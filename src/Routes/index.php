<?php

use HRMS\Core\Router;

$router = new Router();

// Define Routes
$router->add('GET', '/', ['UserController', 'login']);
$router->add('GET', 'login', ['UserController', 'login']);
$router->add('GET', 'logout', ['UserController', 'logout']);
$router->add('GET', 'register', ['UserController', 'register']);
$router->add('POST', 'login', ['UserController', 'loginUser']);
$router->add('POST', 'register', ['UserController', 'registerUser']);
$router->add('GET', 'addRecord', ['HealthRecordController', 'addRecord']);
$router->add('GET', 'editRecord', ['HealthRecordController', 'editRecord']);
$router->add('POST', 'submitHealthRecord', ['HealthRecordController', 'submitHealthRecord']);
$router->add('GET', 'dashboard', ['HealthRecordController', 'getUserWiseHealthRecords']);
$router->add('GET', 'adminDashboard', ['AdminController', 'getAdminDashboardData']);
$router->add('GET', 'getLog', ['AdminController', 'getLog']);
$router->add('GET', 'viewPatientRecords', ['AdminController', 'getAllPatientRecords']);
$router->add('GET', 'viewPackages', ['AdminController', 'getPackages']);
$router->add('GET', 'addPackage', ['PackageController', 'addPackage']);
$router->add('POST', 'savePackage', ['PackageController', 'savePackage']);
$router->add('GET', 'viewMyPackage', ['PackageController', 'getUserPackage']);
$router->add('POST', 'upgradeMyPackage', ['PackageController', 'upgradePackage']);
$router->add('GET', 'showUpgradeForm', ['PackageController', 'showUpgradeForm']);
$router->add('GET', 'getNotifications', ['NotificationController', 'getNotifications']);
$router->add('GET', 'sendNotification', ['NotificationController', 'sendNotification']);

// Dispatch Request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);