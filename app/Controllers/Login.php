<?php

namespace App\Controllers;

use App\Models\ModelUser;

class Login extends BaseController
{
    public function index()
    {
        // Jika sudah login, redirect ke Home
        if (session()->get('user') && session()->get('user')['logged_in']) {
            return redirect()->to('/Home');
        }

        // Jika belum login, tampilkan halaman login
        return view('login/v_login');
    }
    
    public function ceklogin()
    {
        $session = session();
        $model = new ModelUser();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        if (empty($email) || empty($password)) {
            $session->setFlashdata('msg', 'Email dan password harus diisi');
            return redirect()->to('/login');
        }
        
        $cek = $model->cek_login($email);
        
        if($cek){
            $verify_pass = password_verify($password, $cek['password']);
            
            if($verify_pass){
                // Simpan dalam format yang konsisten
                $userData = [
                    'id'       => $cek['id_user'],
                    'nama'     => $cek['nama_user'],
                    'email'    => $cek['email'],
                    'status'   => strtolower($cek['status']), // Pastikan lowercase
                    'logged_in' => true
                ];
                
                // Simpan sebagai array di key 'user'
                $session->set('user', $userData);
                
                return redirect()->to('/Home');
            } else {
                $session->setFlashdata('msg', 'Password salah');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Email tidak ditemukan');
            return redirect()->to('/login');
        }
    }

    public function save()
    {
        $session = session();
        $model = new ModelUser();
        
        // Validation rules
        $rules = [
            'nama_user' => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|is_unique[user.email]|max_length[100]',
            'password'  => 'required|min_length[6]',
            'nohp'      => 'required|max_length[20]'
        ];

        $messages = [
            'nama_user' => [
                'required' => 'Nama lengkap wajib diisi',
                'min_length' => 'Nama minimal 3 karakter',
                'max_length' => 'Nama maksimal 100 karakter'
            ],
            'email' => [
                'required' => 'Email wajib diisi',
                'valid_email' => 'Format email tidak valid',
                'is_unique' => 'Email ini sudah terdaftar',
                'max_length' => 'Email maksimal 100 karakter'
            ],
            'password' => [
                'required' => 'Password wajib diisi',
                'min_length' => 'Password minimal 6 karakter'
            ],
            'nohp' => [
                'required' => 'Nomor HP wajib diisi',
                'max_length' => 'Nomor HP maksimal 20 karakter'
            ]
        ];
        
        if (!$this->validate($rules, $messages)) {
            $errors = $this->validator->getErrors();
            $session->setFlashdata('errors', $errors);
            $session->setFlashdata('input', $this->request->getPost());
            return redirect()->to('/login')->withInput();
        }
    
        $data = [
            'nama_user' => $this->request->getPost('nama_user'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'nohp'      => $this->request->getPost('nohp'),
            'status'    => 'penumpang',
            'nosim'     => null
        ];
    
        if ($model->save($data)) {
            $session->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
        } else {
            $session->setFlashdata('msg', 'Registrasi gagal, silakan coba lagi.');
        }
    
        return redirect()->to('/login');
    }
    
    public function logout()
    {
        $session = session();
        // Hapus spesifik key user
        $session->remove('user');
        $session->destroy();
        return redirect()->to('/login');
    }
}