<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/testdb', 'TestDb::index');

$routes->get('/', 'AuthController::login');
$routes->post('/barang', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/barang/create', 'BarangController::create');
    $routes->post('/barang/store', 'BarangController::store');

    $routes->get('/barang/edit/(:num)', 'BarangController::edit/$1');
    $routes->post('/barang/update/(:num)', 'BarangController::update/$1');

    $routes->get('/barang/delete/(:num)', 'BarangController::delete/$1');
});