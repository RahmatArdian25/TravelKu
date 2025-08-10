<?php
namespace App\Controllers;

use App\Models\ModelJadwalBerangkat;
use CodeIgniter\Exceptions\PageNotFoundException;

class JadwalBerangkatController extends BaseController
{
    protected $model;
    
    public function __construct()
    {
        $this->model = new ModelJadwalBerangkat();
        helper('form');
    }
    
    public function index()
{
    $tanggal = $this->request->getGet('tanggal') ?? '';
    
    $data = [
        'title' => 'Daftar Jadwal Berangkat',
        'jadwal' => $this->model->getFilteredJadwal($tanggal),
        'filter_tanggal' => $tanggal
    ];
    
    return view('jadwalberangkat/index', $data);
}
    
    public function create()
    {
        $data = [
            'title' => 'Tambah Jadwal Berangkat',
            'supir' => $this->model->getSupir(),
            'kendaraan' => $this->model->getKendaraan(),
            'rute' => $this->model->getRute(),
            'validation' => \Config\Services::validation()
        ];
        
        return view('jadwalberangkat/create', $data);
    }
    
   public function store()
{
    $rules = $this->model->getValidationRules();
    
    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
    
    $data = [
        'iduser' => $this->request->getPost('iduser'),
        'idkendaraan' => $this->request->getPost('idkendaraan'),
        'idrute' => $this->request->getPost('idrute'),
        'tanggal' => $this->request->getPost('tanggal'),
        'jam' => $this->request->getPost('jam')
    ];
    
    if ($this->model->save($data)) {
        return redirect()->to('/jadwalberangkat')->with('message', 'Jadwal berhasil ditambahkan');
    } else {
        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }
}

    
  public function edit($id)
{
    $jadwal = $this->model->find($id);

    if (!$jadwal) {
        throw new PageNotFoundException("Jadwal dengan ID $id tidak ditemukan");
    }

    // Get all drivers with their schedules
    $supir = $this->model->getSupir();
    $supirWithSchedules = [];
    foreach ($supir as $s) {
        $s['jadwal'] = $this->model->getJadwalSupir($s['id_user']);
        $supirWithSchedules[] = $s;
    }

    $data = [
        'title'     => 'Edit Jadwal Berangkat',
        'jadwal'    => $jadwal,
        'supir'     => $supirWithSchedules, // Use the new array with schedules
        'kendaraan' => $this->model->getKendaraan(),
        'rute'      => $this->model->getRute(),
        'validation'=> \Config\Services::validation()
    ];

    return view('jadwalberangkat/edit', $data);
}
    public function update($id)
{
    $rules = $this->model->getValidationRules();

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Ambil data input
    $iduser = $this->request->getPost('iduser');
    $idkendaraan = $this->request->getPost('idkendaraan');
    $idrute = $this->request->getPost('idrute');
    $tanggal = $this->request->getPost('tanggal');
    $jam = $this->request->getPost('jam');

    // Ambil data rute untuk mendapatkan jadwal berangkat
    $ruteModel = new \App\Models\ModelRute();
    $ruteInfo = $ruteModel->find($idrute);
    $jadwalBerangkat = $ruteInfo['jadwalberangkat'] ?? 'pagi';

    // Validasi ketersediaan supir
    $supirQuery = $this->model->getSupirQuery()
        ->where('id_user', $iduser);

    // Tambahkan kondisi berdasarkan jadwal berangkat
    if ($jadwalBerangkat === 'pagi') {
        // Supir tidak boleh memiliki jadwal pagi di tanggal yang sama
        $supirQuery->whereNotIn('id_user', function($builder) use ($tanggal) {
            return $builder->select('iduser')
                ->from('jadwalberangkat')
                ->where('tanggal', $tanggal)
                ->where('jam', '10:00');
        });
    } elseif ($jadwalBerangkat === 'siang') {
        // Supir tidak boleh memiliki jadwal pagi atau siang di tanggal yang sama
        $supirQuery->whereNotIn('id_user', function($builder) use ($tanggal) {
            return $builder->select('iduser')
                ->from('jadwalberangkat')
                ->where('tanggal', $tanggal)
                ->whereIn('jam', ['10:00', '14:00']);
        });
    } elseif ($jadwalBerangkat === 'malam') {
        // Supir tidak boleh memiliki jadwal siang atau malam di tanggal yang sama
        $supirQuery->whereNotIn('id_user', function($builder) use ($tanggal) {
            return $builder->select('iduser')
                ->from('jadwalberangkat')
                ->where('tanggal', $tanggal)
                ->whereIn('jam', ['14:00', '19:00']);
        });
    }

    $supirAvailable = $supirQuery->countAllResults() > 0;

    if (!$supirAvailable) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Supir yang dipilih tidak tersedia untuk jadwal ini karena sudah memiliki tugas di waktu yang sama');
    }

    $data = [
        'idjadwal'    => $id,
        'iduser'      => $iduser,
        'idkendaraan' => $idkendaraan,
        'idrute'      => $idrute,
        'tanggal'     => $tanggal,
        'jam'         => $jam
    ];

    if ($this->model->save($data)) {
        return redirect()->to('/jadwalberangkat')->with('message', 'Jadwal berhasil diperbarui');
    } else {
        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }
}
    public function delete($id)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/jadwalberangkat')->with('message', 'Jadwal berhasil dihapus');
        } else {
            return redirect()->to('/jadwalberangkat')->with('error', 'Gagal menghapus jadwal');
        }
    }

    public function laporanJadwal()
{
    $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
    
    $data = [
        'title' => 'Laporan Jadwal Berangkat',
        'jadwal' => $this->model->getJadwalByTanggal($tanggal),
        'tanggal' => $tanggal
    ];
    
    return view('laporan/jadwal', $data);
}

public function generatePdfJadwal()
{
    $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
    $jadwal = $this->model->getJadwalByTanggal($tanggal);
    
    $data = [
        'title' => 'Laporan Jadwal Berangkat',
        'jadwal' => $jadwal,
        'tanggal' => $tanggal
    ];
    
    $html = view('laporan/pdf_jadwal', $data);
    
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream('laporan-jadwal-berangkat.pdf', ['Attachment' => true]);
}

public function generateSuratJalan($id)
{
    $jadwal = $this->model->getJadwalWithDetails($id);
    
    if (!$jadwal) {
        throw new PageNotFoundException("Jadwal dengan ID $id tidak ditemukan");
    }
    
    // Get passenger data for this schedule
    $penumpang = $this->model->getPenumpangByJadwal($id);
    
    // Generate nomor surat jalan
    $nomorSurat = 'SJ-' . date('Ymd') . '-' . $jadwal['idjadwal'];
    
    $data = [
        'title' => 'Surat Jalan',
        'jadwal' => $jadwal,
        'penumpang' => $penumpang,
        'nomor_surat' => $nomorSurat
    ];
    
    return view('laporan/surat_jalan', $data);
}
public function generatePdfSuratJalan($id)
{
    $jadwal = $this->model->getJadwalWithDetails($id);
    
    if (!$jadwal) {
        throw new PageNotFoundException("Jadwal dengan ID $id tidak ditemukan");
    }
    
    // Get passenger data for this schedule
    $penumpang = $this->model->getPenumpangByJadwal($id);
    
    // Generate nomor surat jalan
    $nomorSurat = 'SJ-' . date('Ymd') . '-' . $jadwal['idjadwal'];
    
    $data = [
        'title' => 'Surat Jalan',
        'jadwal' => $jadwal,
        'penumpang' => $penumpang,
        'nomor_surat' => $nomorSurat
    ];
    
    $html = view('laporan/pdf_surat_jalan', $data);
    
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('surat-jalan-'.$nomorSurat.'.pdf', ['Attachment' => true]);
}
}