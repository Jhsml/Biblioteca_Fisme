<?php
session_start();

require_once 'config/Conexion.php';

// Mapeo de controladores disponibles
$controllers = [
    'auth'   => 'Controladorautenticacion',
    'books'  => 'Controladorlibro',
    'loans'  => 'Controladorprestamos',
    'home'   => 'Controladorinicio',
    // para casos por defecto
];

// Parámetros desde la URL
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Obtener el nombre de clase del controlador
$controllerClass = $controllers[$page] ?? 'Controladorlibro';

// Verifica que el archivo del controlador exista
$controllerFile = "controllers/{$controllerClass}.php";
if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo "Error: Controlador no encontrado.";
    exit;
}

// Incluir el archivo del controlador
require_once $controllerFile;

// Verifica que la clase exista y crea la instancia
if (!class_exists($controllerClass)) {
    http_response_code(500);
    echo "Error: Clase del controlador '{$controllerClass}' no definida.";
    exit;
}

$controller = new $controllerClass();

// Llama al método si existe
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    if (method_exists($controller, 'index')) {
        $controller->index(); // fallback
    } else {
        http_response_code(404);
        echo "Error: Acción '{$action}' no encontrada en {$controllerClass}.";
    }
}
?>
