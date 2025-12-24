<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route yang sudah ada tetap seperti biasa
$routes->get('/', 'Home::index');

// Auth routes - Perbaikan untuk mengatasi 404
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout');

// Alternative auth routes jika ingin menggunakan prefix 'auth'
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('attemptLogin', 'Auth::attemptLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('attemptRegister', 'Auth::attemptRegister');
    $routes->get('logout', 'Auth::logout');
    $routes->get('createAdmin', 'Auth::createAdmin');
    $routes->post('createAdmin', 'Auth::createAdmin');
});

// Event routes
$routes->group('events', function ($routes) {
    $routes->get('/', 'Events::index');
    $routes->get('create', 'Events::create');
    $routes->post('store', 'Events::store');
    $routes->get('edit/(:num)', 'Events::edit/$1');
    $routes->post('update/(:num)', 'Events::update/$1');
    $routes->get('delete/(:num)', 'Events::delete/$1');
    $routes->get('view/(:num)', 'Events::view/$1');
    $routes->get('participants/(:num)', 'Events::participants/$1');
    $routes->get('participant_index', 'Events::participantIndex');
    $routes->get('register/(:num)', 'Events::register/$1');
    $routes->get('register', 'Events::register');

    // Certificate routes
    $routes->get('certificate', 'Events::certificate');
    $routes->get('certificate/(:num)', 'Events::certificate/$1');
    $routes->get('download-certificate/(:num)', 'Events::downloadCertificate/$1');
    $routes->post('preview-certificate', 'Events::previewCertificate');
});

// Admin routes
$routes->group('admin', function ($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('users', 'Admin::users');
    $routes->get('events', 'Admin::events');
});

// Participant routes
$routes->group('participants', function ($routes) {
    $routes->post('register', 'Participants::register');
    $routes->get('unregister/(:num)', 'Participants::unregister/$1');
});

// Dashboard route
$routes->get('dashboard', 'Dashboard::index');

// Reports Routes
$routes->group('reports', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Reports::index');
    $routes->get('dashboard', 'Reports::index');
    $routes->get('events', 'Reports::events');
    $routes->get('participants', 'Reports::participants');
    $routes->get('certificates', 'Reports::certificates');

    // Export routes
    $routes->get('export/events/excel', 'Reports::exportEventsExcel');
    $routes->get('export/events/pdf', 'Reports::exportEventsPdf');
    $routes->get('export/participants/excel', 'Reports::exportParticipantsExcel');
    $routes->get('export/participants/pdf', 'Reports::exportParticipantsPdf');
    $routes->get('export/certificates/excel', 'Reports::exportCertificatesExcel');
    $routes->get('export/certificates/pdf', 'Reports::exportCertificatesPdf');
});

// Certificate Routes
$routes->group('certificates', ['filter' => 'auth'], function ($routes) {
    $routes->get('generate/(:num)', 'Certificates::generate/$1');
});

// Profile Routes
$routes->group('profile', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Profile::index');
    $routes->post('update', 'Profile::update');
    $routes->post('change-password', 'Profile::changePassword');
});
