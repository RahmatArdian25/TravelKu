<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
   public function before(RequestInterface $request, $arguments = null)
{
    $session = session();
    $user = $session->get('user');

    if (!$user || !$user['logged_in']) {
        return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu!');
    }

    $status = is_array($user) ? ($user['status'] ?? null) : null;

    if (!$status || !in_array($status, $arguments)) {
        return redirect()->to('/')->with('error', 'Akses ditolak!');
    }
}


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan kalau tidak dipakai
    }
}
