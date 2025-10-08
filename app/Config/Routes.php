<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
//
$routes->post('api/login', 'C_eleve::login');
$routes->post('api/logout', 'C_eleve::logout');
$routes->post('api/choix', 'C_choix::saveChoices');
$routes->get('api/choix/(:num)', 'C_choix::getChoicesByEleve/$1');

// $routes->get('api/users', 'C_eleve::listUser');