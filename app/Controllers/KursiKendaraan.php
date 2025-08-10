<?php
namespace App\Controllers;

use App\Models\ModelKendaraan;
use App\Models\ModelKursiKendaraan;

class KursiKendaraan extends BaseController
{
    protected $kursiKendaraanModel;
    protected $kendaraanModel;
    
   public function __construct()
{
    $this->kursiKendaraanModel = new ModelKursiKendaraan();
    $this->kendaraanModel = new ModelKendaraan();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Daftar Kursi Kendaraan',
            'kursiKendaraan' => $this->kursiKendaraanModel->getKursiKendaraanWithNamaKendaraan()
        ];
        
        return view('kursikendaraan/index', $data);
    }
    
    public function create()
    {
        $data = [
            'title' => 'Tambah Kursi Kendaraan',
            'validation' => \Config\Services::validation(),
            'kendaraan' => $this->kendaraanModel->findAll()
        ];
        
        return view('kursikendaraan/create', $data);
    }
    
    public function save()
    {
        $rules = [
    'idkendaraan' => 'required|is_not_unique[kendaraan.idkendaraan]',
    'nomorkursi' => 'required|numeric|greater_than[0]|is_seat_unique[idkendaraan,jadwalberangkat]',
    'jadwalberangkat' => 'required|in_list[pagi,siang,malam]'
];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'idkendaraan' => $this->request->getPost('idkendaraan'),
            'nomorkursi' => $this->request->getPost('nomorkursi'),
            'jadwalberangkat' => $this->request->getPost('jadwalberangkat')
        ];

        $this->kursiKendaraanModel->save($data);
        return redirect()->to('/kursikendaraan')->with('success', 'Data kursi kendaraan berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Kursi Kendaraan',
            'kursiKendaraan' => $this->kursiKendaraanModel->find($id),
            'kendaraan' => $this->kendaraanModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        
        return view('kursikendaraan/edit', $data);
    }
    
    public function update($id)
    {
    $rules = [
    'idkendaraan' => 'required|is_not_unique[kendaraan.idkendaraan]',
    'nomorkursi' => 'required|numeric|greater_than[0]|is_seat_unique[idkendaraan,jadwalberangkat,idkursi]',
    'jadwalberangkat' => 'required|in_list[pagi,siang,malam]'
];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'idkursi' => $id,
            'idkendaraan' => $this->request->getPost('idkendaraan'),
            'nomorkursi' => $this->request->getPost('nomorkursi'),
            'jadwalberangkat' => $this->request->getPost('jadwalberangkat')
        ];

        $this->kursiKendaraanModel->save($data);
        return redirect()->to('/kursikendaraan')->with('success', 'Data kursi kendaraan berhasil diperbarui');
    }
    
    public function delete($id)
    {
        $this->kursiKendaraanModel->delete($id);
        return redirect()->to('/kursikendaraan')->with('message', 'Data kursi kendaraan berhasil dihapus');
    }
}