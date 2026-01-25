<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/testdb', 'TestDb::index');

$routes->get('/', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('profile', 'ProfileController::index');
    $routes->get('profile/edit', 'ProfileController::edit');
    $routes->post('profile/update', 'ProfileController::update');
});

$routes->group('user', ['filter' => 'admin'], function ($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('delete/(:num)', 'UserController::delete/$1');
});

$routes->group('barang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'BarangController::index');
});

$routes->group('barang', ['filter' => 'admin'], function ($routes) {
    $routes->get('create', 'BarangController::create');
    $routes->post('store', 'BarangController::store');

    $routes->get('edit/(:num)', 'BarangController::edit/$1');
    $routes->post('update/(:num)', 'BarangController::update/$1');

    $routes->get('delete/(:num)', 'BarangController::delete/$1');
});

$routes->group('pinjam', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PinjamController::index');
    $routes->get('create', 'PinjamController::create');
    $routes->post('store', 'PinjamController::store');
    $routes->get('edit/(:num)', 'PinjamController::edit/$1');
    $routes->post('update/(:num)', 'PinjamController::update/$1');
    $routes->get('delete/(:num)', 'PinjamController::delete/$1');
});

