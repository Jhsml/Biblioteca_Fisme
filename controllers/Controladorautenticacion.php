<?php
require_once 'models/Usuario.php';
require_once 'models/Escuela.php';

class Controladorautenticacion {
    private $userModel;
    private $escuelaModel;

    public function __construct() {
        $this->userModel = new Usuario();
        $this->escuelaModel = new Escuela();
    }
public function login() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $correo = $_POST['correo'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';

    $user = $this->userModel->authenticate($correo, $contraseña);

    header('Content-Type: application/json');

    if ($user) {
        $_SESSION['user'] = $user;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
    }
    exit;
}

public function register() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Método no permitido']);
        exit;
    }

    $data = [
        'nombre_completo' => $_POST['nombre_completo'] ?? '',
        'correo' => $_POST['correo'] ?? '',
        'contraseña' => $_POST['contraseña'] ?? '',
        'tipo_documento' => $_POST['tipo_documento'] ?? 'DNI',
        'num_documento' => $_POST['num_documento'] ?? '',
        'escuela_id' => $_POST['escuela_id'] ?? 1
    ];

    $user = $this->userModel->register($data);

    header('Content-Type: application/json');

   if ($user) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user'] = $user;
    session_write_close();
    echo json_encode(['redirect' => 'index.php']);
} else {
        http_response_code(400);
        echo json_encode(['message' => 'Usuario ya existe o error en el registro']);
    }

    exit;
}


    public function getEscuelas() {
        header('Content-Type: application/json');
        $escuelas = $this->escuelaModel->getAll();
        echo json_encode($escuelas);
        exit;
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
?>