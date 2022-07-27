<?php

use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');
$router->post('/', 'HomeController@login');
$router->get('/register', 'HomeController@register');
$router->post('/register', 'HomeController@create');
$router->get('/logout', 'HomeController@logout');

$router->get('/dashboard', 'DashController@index');
$router->get('/dashboard/invite', 'DashController@invite');
$router->post('/dashboard/invite', 'DashController@createInvite');
$router->get('/dashboard/invite/delete/{id}', 'DashController@deleteInvite');

$router->get('/dashboard/wallet', 'DashController@wallet');
$router->post('/dashboard/wallet', 'DashController@createWallet');
$router->post('/dashboard/wallet/edit/{id}', 'DashController@editWallet');
$router->get('/dashboard/wallet/delete/{id}', 'DashController@deleteWallet');

$router->post('/dashboard', 'DashController@create');
$router->get('/dashboard/profile', 'DashController@profile');
$router->post('/dashboard/profile', 'DashController@editProfile');
$router->post('/dashboard/edit/{id}', 'DashController@edit');
$router->get('/dashboard/delete/{id}', 'DashController@delete');
$router->get('/dashboard/paid/{id}/{bool}', 'DashController@paid');
