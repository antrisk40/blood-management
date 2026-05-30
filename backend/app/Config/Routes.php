<?php

use CodeIgniter\Router\RouteCollection;

$routes->group('api', function($routes) {
    // Auth Routes
    $routes->post('auth/register/hospital', 'Auth\RegisterController::hospital');
    $routes->post('auth/register/receiver', 'Auth\RegisterController::receiver');
    $routes->post('auth/login', 'Auth\LoginController::login');

    // Public Blood Route
    $routes->get('blood-samples', 'Blood\BloodController::index');
    $routes->get('available-blood', 'Blood\BloodController::index'); // alias

    // Hospital Only Routes
    $routes->group('', ['filter' => ['auth', 'hospital']], function($routes) {
        $routes->get('hospital/inventory', 'Hospital\InventoryController::index');
        $routes->post('blood-samples', 'Hospital\InventoryController::create');
        $routes->put('blood-samples/(:num)', 'Hospital\InventoryController::update/$1');
        $routes->delete('blood-samples/(:num)', 'Hospital\InventoryController::delete/$1');
        
        $routes->get('hospital/requests', 'Hospital\RequestController::index');
        $routes->put('hospital/requests/(:num)/deliver', 'Hospital\RequestController::deliver/$1');
    });

    // Receiver Only Routes
    $routes->group('', ['filter' => ['auth', 'receiver']], function($routes) {
        $routes->post('requests', 'Blood\BloodRequestController::create');
        $routes->post('request-sample', 'Blood\BloodRequestController::create'); // alias
    });

    // Handle OPTIONS requests for preflight
    $routes->options('(:any)', function() {});
});
