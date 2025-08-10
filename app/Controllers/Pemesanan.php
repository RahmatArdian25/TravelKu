<?php
namespace App\Controllers;

use App\Models\ModelPemesanan;
use App\Models\ModelDetailPemesanan;
use App\Models\ModelUser;
use App\Models\ModelRute;
use App\Models\ModelKendaraan;
use App\Models\ModelKursiKendaraan;
use App\Models\ModelJadwalBerangkat;

class Pemesanan extends BaseController
{
    protected $modelPemesanan;
    protected $modelDetailPemesanan;
    protected $modelUser;
    protected $modelRute;
    protected $modelKendaraan;
    protected $modelKursiKendaraan;
    protected $modelJadwalBerangkat;

    public function __construct()
    {
        $this->modelPemesanan = new ModelPemesanan();
        $this->modelDetailPemesanan = new ModelDetailPemesanan();
        $this->modelUser = new ModelUser();
        $this->modelRute = new ModelRute();
        $this->modelKendaraan = new ModelKendaraan();
        $this->modelKursiKendaraan = new ModelKursiKendaraan();
        $this->modelJadwalBerangkat = new ModelJadwalBerangkat();
    }

   public function index()
{
    // 🔍 Cari pesanan yang sudah lewat 3 menit dan belum dibayar
    $expiredOrders = $this->modelPemesanan
        ->where('status', 'belum bayar')
        ->where('created_at <=', date('Y-m-d H:i:s', strtotime('-3 minutes')))
        ->findAll();

    if (!empty($expiredOrders)) {
        $this->modelPemesanan->transStart();

        try {
            foreach ($expiredOrders as $order) {
                $id = $order['idpemesanan'];

                // Ambil nama file bukti pembayaran
                $buktiFileName = $order['bukti_pembayaran'];

                // Hapus detail pemesanan
                $this->modelDetailPemesanan->where('pemesananid', $id)->delete();

                // Hapus data pemesanan
                $this->modelPemesanan->delete($id);

                // Hapus file bukti pembayaran kalau ada
                if ($buktiFileName && file_exists('uploads/bukti_pembayaran/' . $buktiFileName)) {
                    unlink('uploads/bukti_pembayaran/' . $buktiFileName);
                }
            }

            $this->modelPemesanan->transComplete();

            session()->setFlashdata(
                'alert',
                count($expiredOrders) . " pemesanan sudah lewat dari 3 menit dan belum dibayar, data dihapus."
            );
        } catch (\Exception $e) {
            $this->modelPemesanan->transRollback();
            session()->setFlashdata(
                'error',
                'Gagal menghapus pemesanan otomatis: ' . $e->getMessage()
            );
        }
    }

    // 👤 Ambil user dari session
    $user = session()->get('user');

    // 📦 Ambil data sesuai role
    if ($user && isset($user['status']) && $user['status'] === 'penumpang') {
        $pemesanan = $this->modelPemesanan->getPemesananWithDetails($user['id']);
    } else {
        $pemesanan = $this->modelPemesanan->getPemesananWithDetails();
    }

    $data = [
        'title'     => 'Daftar Pemesanan',
        'pemesanan' => $pemesanan,
        'user'      => $user
    ];

    return view('pemesanan/index', $data);
}

   public function create()
{
    $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

    $data = [
        'title' => 'Tambah Pemesanan',
        'users' => $this->modelUser->where('status', 'penumpang')->findAll(),
        'rutes' => $this->modelRute->getRutesWithAvailableSeats($tanggal),
        'kendaraans' => $this->modelKendaraan->findAll(),
        'tanggal' => $tanggal,
        'validation' => \Config\Services::validation()
    ];
    return view('pemesanan/create', $data);
}

public function getAvailableRutes()
{
    $tanggal = $this->request->getGet('tanggal');

    if (!$tanggal) {
        return $this->response->setJSON(['error' => 'Parameter tanggal diperlukan'])->setStatusCode(400);
    }

    try {
        $rutes = $this->modelRute->getRutesWithAvailableSeats($tanggal);
        return $this->response->setJSON($rutes);
    } catch (\Throwable $e) {
        return $this->response->setJSON(['error' => $e->getMessage()])->setStatusCode(500);
    }
}



    public function store()
{
    // Validasi input
    $rules = [
        'id_user' => 'required|numeric',
        'idrute' => 'required|numeric',
        'tanggal' => 'required|valid_date',
        'jumlah_orang' => 'required|numeric',
        'total' => 'required|numeric',
        'bukti_pembayaran' => [
            'rules' => 'max_size[bukti_pembayaran,2048]|is_image[bukti_pembayaran]|mime_in[bukti_pembayaran,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Ukuran gambar terlalu besar (maksimal 2MB)',
                'is_image' => 'File yang diupload harus berupa gambar',
                'mime_in' => 'Format gambar tidak valid (hanya JPG, JPEG, PNG)'
            ]
        ]
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $userId  = $this->request->getPost('id_user');
    $tanggal = $this->request->getPost('tanggal');
    $idrute  = $this->request->getPost('idrute');

    // Validasi data penumpang
    $penumpangData = $this->request->getPost('penumpang');
    if (!$penumpangData || !is_array($penumpangData)) {
        return redirect()->back()->withInput()->with('errors', ['penumpang' => 'Data penumpang wajib diisi.']);
    }

    // Cek duplikasi kursi
    $selectedSeats = [];
    foreach ($penumpangData as $penumpang) {
        $kursiId = $penumpang['kursikendaraanid'] ?? null;
        if ($kursiId) {
            if (in_array($kursiId, $selectedSeats)) {
                $kursiInfo = $this->modelKursiKendaraan->find($kursiId);
                $kursiName = $kursiInfo ? 'Kursi ' . $kursiInfo['nomorkursi'] : 'Kursi ID ' . $kursiId;
                return redirect()->back()->withInput()->with('errors', ['kursi' => $kursiName . ' dipilih lebih dari satu kali']);
            }
            $selectedSeats[] = $kursiId;
        }
    }

    // Cek kursi yang sudah dibooking
    $kursiTerpakai = [];
    foreach ($penumpangData as $penumpang) {
        $kursiId = $penumpang['kursikendaraanid'] ?? null;
        if ($kursiId) {
            $isBooked = $this->modelPemesanan
                ->join('detailpemesanan', 'pemesanan.idpemesanan = detailpemesanan.pemesananid')
                ->join('kursikendaraan', 'detailpemesanan.kursikendaraanid = kursikendaraan.idkursi')
                ->join('kendaraan', 'kursikendaraan.idkendaraan = kendaraan.idkendaraan')
                ->where('pemesanan.tanggal', $tanggal)
                ->where('pemesanan.idrute', $idrute)
                ->where('detailpemesanan.kursikendaraanid', $kursiId)
                ->select('kursikendaraan.nomorkursi, kendaraan.namakendaraan as nama_kendaraan')
                ->first();

            if ($isBooked) {
                $kursiTerpakai[] = $isBooked['nomorkursi'] . ' (' . $isBooked['nama_kendaraan'] . ')';
            }
        }
    }

    if (!empty($kursiTerpakai)) {
        return redirect()->back()->withInput()->with('errors', [
            'kursi' => 'Kursi ' . implode(', ', $kursiTerpakai) . ' sudah dipesan di tanggal yang sama'
        ]);
    }

    // Handle upload bukti pembayaran
    $buktiPembayaran = $this->request->getFile('bukti_pembayaran');
    $buktiFileName   = null;
    $status          = $this->request->getPost('status');

    if ($buktiPembayaran->isValid() && !$buktiPembayaran->hasMoved()) {
        $buktiFileName = $buktiPembayaran->getRandomName();
        $buktiPembayaran->move('uploads/bukti_pembayaran', $buktiFileName);
    }

    $this->modelPemesanan->transStart();

    try {
        // Ambil data rute
        $ruteInfo      = $this->modelRute->find($idrute);
        $idkendaraan   = $ruteInfo['idkendaraan'] ?? null;
        $jadwalBerangkat = $ruteInfo['jadwalberangkat'] ?? 'pagi';

        // Tentukan jam berangkat
        $jam = match ($jadwalBerangkat) {
            'siang' => '14:00',
            'malam' => '19:00',
            default => '10:00'
        };

        // Cek apakah jadwal sudah ada
        $jadwalExist = $this->modelJadwalBerangkat
            ->where('idrute', $idrute)
            ->where('tanggal', $tanggal)
            ->where('idkendaraan', $idkendaraan)
            ->first();

        if (!$jadwalExist) {
            // Cari supir yang belum punya jadwal di semua shift tanggal ini
            $supirQuery = $this->modelUser->where('status', 'supir');

            // Kondisi existing untuk shift
            if ($jadwalBerangkat === 'pagi') {
                $supirQuery->whereNotIn('id_user', function($builder) use ($tanggal) {
                    return $builder->select('iduser')
                        ->from('jadwalberangkat')
                        ->where('tanggal', $tanggal)
                        ->where('jam', '10:00');
                });
            } elseif ($jadwalBerangkat === 'siang') {
                $supirQuery->whereNotIn('id_user', function($builder) use ($tanggal) {
                    return $builder->select('iduser')
                        ->from('jadwalberangkat')
                        ->where('tanggal', $tanggal)
                        ->whereIn('jam', ['10:00', '14:00']);
                });
            } elseif ($jadwalBerangkat === 'malam') {
                $supirQuery->whereNotIn('id_user', function($builder) use ($tanggal) {
                    return $builder->select('iduser')
                        ->from('jadwalberangkat')
                        ->where('tanggal', $tanggal)
                        ->whereIn('jam', ['14:00', '19:00']);
                });
            }

            // Cek supir terakhir
            $lastSupir   = $this->modelJadwalBerangkat->select('iduser')->orderBy('idjadwal', 'DESC')->first();
            $lastSupirId = $lastSupir['iduser'] ?? null;

            $supirList = $supirQuery->orderBy('id_user', 'ASC')->findAll();

            if ($supirList) {
                if ($lastSupirId) {
                    $index = array_search($lastSupirId, array_column($supirList, 'id_user'));
                    $nextIndex = ($index === false || $index + 1 >= count($supirList)) ? 0 : $index + 1;
                    $supir = $supirList[$nextIndex];
                } else {
                    $supir = $supirList[0];
                }

                $jadwalData = [
                    'iduser'     => $supir['id_user'],
                    'idkendaraan'=> $idkendaraan,
                    'idrute'     => $idrute,
                    'tanggal'    => $tanggal,
                    'jam'        => $jam
                ];

                $this->modelJadwalBerangkat->insert($jadwalData);
                $idjadwal = $this->modelJadwalBerangkat->insertID();
            } else {
                $idjadwal = null;
                $this->modelPemesanan->transRollback();
                return redirect()->back()->withInput()->with('error', 'Tidak ada supir yang tersedia untuk jadwal ini');
            }

        } else {
            $idjadwal = $jadwalExist['idjadwal'];
        }

        // Simpan pemesanan
        $pemesananData = [
            'id_user'          => $userId,
            'idrute'           => $idrute,
            'tanggal'          => $tanggal,
            'jumlah_orang'     => $this->request->getPost('jumlah_orang'),
            'total'            => $this->request->getPost('total'),
            'status'           => $status,
            'bukti_pembayaran' => $buktiFileName,
            'idjadwal'         => $idjadwal,
        ];

        $this->modelPemesanan->insert($pemesananData);
        $pemesananId = $this->modelPemesanan->insertID();

        foreach ($penumpangData as $penumpang) {
            $this->modelDetailPemesanan->insert([
                'pemesananid'     => $pemesananId,
                'kursikendaraanid'=> $penumpang['kursikendaraanid'] ?? null,
                'namapenumpang'   => $penumpang['namapenumpang'] ?? '',
                'jeniskelamin'    => $penumpang['jeniskelamin'] ?? ''
            ]);
        }

        $this->modelPemesanan->transComplete();

        return redirect()->to('/pemesanan')->with('message', 'Pemesanan berhasil ditambahkan');

    } catch (\Exception $e) {
        $this->modelPemesanan->transRollback();
        if ($buktiFileName && file_exists('uploads/bukti_pembayaran/' . $buktiFileName)) {
            unlink('uploads/bukti_pembayaran/' . $buktiFileName);
        }
        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pemesanan: ' . $e->getMessage());
    }
}

   // In your Pemesanan controller's edit method:
public function edit($id)
{
    $pemesanan = $this->modelPemesanan->find($id);
    if (!$pemesanan) {
        return redirect()->to('/pemesanan')->with('error', 'Pemesanan tidak ditemukan');
    }

    // Dapatkan data terkait
    $jadwal = $this->modelJadwalBerangkat->find($pemesanan['idjadwal']);
    $kendaraan = $jadwal ? $this->modelKendaraan->find($jadwal['idkendaraan']) : null;
    $supir = $jadwal ? $this->modelUser->find($jadwal['iduser']) : null;
    $kursi = $jadwal ? $this->modelKursiKendaraan->where('idkendaraan', $jadwal['idkendaraan'])->findAll() : [];

    $data = [
        'title' => 'Edit Pemesanan',
        'pemesanan' => $pemesanan,
        'detail_pemesanan' => $this->modelDetailPemesanan->where('pemesananid', $id)->findAll(),
        'users' => $this->modelUser->where('status', 'penumpang')->findAll(),
        'rutes' => $this->modelRute->findAll(),
        'jadwal' => $jadwal,
        'kendaraan' => $kendaraan,
        'supir' => $supir,
        'kursi' => $kursi,
        'validation' => \Config\Services::validation()
    ];

    return view('pemesanan/edit', $data);
}

  public function update($id)
{
    $pemesanan = $this->modelPemesanan->find($id);
    if (!$pemesanan) {
        return redirect()->to('/pemesanan')->with('error', 'Pemesanan tidak ditemukan');
    }

    // Rules validasi
    $rules = [
        'id_user' => 'required|numeric',
        'idrute' => 'required|numeric',
        'tanggal' => 'required|valid_date',
        'jumlah_orang' => 'required|numeric',
        'total' => 'required|numeric',
        'status' => 'permit_empty|in_list[belum bayar,sudah bayar belum konfirmasi,pembayaran sudah di konfirmasi,sudah berangkat]',
        'bukti_pembayaran' => [
            'rules' => 'max_size[bukti_pembayaran,2048]|is_image[bukti_pembayaran]|mime_in[bukti_pembayaran,image/jpg,image/jpeg,image/png]',
            'errors' => [
                'max_size' => 'Ukuran gambar terlalu besar (maksimal 2MB)',
                'is_image' => 'File yang diupload harus berupa gambar',
                'mime_in' => 'Format gambar tidak valid (hanya JPG, JPEG, PNG)'
            ]
        ]
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Handle status berdasarkan bukti pembayaran
    $buktiPembayaran = $this->request->getFile('bukti_pembayaran');
    $status = $this->request->getPost('status');
    
    // Jika ada upload bukti pembayaran, paksa status ke 'sudah bayar belum konfirmasi'
    if ($buktiPembayaran->isValid() && !$buktiPembayaran->hasMoved()) {
        $status = 'sudah bayar belum konfirmasi';
    }
    // Jika tidak ada status yang dipilih, gunakan status lama
    elseif (empty($status)) {
        $status = $pemesanan['status'];
    }

    // Validate passenger data
    $penumpangData = $this->request->getPost('penumpang');
    if (!$penumpangData || !is_array($penumpangData)) {
        return redirect()->back()->withInput()->with('errors', ['penumpang' => 'Data penumpang wajib diisi.']);
    }

    // Check for duplicate seats
    $selectedSeats = [];
    foreach ($penumpangData as $penumpang) {
        $kursiId = $penumpang['kursikendaraanid'] ?? null;
        if ($kursiId) {
            if (in_array($kursiId, $selectedSeats)) {
                $kursiInfo = $this->modelKursiKendaraan->find($kursiId);
                $kursiName = $kursiInfo ? 'Kursi ' . $kursiInfo['nomorkursi'] : 'Kursi ID ' . $kursiId;
                return redirect()->back()->withInput()->with('errors', ['kursi' => $kursiName . ' dipilih lebih dari satu kali']);
            }
            $selectedSeats[] = $kursiId;
        }
    }

    // Check for already booked seats (excluding current booking)
    $idrute = $this->request->getPost('idrute');
    $tanggal = $this->request->getPost('tanggal');
    $kursiTerpakai = [];

    foreach ($penumpangData as $penumpang) {
        $kursiId = $penumpang['kursikendaraanid'] ?? null;
        if ($kursiId) {
            $isBooked = $this->modelPemesanan
                ->join('detailpemesanan', 'pemesanan.idpemesanan = detailpemesanan.pemesananid')
                ->join('kursikendaraan', 'detailpemesanan.kursikendaraanid = kursikendaraan.idkursi')
                ->join('kendaraan', 'kursikendaraan.idkendaraan = kendaraan.idkendaraan')
                ->where('pemesanan.tanggal', $tanggal)
                ->where('pemesanan.idrute', $idrute)
                ->where('detailpemesanan.kursikendaraanid', $kursiId)
                ->where('pemesanan.idpemesanan !=', $id)
                ->select('kursikendaraan.nomorkursi, kendaraan.namakendaraan as nama_kendaraan')
                ->first();

            if ($isBooked) {
                $kursiTerpakai[] = $isBooked['nomorkursi'] . ' (' . $isBooked['nama_kendaraan'] . ')';
            }
        }
    }

    if (!empty($kursiTerpakai)) {
        return redirect()->back()->withInput()->with('errors', [
            'kursi' => 'Kursi ' . implode(', ', $kursiTerpakai) . ' sudah dipesan di tanggal yang sama'
        ]);
    }

    // Handle file upload
    $buktiPembayaran = $this->request->getFile('bukti_pembayaran');
    $oldBukti = $this->request->getPost('old_bukti_pembayaran');
    $buktiFileName = $oldBukti;

    if ($buktiPembayaran->isValid() && !$buktiPembayaran->hasMoved()) {
        $buktiFileName = $buktiPembayaran->getRandomName();
        $buktiPembayaran->move('uploads/bukti_pembayaran', $buktiFileName);
        
        if ($oldBukti && file_exists('uploads/bukti_pembayaran/' . $oldBukti)) {
            unlink('uploads/bukti_pembayaran/' . $oldBukti);
        }
    }

    $this->modelPemesanan->transStart();

    try {
        // Find or create departure schedule
        $ruteInfo = $this->modelRute->find($idrute);
        $idkendaraan = $ruteInfo['idkendaraan'] ?? null;
        $jadwalBerangkat = $ruteInfo['jadwalberangkat'] ?? 'pagi'; // Ambil jadwalberangkat dari rute

        // Tentukan jam berdasarkan jadwalberangkat
        $jam = '10:00'; // Default untuk pagi
        if ($jadwalBerangkat === 'siang') {
            $jam = '14:00';
        } else if ($jadwalBerangkat === 'malam') {
            $jam = '19:00';
        }

        $jadwalExist = $this->modelJadwalBerangkat
            ->where('idrute', $idrute)
            ->where('tanggal', $tanggal)
            ->where('idkendaraan', $idkendaraan)
            ->first();

        if (!$jadwalExist) {
            // Query untuk mencari supir yang tersedia berdasarkan jadwal
            $supirQuery = $this->modelUser->where('status', 'supir');
            
            // Tambahkan kondisi berdasarkan jadwal berangkat
            if ($jadwalBerangkat === 'pagi') {
                // Supir yang tidak memiliki jadwal pagi di tanggal yang sama
                $supirQuery->whereNotIn('id_user', function($builder) use ($tanggal) {
                    return $builder->select('iduser')
                        ->from('jadwalberangkat')
                        ->where('tanggal', $tanggal)
                        ->where('jam', '10:00');
                });
            } elseif ($jadwalBerangkat === 'siang') {
                // Supir yang tidak memiliki jadwal pagi atau siang di tanggal yang sama
                $supirQuery->whereNotIn('id_user', function($builder) use ($tanggal) {
                    return $builder->select('iduser')
                        ->from('jadwalberangkat')
                        ->where('tanggal', $tanggal)
                        ->whereIn('jam', ['10:00', '14:00']);
                });
            } elseif ($jadwalBerangkat === 'malam') {
                // Supir yang tidak memiliki jadwal siang atau malam di tanggal yang sama
                $supirQuery->whereNotIn('id_user', function($builder) use ($tanggal) {
                    return $builder->select('iduser')
                        ->from('jadwalberangkat')
                        ->where('tanggal', $tanggal)
                        ->whereIn('jam', ['14:00', '19:00']);
                });
            }
            
            // Ambil supir yang tersedia secara acak
            $supir = $supirQuery->orderBy('RAND()')->first();

            // Jika ditemukan supir yang tersedia
            if ($supir) {
                $jadwalData = [
                    'iduser' => $supir['id_user'],
                    'idkendaraan' => $idkendaraan,
                    'idrute' => $idrute,
                    'tanggal' => $tanggal,
                    'jam' => $jam
                ];

                $this->modelJadwalBerangkat->insert($jadwalData);
                $idjadwal = $this->modelJadwalBerangkat->insertID();
            } else {
                // Tidak ada supir yang tersedia
                $idjadwal = null;
                $this->modelPemesanan->transRollback();
                return redirect()->back()->withInput()->with('error', 'Tidak ada supir yang tersedia untuk jadwal ini');
            }
        } else {
            $idjadwal = $jadwalExist['idjadwal'];
        }

        $pemesananData = [
            'id_user' => $this->request->getPost('id_user'),
            'idrute' => $idrute,
            'tanggal' => $tanggal,
            'jumlah_orang' => $this->request->getPost('jumlah_orang'),
            'total' => $this->request->getPost('total'),
            'status' => $status,
            'bukti_pembayaran' => $buktiFileName,
            'idjadwal' => $idjadwal,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->modelPemesanan->update($id, $pemesananData);

        // Delete old details and insert new ones
        $this->modelDetailPemesanan->where('pemesananid', $id)->delete();

        foreach ($penumpangData as $penumpang) {
            $detailData = [
                'pemesananid' => $id,
                'kursikendaraanid' => $penumpang['kursikendaraanid'] ?? null,
                'namapenumpang' => $penumpang['namapenumpang'] ?? '',
                'jeniskelamin' => $penumpang['jeniskelamin'] ?? ''
            ];
            $this->modelDetailPemesanan->insert($detailData);
        }

        $this->modelPemesanan->transComplete();

        return redirect()->to('/pemesanan')->with('message', 'Pemesanan berhasil diperbarui');
    } catch (\Exception $e) {
        $this->modelPemesanan->transRollback();
        if ($buktiFileName && $buktiFileName !== $oldBukti && file_exists('uploads/bukti_pembayaran/' . $buktiFileName)) {
            unlink('uploads/bukti_pembayaran/' . $buktiFileName);
        }
        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pemesanan: ' . $e->getMessage());
    }
}

    public function delete($id)
    {
        $pemesanan = $this->modelPemesanan->find($id);
        if (!$pemesanan) {
            return redirect()->to('/pemesanan')->with('error', 'Pemesanan tidak ditemukan');
        }

        $this->modelPemesanan->transStart();

        try {
            // Get the bukti_pembayaran filename before deletion
            $buktiFileName = $pemesanan['bukti_pembayaran'];
            
            // Delete detail pemesanan first
            $this->modelDetailPemesanan->where('pemesananid', $id)->delete();
            
            // Then delete pemesanan
            $this->modelPemesanan->delete($id);
            
            // Delete the bukti_pembayaran file if exists
            if ($buktiFileName && file_exists('uploads/bukti_pembayaran/' . $buktiFileName)) {
                unlink('uploads/bukti_pembayaran/' . $buktiFileName);
            }

            $this->modelPemesanan->transComplete();

            return redirect()->to('/pemesanan')->with('message', 'Pemesanan berhasil dihapus');
        } catch (\Exception $e) {
            $this->modelPemesanan->transRollback();
            return redirect()->to('/pemesanan')->with('error', 'Gagal menghapus pemesanan: ' . $e->getMessage());
        }
    }

    // Other methods remain the same...
  public function getKursiByKendaraan($idkendaraan)
{
    try {
        $kursiModel = new ModelKursiKendaraan();
        $kursi = $kursiModel->select('kursikendaraan.*, kendaraan.namakendaraan as nama_kendaraan')
                           ->join('kendaraan', 'kendaraan.idkendaraan = kursikendaraan.idkendaraan')
                           ->where('kursikendaraan.idkendaraan', $idkendaraan)
                           ->findAll();
        
        if (!$kursi) {
            return $this->response->setJSON(['error' => 'Kursi tidak ditemukan untuk kendaraan ini'])->setStatusCode(404);
        }
        
        return $this->response->setJSON($kursi);
    } catch (\Exception $e) {
        return $this->response->setJSON(['error' => $e->getMessage()])->setStatusCode(500);
    }
}

 public function getAvailableKursi()
{
    try {
        $idrute = $this->request->getGet('idrute');
        $tanggal = $this->request->getGet('tanggal');
        $exclude = $this->request->getGet('exclude'); // opsional: idpemesanan yang sedang diedit

        if (!$idrute || !$tanggal) {
            return $this->response->setJSON(['error' => 'Parameter idrute dan tanggal diperlukan'])->setStatusCode(400);
        }

        $jadwalModel = new ModelJadwalBerangkat();
        $kursiModel = new ModelKursiKendaraan();
        $pemesananModel = new ModelPemesanan();
        $ruteModel = new ModelRute();

        $rute = $ruteModel->find($idrute);
        if (!$rute) {
            return $this->response->setJSON(['error' => 'Rute tidak ditemukan'])->setStatusCode(404);
        }

        $jadwal = $jadwalModel->where('idrute', $idrute)
                              ->where('tanggal', $tanggal)
                              ->first();

        if ($jadwal) {
            // Ambil semua pemesanan kecuali yang sedang diedit
            $query = $pemesananModel->where('idjadwal', $jadwal['idjadwal']);
            if ($exclude) {
                $query->where('idpemesanan !=', $exclude);
            }
            $pemesanan = $query->findAll();
            
            // Ambil semua kursi yang sudah dipesan
            $kursiTerpakai = [];
            foreach ($pemesanan as $p) {
                $details = $this->modelDetailPemesanan->where('pemesananid', $p['idpemesanan'])->findAll();
                foreach ($details as $detail) {
                    if ($detail['kursikendaraanid']) {
                        $kursiTerpakai[] = $detail['kursikendaraanid'];
                    }
                }
            }

            // Ambil semua kursi untuk kendaraan ini
            $kursi = $kursiModel->select('kursikendaraan.*, kendaraan.namakendaraan as nama_kendaraan')
                                ->join('kendaraan', 'kendaraan.idkendaraan = kursikendaraan.idkendaraan')
                                ->where('kursikendaraan.idkendaraan', $jadwal['idkendaraan'])
                                ->findAll();

            // Filter hanya kursi yang tersedia
            $availableKursi = array_filter($kursi, function($k) use ($kursiTerpakai) {
                return !in_array($k['idkursi'], $kursiTerpakai);
            });

            return $this->response->setJSON(array_values($availableKursi));
        } else {
            // Jika jadwal belum ada, semua kursi tersedia
            $kursi = $kursiModel->select('kursikendaraan.*, kendaraan.namakendaraan as nama_kendaraan')
                                ->join('kendaraan', 'kendaraan.idkendaraan = kursikendaraan.idkendaraan')
                                ->where('kursikendaraan.idkendaraan', $rute['idkendaraan'])
                                ->findAll();
            return $this->response->setJSON($kursi);
        }
    } catch (\Exception $e) {
        return $this->response->setJSON(['error' => $e->getMessage()])->setStatusCode(500);
    }
}

    
    public function laporanPemesanan()
    {
        $bulan = $this->request->getGet('bulan') ?: date('m');
        $tahun = $this->request->getGet('tahun') ?: date('Y');

        $data = [
            'title' => 'Laporan Pemesanan Per Bulan',
            'pemesanan' => $this->modelPemesanan->getPemesananByMonth($bulan, $tahun),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'months' => [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ]
        ];
        
        return view('laporan/pemesanan', $data);
    }

    public function generatePdfPemesanan()
    {
        $bulan = $this->request->getGet('bulan') ?: date('m');
        $tahun = $this->request->getGet('tahun') ?: date('Y');

        $data = [
            'title' => 'Laporan Pemesanan Per Bulan',
            'pemesanan' => $this->modelPemesanan->getPemesananByMonth($bulan, $tahun),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'monthName' => [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ][$bulan]
        ];
        
        $html = view('laporan/pdf_pemesanan', $data);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("laporan-pemesanan-{$bulan}-{$tahun}.pdf", ['Attachment' => true]);
    }

    public function laporanPemesananTahunan()
    {
        $tahun = $this->request->getGet('tahun') ?: date('Y');

        $data = [
            'title' => 'Laporan Pemesanan Per Tahun',
            'pemesananTahunan' => $this->modelPemesanan->getPemesananByYear($tahun),
            'tahun' => $tahun,
            'years' => range(date('Y') - 5, date('Y') + 1)
        ];
        
        return view('laporan/pemesanan_tahunan', $data);
    }

    public function generatePdfPemesananTahunan()
    {
        $tahun = $this->request->getGet('tahun') ?: date('Y');

        $data = [
            'title' => 'Laporan Pemesanan Tahun ' . $tahun,
            'pemesananTahunan' => $this->modelPemesanan->getPemesananByYear($tahun),
            'tahun' => $tahun
        ];
        
        $html = view('laporan/pdf_pemesanan_tahunan', $data);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("laporan-pemesanan-tahunan-{$tahun}.pdf", ['Attachment' => true]);
    }

    public function cetakTiket($idpemesanan)
{
    $pemesanan = $this->modelPemesanan->find($idpemesanan);
    if (!$pemesanan) {
        return redirect()->to('/pemesanan')->with('error', 'Pemesanan tidak ditemukan');
    }

    // Get additional information
    $rute = $this->modelRute->find($pemesanan['idrute']);
    $jadwal = $this->modelJadwalBerangkat->find($pemesanan['idjadwal']);
    $kendaraan = $jadwal ? $this->modelKendaraan->find($jadwal['idkendaraan']) : null;
    $supir = $jadwal ? $this->modelUser->find($jadwal['iduser']) : null;
    
    // Get detail pemesanan with seat information
    $detail_pemesanan = $this->modelDetailPemesanan->where('pemesananid', $idpemesanan)->findAll();
    $kursiData = [];
    
    foreach ($detail_pemesanan as $detail) {
        $kursi = $detail['kursikendaraanid'] ? $this->modelKursiKendaraan->find($detail['kursikendaraanid']) : null;
        $kursiData[] = [
            'detail' => $detail,
            'kursi' => $kursi
        ];
    }

    $data = [
        'title' => 'Cetak Tiket',
        'pemesanan' => $pemesanan,
        'detail_pemesanan' => $kursiData, // Now includes both detail and seat info
        'user' => $this->modelUser->find($pemesanan['id_user']),
        'rute' => $rute,
        'kendaraan' => $kendaraan,
        'supir' => $supir,
        'jadwal' => $jadwal
    ];
    
    return view('laporan/tiket', $data);
}

public function generatePdfTiket($idpemesanan)
{
    $pemesanan = $this->modelPemesanan->find($idpemesanan);
    if (!$pemesanan) {
        return redirect()->to('/pemesanan')->with('error', 'Pemesanan tidak ditemukan');
    }

    // Get additional information
    $rute = $this->modelRute->find($pemesanan['idrute']);
    $jadwal = $this->modelJadwalBerangkat->find($pemesanan['idjadwal']);
    $kendaraan = $jadwal ? $this->modelKendaraan->find($jadwal['idkendaraan']) : null;
    $supir = $jadwal ? $this->modelUser->find($jadwal['iduser']) : null;
    
    // Get detail pemesanan with seat information
    $detail_pemesanan = $this->modelDetailPemesanan->where('pemesananid', $idpemesanan)->findAll();
    $kursiData = [];
    
    foreach ($detail_pemesanan as $detail) {
        $kursi = $detail['kursikendaraanid'] ? $this->modelKursiKendaraan->find($detail['kursikendaraanid']) : null;
        $kursiData[] = [
            'detail' => $detail,
            'kursi' => $kursi
        ];
    }

    $data = [
        'title' => 'Tiket Perjalanan',
        'pemesanan' => $pemesanan,
        'detail_pemesanan' => $kursiData,
        'user' => $this->modelUser->find($pemesanan['id_user']),
        'rute' => $rute,
        'kendaraan' => $kendaraan,
        'supir' => $supir,
        'jadwal' => $jadwal
    ];
    
    $html = view('laporan/pdf_tiket', $data);
    
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper([0, 0, 300, 500], 'portrait');
    $dompdf->render();
    $dompdf->stream("tiket-{$idpemesanan}.pdf", ['Attachment' => true]);
}


}