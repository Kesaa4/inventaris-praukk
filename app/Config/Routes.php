<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ❌ HAPUS TEST ROUTES UNTUK PRODUCTION
// $routes->get('/testdb', 'TestDb::index');
// $routes->get('/test-navbar', function() {
//     return view('test_navbar');
// });
// $routes->get('/test-barang', function() {
//     return view('barang/index', [
//         'barang' => [],
//         'kategori' => [],
//         'keyword' => '',
//         'catFilter' => '',
//         'pager' => service('pager')
//     ]);
// });

// Authentication Routes
$routes->get('/', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');

// Activity Log Routes
$routes->get('/activity-log', 'ActivityLogController::index', ['filter' => 'auth']);
$routes->get('/activity-log/export-excel', 'ActivityLogController::exportExcel', ['filter' => 'admin']);

// Dashboard Route
$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('/dashboard/cetak-laporan', 'DashboardController::cetakLaporan', ['filter' => 'auth']);

// Profile Routes
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('profile', 'ProfileController::index');
    $routes->get('profile/edit', 'ProfileController::edit');
    $routes->post('profile/update', 'ProfileController::update');
    $routes->get('profile/deleteFoto', 'ProfileController::deleteFoto');
});

// User Management Routes for Admin
$routes->group('user', ['filter' => 'admin'], function ($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('create', 'UserController::create');
    $routes->post('store', 'UserController::store');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('delete/(:num)', 'UserController::delete/$1');
    $routes->get('resetPassword/(:num)', 'UserController::resetPassword/$1');
});

// Barang Routes
$routes->group('barang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'BarangController::index');
    $routes->get('history/(:num)', 'BarangController::history/$1');
    $routes->get('export-excel', 'BarangController::exportExcel');
});

// Barang Management Routes for Admin
$routes->group('barang', ['filter' => 'admin'], function ($routes) {
    $routes->get('create', 'BarangController::create');
    $routes->post('store', 'BarangController::store');
    $routes->get('edit/(:num)', 'BarangController::edit/$1');
    $routes->post('update/(:num)', 'BarangController::update/$1');
    $routes->get('delete/(:num)', 'BarangController::delete/$1');
    $routes->get('delete-foto/(:num)', 'BarangController::deleteFoto/$1');
    $routes->get('trash', 'BarangController::trash');
});

// Pinjam Routes
$routes->group('pinjam', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PinjamController::index');
    $routes->get('create', 'PinjamController::create');
    $routes->post('store', 'PinjamController::store');
    $routes->get('edit/(:num)', 'PinjamController::edit/$1');
    $routes->post('update/(:num)', 'PinjamController::update/$1');
    $routes->get('delete/(:num)', 'PinjamController::delete/$1');
    
    $routes->post('return/(:num)', 'PinjamController::requestReturn/$1');
    $routes->get('return-check/(:num)', 'PinjamController::returnCheck/$1');
    $routes->post('return-update/(:num)', 'PinjamController::returnUpdate/$1');

    $routes->get('cetak-detail/(:num)', 'PinjamController::cetakDetail/$1');
    $routes->get('export-excel', 'PinjamController::exportExcel');

    $routes->get('trash', 'PinjamController::trash');
    $routes->get('restore/(:num)', 'PinjamController::restore/$1');
    $routes->get('force-delete/(:num)', 'PinjamController::forceDelete/$1');
});

// Kategori Routes (untuk semua user yang login)
$routes->group('kategori', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'KategoriController::index');
    $routes->get('export', 'KategoriController::export');
    $routes->get('(:num)', 'KategoriController::show/$1');
});

// Kategori Management Routes for Admin
$routes->group('kategori', ['filter' => 'admin'], function ($routes) {
    $routes->get('create', 'KategoriController::create');
    $routes->post('store', 'KategoriController::store');
    $routes->get('edit/(:num)', 'KategoriController::edit/$1');
    $routes->post('update/(:num)', 'KategoriController::update/$1');
    $routes->post('delete/(:num)', 'KategoriController::delete/$1');
});

