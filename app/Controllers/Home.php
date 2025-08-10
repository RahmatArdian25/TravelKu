<?php

namespace App\Controllers;


use App\Models\ModelUser;

class Home extends BaseController
{
   public function index()
{
    $user = session()->get('user');

    // Jika belum login, redirect ke login
    if (!$user || !$user['logged_in']) {
        return redirect()->to('/login');
    }

    $status = $user['status'];

    switch ($status) {
        case 'admin':
            return view('dashboard/admin');
        case 'pimpinan':
            return view('dashboard/pimpinan');
        case 'supir':
            return view('dashboard/supir');
        case 'penumpang':
            return view('dashboard/penumpang');
        default:
            return redirect()->to('/login');
    }
}

}