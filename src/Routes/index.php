<?php

use HRMS\Router;

$router = new Router();

// Define Routes
$router->add('GET', '/', ['UserController', 'login']);
$router->add('GET', 'login', ['UserController', 'login']);
$router->add('GET', 'register', ['UserController', 'register']);
$router->add('POST', 'login', ['UserController', 'loginUser']);
$router->add('POST', 'register', ['UserController', 'registerUser']);
$router->add('GET', 'addRecord', ['HealthcareController', 'addRecord']);

// Dispatch Request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);