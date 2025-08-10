<?php
namespace App\Models;

use CodeIgniter\Model;

class ModelKendaraan extends Model
{
    protected $table            = 'kendaraan';
    protected $primaryKey       = 'idkendaraan';
    protected $allowedFields    = ['nopolisi_kendaraan', 'namakendaraan', 'jumlahkursi'];
    protected $useAutoIncrement = true;

    /**
     * Relasi ke kursi kendaraan
     */
    public function kursi()
    {
        return $this->hasMany('App\Models\ModelKursiKendaraan', 'idkendaraan');
    }

    /**
     * Mendapatkan daftar kursi yang tersedia
     */
    public function getAvailableSeats($idkendaraan, $excludeBooked = [])
    {
        $builder = $this->db->table('kursikendaraan')
            ->where('idkendaraan', $idkendaraan);

        if (!empty($excludeBooked)) {
            $builder->whereNotIn('idkursi', $excludeBooked);
        }

        return $builder->get()->getResultArray();
    }
}
