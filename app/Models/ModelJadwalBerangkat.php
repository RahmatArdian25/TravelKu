<?php
namespace App\Models;

use CodeIgniter\Model;

class ModelJadwalBerangkat extends Model
{
    protected $table      = 'jadwalberangkat';
    protected $primaryKey = 'idjadwal';
    protected $allowedFields = ['iduser', 'idkendaraan', 'idrute', 'tanggal', 'jam'];
    protected $useAutoIncrement = true;
    
    
    protected $validationRules = [
        'iduser' => 'required|numeric',
        'idkendaraan' => 'required|numeric',
        'idrute' => 'required|numeric',
        'tanggal' => 'required|valid_date',
        'jam' => 'required|regex_match[/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/]'

    ];
    
    protected $validationMessages = [
        'iduser' => [
            'required' => 'Supir harus dipilih',
            'numeric' => 'ID User harus berupa angka'
        ],
        'idkendaraan' => [
            'required' => 'Kendaraan harus dipilih',
            'numeric' => 'ID Kendaraan harus berupa angka'
        ],
        'idrute' => [
            'required' => 'Rute harus dipilih',
            'numeric' => 'ID Rute harus berupa angka'
        ],
        'tanggal' => [
            'required' => 'Tanggal harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'jam' => [
            'required' => 'Jam harus diisi',
            'regex_match' => 'Format jam harus HH:MM (contoh: 08:30 atau 14:00)'
        ]
    ];
    
   public function getJadwalWithDetails($id = null)
{
    $builder = $this->select('jadwalberangkat.*, user.nama_user, user.nohp, 
                            kendaraan.namakendaraan, kendaraan.nopolisi_kendaraan,
                            rute.asal, rute.tujuan, rute.harga,
                            COALESCE(penumpang.jumlah_penumpang, 0) as jumlah_penumpang')
                   ->join('user', 'user.id_user = jadwalberangkat.iduser')
                   ->join('kendaraan', 'kendaraan.idkendaraan = jadwalberangkat.idkendaraan')
                   ->join('rute', 'rute.idrute = jadwalberangkat.idrute')
                   ->join('(SELECT idjadwal, COUNT(detailpemesananid) as jumlah_penumpang 
                            FROM pemesanan p 
                            JOIN detailpemesanan dp ON p.idpemesanan = dp.pemesananid 
                            GROUP BY idjadwal) penumpang', 
                          'penumpang.idjadwal = jadwalberangkat.idjadwal', 'left')
                   ->where('user.status', 'supir');

    if ($id !== null) {
        return $builder->where('jadwalberangkat.idjadwal', $id)->first();
    }

    return $builder->findAll();
}
    
    public function getSupir()
    {
        return $this->db->table('user')
            ->where('status', 'supir')
            ->get()
            ->getResultArray();
    }
    
    public function getKendaraan()
    {
        return $this->db->table('kendaraan')
            ->get()
            ->getResultArray();
    }
    
    public function getRute()
    {
        return $this->db->table('rute')
            ->get()
            ->getResultArray();
    }
    public function getJadwalByTanggal($tanggal)
{
    return $this->db->table('jadwalberangkat j')
        ->select('j.*, u.nama_user, k.namakendaraan, r.asal, r.tujuan, r.harga')
        ->join('user u', 'u.id_user = j.iduser')
        ->join('kendaraan k', 'k.idkendaraan = j.idkendaraan')
        ->join('rute r', 'r.idrute = j.idrute')
        ->where('j.tanggal', $tanggal)
        ->orderBy('j.jam', 'ASC')
        ->get()
        ->getResultArray();
}
public function getPenumpangByJadwal($idjadwal)
{
    return $this->db->table('pemesanan p')
        ->join('detailpemesanan dp', 'p.idpemesanan = dp.pemesananid')
        ->join('kursikendaraan kk', 'dp.kursikendaraanid = kk.idkursi')
        ->where('p.idjadwal', $idjadwal)
        ->select('dp.namapenumpang, dp.jeniskelamin, kk.nomorkursi')
        ->get()
        ->getResultArray();
}
// Di App\Models\ModelJadwalBerangkat
public function getJumlahPenumpangByJadwal()
{
    return $this->db->table('pemesanan p')
        ->select('p.idjadwal, COUNT(dp.iddetail) as jumlah_penumpang')
        ->join('detailpemesanan dp', 'p.idpemesanan = dp.pemesananid', 'left')
        ->groupBy('p.idjadwal')
        ->get()
        ->getResultArray();
}
public function getFilteredJadwal($tanggal = '')
{
    $builder = $this->select('jadwalberangkat.*, user.nama_user, user.nohp, 
                            kendaraan.namakendaraan, kendaraan.nopolisi_kendaraan,
                            rute.asal, rute.tujuan, rute.harga,
                            COALESCE(penumpang.jumlah_penumpang, 0) as jumlah_penumpang')
               ->join('user', 'user.id_user = jadwalberangkat.iduser')
               ->join('kendaraan', 'kendaraan.idkendaraan = jadwalberangkat.idkendaraan')
               ->join('rute', 'rute.idrute = jadwalberangkat.idrute')
               ->join('(SELECT idjadwal, COUNT(detailpemesananid) as jumlah_penumpang 
                        FROM pemesanan p 
                        JOIN detailpemesanan dp ON p.idpemesanan = dp.pemesananid 
                        GROUP BY idjadwal) penumpang', 
                      'penumpang.idjadwal = jadwalberangkat.idjadwal', 'left')
               ->where('user.status', 'supir');

    if (!empty($tanggal)) {
        $builder->where('jadwalberangkat.tanggal', $tanggal);
    }

    return $builder->orderBy('jadwalberangkat.tanggal', 'ASC')
                  ->orderBy('jadwalberangkat.jam', 'ASC')
                  ->findAll();
}

public function getSupirQuery()
{
    return $this->db->table('user')
        ->where('status', 'supir');
}

public function getJadwalSupir($idSupir)
{
    return $this->db->table('jadwalberangkat')
        ->select('tanggal, jam')
        ->where('iduser', $idSupir)
        ->orderBy('tanggal', 'ASC')
        ->orderBy('jam', 'ASC')
        ->get()
        ->getResultArray();
}
}