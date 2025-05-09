<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'DashboardController::index');
$routes->get('/pages/parsing', 'ParsingController::index');
$routes->get('/pages/bulk', 'BulkController::index');
$routes->get('/parsing/create', 'ParsingController::create');
$routes->post('/pages/parsing', 'ParsingController::save');
$routes->get('/pages/load', 'ParsingController::load');
$routes->get('/pages/trial', 'TrialController::index');
$routes->post('/pages/trial', 'TrialController::save');
$routes->get('/pages/product', 'ProductController::index');
$routes->post('/pages/bulk', 'BulkController::about');
$routes->post('/pages/bulk', 'BulkController::about');
$routes->get('parsing/delete/(:segment)/(:segment)', 'ParsingController::delete/$1/$2');
// $routes->get('/pages/maha', 'MahaController::index');
$routes->get('peformance', 'PeformanceMatrixController::index');
$routes->post('peformance', 'PeformanceMatrixController::save');
$routes->get('blur', 'RealtimeController::index');
