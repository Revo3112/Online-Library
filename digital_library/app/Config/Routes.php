<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Authentication::login'); // Redirect the base URL to the login page
$routes->get('/login', 'Authentication::login');
$routes->get('/register', 'Authentication::register');
$routes->post('universal/books/update/(:segment)', 'User::updateBook/$1');

$routes->group('admin', function ($routes) {
    $routes->get('users', 'User::users');
    $routes->delete('delete/(:num)', 'User::deleteUser/$1');
    $routes->get('searchuser', 'User::searchUsers');
});


$routes->group('universal', function ($routes) {
    $routes->get('home', 'User::dashboard');
    // books section
    $routes->get('books', 'User::books');
    $routes->get('books/(:num)', 'User::books/$1');
    $routes->get('books/edit/(:num)', 'User::editBook/$1');
    $routes->match(['post', 'put'], 'books/update/(:num)', 'User::updateBook/$1');
    $routes->post('books/delete/(:num)', 'User::deleteBook/$1');
    $routes->get('books/add', 'User::addBook');
    $routes->post('books/add', 'User::createBook');
    $routes->get('books/view/(:num)', 'User::viewBook/$1');
    $routes->get('books/search', 'User::search');
    $routes->get('books/search2/(:any)', 'User::search2/$1');
    // category section
    $routes->get('kategori', 'User::kategori');
    $routes->get('kategori/new', 'User::addKategori');
    $routes->post('kategori/create', 'User::createKategori');
    $routes->get('kategori/(:any)/edit', 'User::editKategori/$1');
    $routes->post('kategori/(:any)/update', 'User::updateKategori/$1');
    $routes->match(['post', 'delete'], 'kategori/(:any)/delete', 'User::deleteKategori/$1');
    $routes->get('kategori/(:any)', 'User::booksByCategory/$1');

    // dashboard section
    $routes->get('books/export', 'User::exportBooks');
    $routes->get('books/export_excel', 'User::exportBooksToExcel');
    $routes->get('serveFile/(:any)', 'User::serveFile/$1');
});

$routes->group('auth', function ($routes) {
    $routes->post('login', 'Authentication::login_controller');
    $routes->post('register', 'Authentication::register_controller');
    $routes->get('logout', 'Authentication::logout');
    // Password reset routes
    $routes->get('passwordreset', 'Authentication::showResetForm');
    $routes->post('passwordreset/resetpassword', 'Authentication::resetPassword');
});
