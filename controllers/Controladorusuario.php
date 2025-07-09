<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Escuela.php';

$usuarioModel = new Usuario();
$escuelaModel = new Escuela();

// Redirección y rutas centralizadas para feedback y vista
$baseUrl = '/untrm/gestion_usuarios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'registrar_usuario') {
    // Concatenar nombre y apellido para nombre_completo
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $nombre_completo = trim($nombre . ' ' . $apellido);
    $correo = trim($_POST['email'] ?? '');
    $contraseña = $_POST['password'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $rol_id = (int)($_POST['rol_id'] ?? 1);
    $escuela_id = !empty($_POST['escuela_id']) ? (int)$_POST['escuela_id'] : null;

    // Validaciones básicas
    if (!$nombre_completo || !$correo || !$contraseña || !$rol_id) {
        header('Location: ' . $baseUrl . '?error=Faltan campos obligatorios');
        exit;
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        header('Location: ' . $baseUrl . '?error=Email inválido');
        exit;
    }

    $errorMsg = null;
    $result = $usuarioModel->registrarUsuario($nombre_completo, $correo, $contraseña, $telefono, $direccion, $rol_id, $escuela_id, $errorMsg);
    if ($result) {
        header('Location: ' . $baseUrl . '?success=1');
        exit;
    } else {
        $msg = $errorMsg ? urlencode($errorMsg) : 'Error desconocido';
        header('Location: ' . $baseUrl . '?error=' . $msg);
        exit;
    }
}

// GET: mostrar la vista
$usuarios = $usuarioModel->listarUsuarios();
$escuelas = $escuelaModel->listarEscuelas();
// Incluir la vista centralizada correctamente
include __DIR__ . '/../views/admin/registrar_usuario.php';
