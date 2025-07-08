<?php
session_start();
require_once 'config/Conexion.php';
require_once 'controllers/Controladorautenticacion.php';
require_once 'controllers/Controladorinicio.php';
require_once 'controllers/Controladorprestamos.php';
require_once 'controllers/Controladorlibro.php';

// Simple routing
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

switch ($page) {
    case 'auth':
        $controller = new Controladorautenticacion();
        break;
    case 'books':
        $controller = new Controladorinicio();
        break;
    case 'loans':
        $controller = new Controladorprestamos();
        break;
    default:
        $controller = new Controladorlibro();
        break;
}

// Call the appropriate method
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    $controller->index();
}
?>