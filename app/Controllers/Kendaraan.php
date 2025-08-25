<?php
namespace App\Controllers;

use App\Models\ModelKendaraan;

class Kendaraan extends BaseController
{
    protected $kendaraanModel;

    public function __construct()
    {
        $this->kendaraanModel = new ModelKendaraan();
    }

    // ini perubahan

    public function index()
    {
        $data = [
            'title' => 'Daftar Kendaraan',
            'kendaraan' => $this->kendaraanModel->findAll()
        ];

        return view('kendaraan/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kendaraan',
            'validation' => \Config\Services::validation()
        ];

        return view('kendaraan/create', $data);
    }

    public function save()
    {
        $rules = [
            'nopolisi_kendaraan' => 'required|max_length[15]|is_unique[kendaraan.nopolisi_kendaraan]',
            'namakendaraan' => 'required|max_length[100]',
            'jumlahkursi' => 'required|numeric|greater_than_equal_to[1]'
        ];

        $messages = [
            'nopolisi_kendaraan' => [
                'required' => 'Nomor polisi harus diisi',
                'max_length' => 'Nomor polisi maksimal 15 karakter',
                'is_unique' => 'Nomor polisi sudah terdaftar'
            ],
            'namakendaraan' => [
                'required' => 'Nama kendaraan harus diisi',
                'max_length' => 'Nama kendaraan maksimal 100 karakter'
            ],
            'jumlahkursi' => [
                'required' => 'Jumlah kursi harus diisi',
                'numeric' => 'Jumlah kursi harus berupa angka',
                'greater_than_equal_to' => 'Jumlah kursi minimal 1'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nopolisi_kendaraan' => $this->request->getPost('nopolisi_kendaraan'),
            'namakendaraan' => $this->request->getPost('namakendaraan'),
            'jumlahkursi' => $this->request->getPost('jumlahkursi')
        ];

        $this->kendaraanModel->save($data);
        return redirect()->to('/kendaraan')->with('message', 'Data kendaraan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Kendaraan',
            'kendaraan' => $this->kendaraanModel->find($id),
            'validation' => \Config\Services::validation()
        ];

        return view('kendaraan/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nopolisi_kendaraan' => "required|max_length[15]|is_unique[kendaraan.nopolisi_kendaraan,idkendaraan,{$id}]",
            'namakendaraan' => 'required|max_length[100]',
            'jumlahkursi' => 'required|numeric|greater_than_equal_to[1]|jumlah_kursi_masuk_akal'
        ];

        $messages = [
            'nopolisi_kendaraan' => [
                'required' => 'Nomor polisi harus diisi',
                'max_length' => 'Nomor polisi maksimal 15 karakter',
                'is_unique' => 'Nomor polisi sudah terdaftar'
            ],
            'namakendaraan' => [
                'required' => 'Nama kendaraan harus diisi',
                'max_length' => 'Nama kendaraan maksimal 100 karakter'
            ],
            'jumlahkursi' => [
                'required' => 'Jumlah kursi harus diisi',
                'numeric' => 'Jumlah kursi harus berupa angka',
                'greater_than_equal_to' => 'Jumlah kursi minimal 1',
                'jumlah_kursi_masuk_akal' => 'Jumlah kursi tidak boleh kurang dari jumlah kursi yang sudah ada'
            ]
        ];

        // Inject ID ke $_POST agar bisa dibaca oleh validator
        $_POST['idkendaraan'] = $id;

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'idkendaraan' => $id,
            'nopolisi_kendaraan' => $this->request->getPost('nopolisi_kendaraan'),
            'namakendaraan' => $this->request->getPost('namakendaraan'),
            'jumlahkursi' => $this->request->getPost('jumlahkursi')
        ];

        $this->kendaraanModel->skipValidation(true)->save($data);

        $this->kendaraanModel->save($data);
        return redirect()->to('/kendaraan')->with('message', 'Data kendaraan berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->kendaraanModel->delete($id);
        return redirect()->to('/kendaraan')->with('message', 'Data kendaraan berhasil dihapus');
    }

    public function laporanKendaraan()
    {
        $data = [
            'title' => 'Laporan Data Kendaraan',
            'kendaraan' => $this->kendaraanModel->findAll()
        ];

        return view('laporan/kendaraan', $data);
    }

    public function generatePdfKendaraan()
    {
        $data = [
            'title' => 'Laporan Data Kendaraan',
            'kendaraan' => $this->kendaraanModel->findAll()
        ];

        $html = view('laporan/pdf_kendaraan', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan-kendaraan.pdf', ['Attachment' => true]);
    }
}
