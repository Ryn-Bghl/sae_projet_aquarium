<?php
session_start();
require_once 'config.php';
require_once 'app/model/model.php';

if (!empty($_GET['route'])) {
    $route = $_GET['route'];
}
switch ($route) {
    case 'error':
        require_once 'app/controller/404.controller.php';
        generate404Page();
        break;
    default:
        header('Location: ?route=error');
        exit();
}
