<?php
namespace App\Controllers;

use App\Models\ModelRute;
use App\Models\ModelKendaraan;

class Rute extends BaseController
{
    protected $ruteModel;
    protected $kendaraanModel;
    
    public function __construct()
    {
        $this->ruteModel = new ModelRute();
        $this->kendaraanModel = new ModelKendaraan();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Daftar Rute',
            'rute' => $this->ruteModel->getWithKendaraan() 
        ];
        
        return view('rute/index', $data);
    }
    
    public function create()
    {
        $data = [
            'title' => 'Tambah Rute',
            'kendaraans' => $this->kendaraanModel->findAll(), // Untuk dropdown kendaraan
            'validation' => \Config\Services::validation()
        ];
        
        return view('rute/create', $data);
    }
    
    public function save()
    {
        $rules = $this->ruteModel->getValidationRules();
        $rules['idkendaraan'] = 'required|numeric'; // Menambahkan validasi untuk idkendaraan
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'asal' => $this->request->getPost('asal'),
            'tujuan' => $this->request->getPost('tujuan'),
            'harga' => $this->request->getPost('harga'),
            'jadwalberangkat' => $this->request->getPost('jadwalberangkat'),
            'idkendaraan' => $this->request->getPost('idkendaraan')
        ];
        
        $this->ruteModel->save($data);
        return redirect()->to('/rute')->with('message', 'Data rute berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Rute',
            'rute' => $this->ruteModel->find($id),
            'kendaraans' => $this->kendaraanModel->findAll(), // Untuk dropdown kendaraan
            'validation' => \Config\Services::validation()
        ];
        
        return view('rute/edit', $data);
    }
    
    public function update($id)
    {
        $rules = $this->ruteModel->getValidationRules();
        $rules['idkendaraan'] = 'required|numeric'; // Menambahkan validasi untuk idkendaraan
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'idrute' => $id,
            'asal' => $this->request->getPost('asal'),
            'tujuan' => $this->request->getPost('tujuan'),
            'harga' => $this->request->getPost('harga'),
            'jadwalberangkat' => $this->request->getPost('jadwalberangkat'),
            'idkendaraan' => $this->request->getPost('idkendaraan')
        ];

        $this->ruteModel->save($data);
        return redirect()->to('/rute')->with('message', 'Data rute berhasil diperbarui');
    }
    
    // Method delete() dan lainnya tetap sama...

    
    public function delete($id)
    {
        $this->ruteModel->delete($id);
        return redirect()->to('/rute')->with('message', 'Data rute berhasil dihapus');
    }

    // App/Controllers/Rute.php

public function laporanRute()
{
    $data = [
        'title' => 'Laporan Data Rute',
        'rute' => $this->ruteModel->findAll()
    ];
    
    return view('laporan/rute', $data);
}

public function generatePdfRute()
{
    $rute = $this->ruteModel->findAll();
    
    $data = [
        'title' => 'Laporan Data Rute',
        'rute' => $rute
    ];
    
    $html = view('laporan/pdf_rute', $data);
    
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream('laporan-rute.pdf', ['Attachment' => true]);
}
}