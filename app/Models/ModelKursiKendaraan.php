<?php
namespace App\Models;

use CodeIgniter\Model;

class ModelKursiKendaraan extends Model
{
    protected $table = 'kursikendaraan';
    protected $primaryKey = 'idkursi';
    protected $allowedFields = ['idkendaraan', 'nomorkursi', 'jadwalberangkat'];
    protected $useAutoIncrement = true;
    
    protected $validationRules = [
        'idkendaraan' => 'required|numeric|is_not_unique[kendaraan.idkendaraan]',
        'nomorkursi' => 'required|numeric|greater_than[0]',
        'jadwalberangkat' => 'required|in_list[pagi,siang,malam]'
    ];
    
    protected $validationMessages = [
        'idkendaraan' => [
            'required' => 'Kendaraan harus dipilih',
            'numeric' => 'ID Kendaraan harus berupa angka',
            'is_not_unique' => 'Kendaraan tidak valid'
        ],
        'nomorkursi' => [
            'required' => 'Nomor kursi harus diisi',
            'numeric' => 'Nomor kursi harus berupa angka',
            'greater_than' => 'Nomor kursi harus lebih besar dari 0'
        ],
        'jadwalberangkat' => [
            'required' => 'Jadwal berangkat harus dipilih',
            'in_list' => 'Pilihan jadwal berangkat tidak valid'
        ]
    ];

    /**
     * Get seats with vehicle names
     */
    public function getKursiKendaraanWithNamaKendaraan()
    {
        return $this->select('kursikendaraan.*, kendaraan.namakendaraan')
                   ->join('kendaraan', 'kendaraan.idkendaraan = kursikendaraan.idkendaraan')
                   ->orderBy('kendaraan.namakendaraan', 'ASC')
                   ->orderBy('kursikendaraan.nomorkursi', 'ASC')
                   ->findAll();
    }

    /**
     * Check if seat is duplicate
     */
    public function isSeatDuplicate($idkendaraan, $nomorkursi, $jadwalberangkat, $idkursi = null)
    {
        $builder = $this->where('idkendaraan', $idkendaraan)
                       ->where('nomorkursi', $nomorkursi)
                       ->where('jadwalberangkat', $jadwalberangkat);

        if ($idkursi !== null) {
            $builder->where('idkursi !=', $idkursi);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get seats by vehicle
     */
    public function getByKendaraan($idkendaraan)
    {
        return $this->where('idkendaraan', $idkendaraan)
                   ->orderBy('nomorkursi', 'ASC')
                   ->findAll();
    }

    /**
     * Get available seats by route and date
     */
    public function getAvailableByRuteAndDate($idrute, $tanggal)
    {
        $subquery = $this->db->table('pemesanan')
            ->select('detailpemesanan.kursikendaraanid')
            ->join('detailpemesanan', 'detailpemesanan.pemesananid = pemesanan.idpemesanan')
            ->where('pemesanan.idrute', $idrute)
            ->where('pemesanan.tanggal', $tanggal)
            ->getCompiledSelect();

        return $this->select('kursikendaraan.*')
                   ->join('rute', 'rute.idkendaraan = kursikendaraan.idkendaraan')
                   ->where('rute.idrute', $idrute)
                   ->where("kursikendaraan.idkursi NOT IN ($subquery)", null, false)
                   ->findAll();
    }
}