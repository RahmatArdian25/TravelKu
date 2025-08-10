<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


    $routes->get('/', 'Beranda::index');

        //login
        $routes->get('/login', 'Login::index');
        $routes->post('/Login/ceklogin', 'Login::ceklogin');
        $routes->get('Login/Logout', 'Login::logout'); 
        $routes->post('/Login/save', 'Login::save'); 
        $routes->get('/Home', 'Home::index');

    $routes->group('kendaraan', ['filter' => 'status:admin'], function($routes) {
    $routes->get('/', 'Kendaraan::index');
    $routes->get('create', 'Kendaraan::create');
    $routes->post('save', 'Kendaraan::save');
    $routes->get('edit/(:num)', 'Kendaraan::edit/$1');
    $routes->post('update/(:num)', 'Kendaraan::update/$1');
    $routes->delete('delete/(:num)', 'Kendaraan::delete/$1');
});

$routes->group('kendaraan', ['filter' => 'status:pimpinan'], function($routes) {
    $routes->get('/', 'Kendaraan::index');
});

$routes->group('kendaraan', ['filter' => 'status:supir'], function($routes) {
    $routes->get('/', 'Kendaraan::index');
});

$routes->group('rute', ['filter' => 'status:admin'], function($routes) {
    $routes->get('/', 'Rute::index');
    $routes->get('create', 'Rute::create');
    $routes->post('save', 'Rute::save');
    $routes->get('edit/(:num)', 'Rute::edit/$1');
    $routes->post('update/(:num)', 'Rute::update/$1');
    $routes->delete('delete/(:num)', 'Rute::delete/$1');
});

$routes->group('user', ['filter' => 'status:admin'], function($routes) {
    $routes->get('/', 'User::index');
    $routes->get('create', 'User::create');
    $routes->post('save', 'User::save');
    $routes->get('edit/(:num)', 'User::edit/$1');
    $routes->post('update/(:num)', 'User::update/$1');
    $routes->delete('delete/(:num)', 'User::delete/$1');
});

$routes->group('kursikendaraan', ['filter' => 'status:admin'], function($routes) {
    $routes->get('/', 'KursiKendaraan::index');
    $routes->get('create', 'KursiKendaraan::create');
    $routes->post('save', 'KursiKendaraan::save');
    $routes->get('edit/(:num)', 'KursiKendaraan::edit/$1');
    $routes->post('update/(:num)', 'KursiKendaraan::update/$1');
    $routes->delete('delete/(:num)', 'KursiKendaraan::delete/$1');
});

    
$routes->group('pemesanan', ['filter' => 'status:admin,penumpang'], function($routes) {
    $routes->get('/', 'Pemesanan::index');
    $routes->get('create', 'Pemesanan::create');
    $routes->post('store', 'Pemesanan::store');
    $routes->get('edit/(:num)', 'Pemesanan::edit/$1');
    $routes->put('update/(:num)', 'Pemesanan::update/$1');
    $routes->delete('delete/(:num)', 'Pemesanan::delete/$1');

    // HANYA deklarasi ini (tidak perlu ulang di luar)
    $routes->get('getKursiByKendaraan/(:num)', 'Pemesanan::getKursiByKendaraan/$1');
    $routes->get('getAvailableKursi', 'Pemesanan::getAvailableKursi');
    $routes->get('checkExistingBooking', 'Pemesanan::checkExistingBooking');
    $routes->get('checkJadwalExist', 'Pemesanan::checkJadwalExist');
    $routes->get('getAvailableRutes', 'Pemesanan::getAvailableRutes');
    
});


$routes->group('jadwalberangkat', ['filter' => 'status:admin,supir'], function($routes) {
    $routes->get('/', 'JadwalBerangkatController::index');
    $routes->get('create', 'JadwalBerangkatController::create');
    $routes->post('store', 'JadwalBerangkatController::store');
    $routes->get('edit/(:num)', 'JadwalBerangkatController::edit/$1');
    $routes->post('update/(:num)', 'JadwalBerangkatController::update/$1');
    $routes->get('delete/(:num)', 'JadwalBerangkatController::delete/$1');
});

$routes->group('laporan', ['filter' => 'status:admin,pimpinan'], function($routes) {

    // Tambahkan route untuk laporan penumpang
    $routes->get('penumpang', 'User::laporanPenumpang');
    $routes->get('penumpang/pdf', 'User::generatePdfPenumpang');
    $routes->get('supir', 'User::laporanSupir');
    $routes->get('supir/pdf', 'User::generatePdfSupir');
    $routes->get('kendaraan', 'Kendaraan::laporanKendaraan');
    $routes->get('kendaraan/pdf', 'Kendaraan::generatePdfKendaraan');
    $routes->get('rute', 'Rute::laporanRute');
    $routes->get('rute/pdf', 'Rute::generatePdfRute');
       $routes->get('jadwal', 'JadwalBerangkatController::laporanJadwal');
    $routes->get('jadwal/pdf', 'JadwalBerangkatController::generatePdfJadwal');
     $routes->get('pemesanan', 'Pemesanan::laporanPemesanan');
    $routes->get('pemesanan/pdf', 'Pemesanan::generatePdfPemesanan');
    $routes->get('pemesanan-tahunan', 'Pemesanan::laporanPemesananTahunan');
    $routes->get('pemesanan-tahunan/pdf', 'Pemesanan::generatePdfPemesananTahunan');
  


});

$routes->group('laporan', ['filter' => 'status:admin,penumpang'], function($routes) {
         $routes->get('tiket/(:num)', 'Pemesanan::cetakTiket/$1');
    $routes->get('tiket/pdf/(:num)', 'Pemesanan::generatePdfTiket/$1');
});

$routes->group('laporan', ['filter' => 'status:admin,supir'], function($routes) {
         $routes->get('suratjalan/(:num)', 'JadwalBerangkatController::generateSuratJalan/$1');
    $routes->get('suratjalan/pdf/(:num)', 'JadwalBerangkatController::generatePdfSuratJalan/$1');
});

