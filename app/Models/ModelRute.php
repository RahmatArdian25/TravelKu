<?php
namespace App\Models;

use CodeIgniter\Model;

class ModelRute extends Model
{
    protected $table      = 'rute';
    protected $primaryKey = 'idrute';
    protected $allowedFields = ['asal', 'tujuan', 'harga', 'jadwalberangkat', 'idkendaraan'];
    protected $useAutoIncrement = true;
    
    protected $validationRules = [
        'asal' => 'required|max_length[100]',
        'tujuan' => 'required|max_length[100]',
        'harga' => 'required|numeric|greater_than[0]',
        'jadwalberangkat' => 'required|in_list[pagi,siang,malam]',
        'idkendaraan' => 'required|numeric|is_not_unique[kendaraan.idkendaraan]'
    ];
    
    protected $validationMessages = [
        'asal' => [
            'required' => 'Asal rute harus diisi',
            'max_length' => 'Asal rute maksimal 100 karakter'
        ],
        'tujuan' => [
            'required' => 'Tujuan rute harus diisi',
            'max_length' => 'Tujuan rute maksimal 100 karakter'
        ],
        'harga' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka',
            'greater_than' => 'Harga harus lebih besar dari 0'
        ],
        'jadwalberangkat' => [
            'required' => 'Jadwal berangkat harus dipilih',
            'in_list' => 'Pilih jadwal berangkat yang valid (pagi, siang, malam)'
        ],
        'idkendaraan' => [
            'required' => 'Kendaraan harus dipilih',
            'numeric' => 'ID Kendaraan harus berupa angka',
            'is_not_unique' => 'Kendaraan tidak valid'
        ]
    ];

    /**
     * Relasi ke kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo('App\Models\ModelKendaraan', 'idkendaraan');
    }

    /**
     * Mendapatkan data rute dengan informasi kendaraan
     */
    public function getWithKendaraan()
    {
        return $this->select('rute.*, kendaraan.namakendaraan, kendaraan.nopolisi_kendaraan, kendaraan.jumlahkursi')
                   ->join('kendaraan', 'kendaraan.idkendaraan = rute.idkendaraan')
                   ->findAll();
    }

    /**
     * Mendapatkan kursi tersedia untuk rute ini pada tanggal tertentu
     */
    public function getAvailableSeats($tanggal)
    {
        if (!$this->idkendaraan) {
            return [];
        }

        // Kursi yang sudah dipesan pada tanggal ini
        $bookedSeats = $this->db->table('pemesanan')
            ->select('detailpemesanan.kursikendaraanid')
            ->join('detailpemesanan', 'detailpemesanan.pemesananid = pemesanan.idpemesanan')
            ->where('pemesanan.idrute', $this->idrute)
            ->where('pemesanan.tanggal', $tanggal)
            ->get()
            ->getResultArray();

        $bookedIds = array_column($bookedSeats, 'kursikendaraanid');

        return $this->kendaraan->getAvailableSeats($bookedIds);
    }

    /**
     * Mendapatkan rute dengan kursi tersedia
     */
  public function getRutesWithAvailableSeats($tanggal)
{
    $routes = $this->select('rute.*, kendaraan.namakendaraan, kendaraan.nopolisi_kendaraan, kendaraan.jumlahkursi')
                   ->join('kendaraan', 'kendaraan.idkendaraan = rute.idkendaraan')
                   ->findAll();

    $result = [];

    foreach ($routes as $route) {
        // Ambil semua kursi kendaraan untuk rute ini
        $totalSeats = $this->db->table('kursikendaraan')
            ->where('idkendaraan', $route['idkendaraan'])
            ->countAllResults();

        // Hitung kursi yang sudah dipesan
        $bookedSeats = $this->db->table('pemesanan')
            ->select('detailpemesanan.kursikendaraanid')
            ->join('detailpemesanan', 'detailpemesanan.pemesananid = pemesanan.idpemesanan')
            ->where('pemesanan.idrute', $route['idrute'])
            ->where('pemesanan.tanggal', $tanggal)
            ->countAllResults();

        $availableSeats = $totalSeats - $bookedSeats;

        if ($availableSeats > 0) {
            $route['kursi_tersedia'] = $availableSeats;
            $result[] = $route;
        }
    }

    return $result;
}


}