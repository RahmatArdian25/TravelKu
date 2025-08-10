<?php
namespace App\Models;

use CodeIgniter\Model;

class ModelPemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'idpemesanan';
    protected $allowedFields = ['id_user', 'idrute', 'tanggal', 'jumlah_orang', 'total', 'status','bukti_pembayaran','idjadwal'];
    protected $useAutoIncrement = true;
    
   protected $useTimestamps = true;
protected $createdField  = 'created_at';
protected $updatedField  = 'updated_at';


    protected $validationRules = [
        'id_user' => 'required|numeric',
        'idrute' => 'required|numeric',
        'tanggal' => 'required|valid_date',
        'jumlah_orang' => 'required|numeric',
        'total' => 'required|numeric',
        'status' => 'required|in_list[sudah berangkat,belum bayar,sudah bayar belum konfirmasi,pembayaran sudah di konfirmasi,dibatalkan]'
    ];
    
    protected $validationMessages = [
        'id_user' => ['required' => 'User harus dipilih'],
        'idrute' => ['required' => 'Rute harus dipilih'],
        'tanggal' => [
            'required' => 'Tanggal harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'jumlah_orang' => [
            'required' => 'Jumlah orang harus diisi',
            'numeric' => 'Jumlah orang harus berupa angka'
        ],
        'total' => [
            'required' => 'Total harus diisi',
            'numeric' => 'Total harus berupa angka'
        ],
        'status' => ['required' => 'Status harus dipilih']
    ];

    // Di Model Pemesanan
public function getBookedSeats($idrute, $tanggal)
{
    return $this->db->table('detailpemesanan')
        ->select('kursikendaraanid')
        ->join('pemesanan', 'pemesanan.idpemesanan = detailpemesanan.idpemesanan')
        ->where('pemesanan.idrute', $idrute)
        ->where('pemesanan.tanggal', $tanggal)
        ->where('pemesanan.status !=', 'dibatalkan') // Tidak termasuk yang dibatalkan
        ->get()
        ->getResultArray();
}
    public function getPemesananWithDetails($userId = null)
    {
        $builder = $this->select('pemesanan.*, user.nama_user, rute.asal, rute.tujuan, rute.harga, 
                                kendaraan.namakendaraan, kendaraan.nopolisi_kendaraan, 
                                jadwalberangkat.jam as jam_berangkat')
                        ->join('user', 'user.id_user = pemesanan.id_user')
                        ->join('rute', 'rute.idrute = pemesanan.idrute')
                        ->join('kendaraan', 'kendaraan.idkendaraan = rute.idkendaraan', 'left')
                        ->join('jadwalberangkat', 'jadwalberangkat.idjadwal = pemesanan.idjadwal', 'left');
        
        if ($userId !== null) {
            $builder->where('pemesanan.id_user', $userId);
        }
        
        return $builder->orderBy('pemesanan.tanggal', 'DESC')
                      ->findAll();
    }

    public function getDetailPemesanan($idpemesanan)
    {
        $detailModel = new ModelDetailPemesanan();
        return $detailModel->where('pemesananid', $idpemesanan)
                          ->join('kursikendaraan', 'kursikendaraan.idkursi = detailpemesanan.kursikendaraanid')
                          ->findAll();
    }

    public function getPemesananByMonth($bulan, $tahun, $userId = null)
    {
        $builder = $this->db->table('pemesanan p')
            ->select('p.idpemesanan, r.asal, r.tujuan, u.nama_user, p.tanggal, p.jumlah_orang, 
                     p.status, p.total, k.namakendaraan, k.nopolisi_kendaraan')
            ->join('user u', 'u.id_user = p.id_user')
            ->join('rute r', 'r.idrute = p.idrute')
            ->join('kendaraan k', 'k.idkendaraan = r.idkendaraan', 'left')
            ->where('MONTH(p.tanggal)', $bulan)
            ->where('YEAR(p.tanggal)', $tahun)
            ->orderBy('p.tanggal', 'ASC');
        
        if ($userId !== null) {
            $builder->where('p.id_user', $userId);
        }
        
        return $builder->get()->getResultArray();
    }

    public function getPemesananByYear($tahun, $userId = null)
    {
        $builder = $this->db->table('pemesanan p')
            ->select('p.*, u.nama_user, r.asal, r.tujuan, k.namakendaraan, k.nopolisi_kendaraan')
            ->join('user u', 'u.id_user = p.id_user', 'left')
            ->join('rute r', 'r.idrute = p.idrute', 'left')
            ->join('kendaraan k', 'k.idkendaraan = r.idkendaraan', 'left')
            ->where('YEAR(p.tanggal)', $tahun);
        
        if ($userId !== null) {
            $builder->where('p.id_user', $userId);
        }
        
        return $builder->orderBy('p.tanggal', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    public function getPemesananWithKendaraan($idpemesanan)
    {
        return $this->select('pemesanan.*, kendaraan.namakendaraan, kendaraan.nopolisi_kendaraan')
                   ->join('rute', 'rute.idrute = pemesanan.idrute')
                   ->join('kendaraan', 'kendaraan.idkendaraan = rute.idkendaraan')
                   ->where('pemesanan.idpemesanan', $idpemesanan)
                   ->first();
    }

    public function getKursiTerpakai($tanggal, $idrute)
    {
        return $this->select('detailpemesanan.kursikendaraanid')
                   ->join('detailpemesanan', 'detailpemesanan.pemesananid = pemesanan.idpemesanan')
                   ->where('pemesanan.tanggal', $tanggal)
                   ->where('pemesanan.idrute', $idrute)
                   ->findAll();
    }
}