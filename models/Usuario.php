<?php
require_once 'config/Conexion.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = new Conexion();
    }

    public function authenticate($correo, $contraseña) {
        $conn = $this->db->getConexion();
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($usuario = $result->fetch_assoc()) {
            if (password_verify($contraseña, $usuario['contraseña'])) {
                unset($usuario['contraseña']); // Opcional: no guardar la contraseña en sesión
                return $usuario;
            }
        }

        return null;
    }

    public function register($data) {
        $conn = $this->db->getConexion();

        // Verificar si ya existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $data['correo']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return null; // Ya existe
        }
        $stmt->close();

        // Hash de contraseña
        $hash = password_hash($data['contraseña'], PASSWORD_DEFAULT);
        $rol_id = 1; // Usuario por defecto

        $stmt = $conn->prepare("
            INSERT INTO usuarios 
            (nombre_completo, correo, contraseña, tipo_documento, num_documento, rol_id, escuela_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssssii",
            $data['nombre_completo'],
            $data['correo'],
            $hash,
            $data['tipo_documento'],
            $data['num_documento'],
            $rol_id,
            $data['escuela_id']
        );

        if (!$stmt->execute()) {
            return null;
        }

        $id = $stmt->insert_id;

        // Obtener usuario completo (incluye rol_id)
        $stmt = $conn->prepare("SELECT id, nombre_completo, correo, rol_id FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getAll() {
        $conn = $this->db->getConexion();
        $usuarios = [];

        $sql = "SELECT * FROM usuarios";
        $result = $conn->query($sql);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }

        return $usuarios;
    }
}
?>
