<?php
session_start();
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/helpers.php';
require ROOT . 'app/helpers.php';
require ROOT . 'app/routes.php';

core\Environment::load(ROOT);

$router->run($router->routes);
