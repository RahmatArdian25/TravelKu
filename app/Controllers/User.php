<?php
namespace App\Controllers;

use App\Models\ModelUser;

class User extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new ModelUser();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Daftar User',
            'user' => $this->userModel->getUser()
        ];
        
        return view('user/index', $data);
    }
    
    public function create()
    {
        $data = [
            'title' => 'Tambah User',
            'validation' => \Config\Services::validation()
        ];
        
        return view('user/create', $data);
    }
    
    public function save()
    {
        // Validation rules
        $rules = [
            'nama_user' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]|is_unique[user.email]',
            'password' => 'required|min_length[6]',
            'nohp' => 'required|max_length[20]',
            'status' => 'required|in_list[penumpang,admin,pimpinan,supir]',
            'nosim' => 'permit_empty|max_length[50]'
        ];

        $messages = [
            'nama_user' => [
                'required' => 'Nama User wajib diisi',
                'max_length' => 'Nama User maksimal 100 karakter'
            ],
            'email' => [
                'required' => 'Email wajib diisi',
                'valid_email' => 'Format email tidak valid',
                'max_length' => 'Email maksimal 100 karakter',
                'is_unique' => 'Email ini sudah terdaftar'
            ],
            'password' => [
                'required' => 'Password wajib diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'nohp' => [
                'required' => 'Nomor HP wajib diisi',
                'max_length' => 'Nomor HP maksimal 20 karakter'
            ],
            'status' => [
                'required' => 'Status wajib dipilih',
                'in_list' => 'Status yang dipilih tidak valid'
            ],
            'nosim' => [
                'max_length' => 'Nomor SIM maksimal 50 karakter'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'nama_user' => $this->request->getPost('nama_user'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nohp' => $this->request->getPost('nohp'),
            'status' => $this->request->getPost('status'),
            'nosim' => $this->request->getPost('nosim')
        ];
        
        $this->userModel->insertData($data);
        return redirect()->to('/user')->with('message', 'Data user berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $data = [
            'title' => 'Edit User',
            'user' => $this->userModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        
        return view('user/edit', $data);
    }
    
    public function update($id)
    {
        // Validation rules
        $rules = [
            'nama_user' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]|is_unique[user.email,id_user,'.$id.']',
            'nohp' => 'required|max_length[20]',
            'status' => 'required|in_list[penumpang,admin,pimpinan,supir]',
            'nosim' => 'permit_empty|max_length[50]'
        ];

        $messages = [
            'nama_user' => [
                'required' => 'Nama User wajib diisi',
                'max_length' => 'Nama User maksimal 100 karakter'
            ],
            'email' => [
                'required' => 'Email wajib diisi',
                'valid_email' => 'Format email tidak valid',
                'max_length' => 'Email maksimal 100 karakter',
                'is_unique' => 'Email ini sudah terdaftar'
            ],
            'nohp' => [
                'required' => 'Nomor HP wajib diisi',
                'max_length' => 'Nomor HP maksimal 20 karakter'
            ],
            'status' => [
                'required' => 'Status wajib dipilih',
                'in_list' => 'Status yang dipilih tidak valid'
            ],
            'nosim' => [
                'max_length' => 'Nomor SIM maksimal 50 karakter'
            ]
        ];

        // Add password validation only if password is provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $messages['password'] = [
                'min_length' => 'Password minimal 6 karakter'
            ];
        }

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_user' => $id,
            'nama_user' => $this->request->getPost('nama_user'),
            'email' => $this->request->getPost('email'),
            'nohp' => $this->request->getPost('nohp'),
            'status' => $this->request->getPost('status'),
            'nosim' => $this->request->getPost('nosim')
        ];

        // Update password only if provided
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->updatedatauser($data, $id);
        return redirect()->to('/user')->with('message', 'Data user berhasil diperbarui');
    }
    
    public function delete($id)
    {
        $this->userModel->deleteuser($id);
        return redirect()->to('/user')->with('message', 'Data user berhasil dihapus');
    }

    public function laporanPenumpang()
{
    $data = [
        'title' => 'Laporan Data Penumpang',
        'penumpang' => $this->userModel->where('status', 'penumpang')->findAll()
    ];
    
    return view('laporan/penumpang', $data);
}
public function generatePdfPenumpang()
{
    $penumpang = $this->userModel->where('status', 'penumpang')->findAll();
    
    $data = [
        'title' => 'Laporan Data Penumpang',
        'penumpang' => $penumpang
    ];
    
    $html = view('laporan/pdf_penumpang', $data);
    
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream('laporan-penumpang.pdf', ['Attachment' => true]);
}

public function laporanSupir()
{
    $data = [
        'title' => 'Laporan Data Supir',
        'supir' => $this->userModel->where('status', 'supir')->findAll()
    ];
    
    return view('laporan/supir', $data);
}

public function generatePdfSupir()
{
    $supir = $this->userModel->where('status', 'supir')->findAll();
    
    $data = [
        'title' => 'Laporan Data Supir',
        'supir' => $supir
    ];
    
    $html = view('laporan/pdf_supir', $data);
    
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream('laporan-supir.pdf', ['Attachment' => true]);
}
}