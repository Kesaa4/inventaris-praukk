<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/testdb', 'TestDb::index');

$routes->get('/dashboard', 'BarangController::index');
$routes->get('/dashboard/create', 'BarangController::create');
$routes->post('/dashboard/store', 'BarangController::store');

$routes->get('/dashboard/edit/(:num)', 'BarangController::edit/$1');
$routes->post('/dashboard/update/(:num)', 'BarangController::update/$1');

$routes->get('/dashboard/delete/(:num)', 'BarangController::delete/$1');

