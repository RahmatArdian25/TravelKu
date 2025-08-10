<?php

namespace App\Controllers;

class Beranda extends BaseController
{
    public function index()
    {
        // Contoh data, kalau mau bisa diisi dinamis
        $data = [
            'title' => 'Beranda Masjid Syuhada',
            'berita' => [
                [
                    'judul' => 'Persiapan Idul Adha',
                    'isi' => 'Masjid Syuhada sedang mempersiapkan kegiatan Idul Adha tahun ini...',
                ],
                [
                    'judul' => 'Pembangunan Tempat Wudhu',
                    'isi' => 'Alhamdulillah, pembangunan tempat wudhu telah selesai 90%...',
                ]
            ]
        ];

        return view('beranda', $data);
    }
}
