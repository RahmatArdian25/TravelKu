<?php
namespace App\Models;

use CodeIgniter\Model;

class ModelDetailPemesanan extends Model
{
    protected $table = 'detailpemesanan';
    protected $primaryKey = 'detailpemesananid';
    protected $allowedFields = ['pemesananid', 'kursikendaraanid', 'namapenumpang', 'jeniskelamin'];
    protected $useAutoIncrement = true;
    
    protected $validationRules = [
        'pemesananid' => 'required|numeric',
        'kursikendaraanid' => 'required|numeric',
        'namapenumpang' => 'required|max_length[100]',
        'jeniskelamin' => 'required|in_list[Laki-laki,Perempuan]'
    ];
    
    protected $validationMessages = [
        'pemesananid' => ['required' => 'ID Pemesanan harus diisi'],
        'kursikendaraanid' => ['required' => 'Kursi kendaraan harus dipilih'],
        'namapenumpang' => [
            'required' => 'Nama penumpang harus diisi',
            'max_length' => 'Nama penumpang maksimal 100 karakter'
        ],
        'jeniskelamin' => ['required' => 'Jenis kelamin harus dipilih']
    ];
    // In ModelDetailPemesanan
public function getDetailWithKursi($pemesananId)
{
    return $this->select('detailpemesanan.*, kursikendaraan.nomorkursi')
               ->join('kursikendaraan', 'kursikendaraan.idkursi = detailpemesanan.kursikendaraanid', 'left')
               ->where('pemesananid', $pemesananId)
               ->findAll();
}
}

