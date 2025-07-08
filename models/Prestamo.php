<?php
require_once 'config/Conexion.php';

class Prestamo {
    private $db;

    public function __construct() {
        $this->db = new Conexion();
    }

    public function createReservation($usuario_id, $libro_id) {
        $conn = $this->db->getConexion();

        $sql = "SELECT id FROM ejemplares WHERE libro_id = ? AND estado = 'disponible' LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $libro_id);
        $stmt->execute();
        $stmt->bind_result($ejemplar_id);

        if ($stmt->fetch()) {
            $stmt->close();

            $fecha_prestamo = date('Y-m-d');
            $fecha_vencimiento = date('Y-m-d', strtotime('+14 days'));

            $insert_sql = "INSERT INTO prestamos (usuario_id, ejemplar_id, fecha_prestamo, fecha_vencimiento, estado, observaciones) VALUES (?, ?, ?, ?, 'activo', 'Reserva')";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param('iiss', $usuario_id, $ejemplar_id, $fecha_prestamo, $fecha_vencimiento);
            $insert_stmt->execute();
            $insert_stmt->close();

            // Marcar ejemplar como reservado
            $update_sql = "UPDATE ejemplares SET estado = 'prestado' WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param('i', $ejemplar_id);
            $update_stmt->execute();
            $update_stmt->close();

            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function getAllLoans() {
        $conn = $this->db->getConexion();

        $sql = "SELECT p.*, u.nombre_completo, u.correo, l.titulo, l.isbn
                FROM prestamos p
                JOIN usuarios u ON p.usuario_id = u.id
                JOIN ejemplares e ON p.ejemplar_id = e.id
                JOIN libros l ON e.libro_id = l.id
                ORDER BY p.fecha_prestamo DESC";

        $result = $conn->query($sql);
        $prestamos = [];

        while ($row = $result->fetch_assoc()) {
            $prestamos[] = [
                'id' => $row['id'],
                'usuarios' => [
                    'id' => $row['usuario_id'],
                    'nombre_completo' => $row['nombre_completo'],
                    'correo' => $row['correo']
                ],
                'libros' => [
                    'titulo' => $row['titulo'],
                    'isbn' => $row['isbn']
                ],
                'fecha_prestamo' => $row['fecha_prestamo'],
                'fecha_vencimiento' => $row['fecha_vencimiento'],
                'fecha_devolucion' => $row['fecha_devolucion'],
                'estado' => $row['estado'],
                'observaciones' => $row['observaciones']
            ];
        }

        return $prestamos;
    }

    public function getPendingReservations() {
        $conn = $this->db->getConexion();

        $sql = "SELECT p.*, u.nombre_completo, u.correo, l.titulo, l.isbn
                FROM prestamos p
                JOIN usuarios u ON p.usuario_id = u.id
                JOIN ejemplares e ON p.ejemplar_id = e.id
                JOIN libros l ON e.libro_id = l.id
                WHERE p.estado = 'activo' AND p.observaciones = 'Reserva'
                ORDER BY p.fecha_prestamo DESC";

        $result = $conn->query($sql);
        $prestamos = [];

        while ($row = $result->fetch_assoc()) {
            $prestamos[] = [
                'id' => $row['id'],
                'usuarios' => [
                    'id' => $row['usuario_id'],
                    'nombre_completo' => $row['nombre_completo'],
                    'correo' => $row['correo']
                ],
                'libros' => [
                    'titulo' => $row['titulo'],
                    'isbn' => $row['isbn']
                ],
                'fecha_prestamo' => $row['fecha_prestamo']
            ];
        }

        return $prestamos;
    }

    public function confirmLoan($loan_id) {
        $conn = $this->db->getConexion();

        $sql = "UPDATE prestamos SET observaciones = 'Confirmado' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $loan_id);

        return $stmt->execute();
    }
}
?>
