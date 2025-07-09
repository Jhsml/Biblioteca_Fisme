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

    $ejemplar_id = null; // IMPORTANTE: declarar antes
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

        // Marcar ejemplar como prestado
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
        WHERE p.estado != 'devuelto' 
          AND p.observaciones != 'Reserva'
          AND p.observaciones != 'Rechazado'
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
  public function rejectLoan($loan_id) {
    $conn = $this->db->getConexion();

    // 1. Obtener el ejemplar relacionado
    $stmt = $conn->prepare("SELECT ejemplar_id FROM prestamos WHERE id = ? AND estado = 'activo' AND observaciones = 'Reserva'");
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ejemplar_id = $row['ejemplar_id'];

        // 2. Cambiar solo la observación del préstamo a 'Rechazado'
        $stmt = $conn->prepare("UPDATE prestamos SET observaciones = 'Rechazado' WHERE id = ?");
        $stmt->bind_param("i", $loan_id);
        $updateLoan = $stmt->execute();

        // 3. Marcar el ejemplar como disponible nuevamente
        if ($updateLoan) {
            $stmt = $conn->prepare("UPDATE ejemplares SET estado = 'disponible' WHERE id = ?");
            $stmt->bind_param("i", $ejemplar_id);
            return $stmt->execute();
        }
    }

    return false;
}
public function marcarComoDevuelto($loan_id) {
    $conn = $this->db->getConexion();

    $stmt = $conn->prepare("SELECT ejemplar_id FROM prestamos WHERE id = ?");
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        error_log("Fallo SELECT: " . $conn->error);
        return false;
    }

    if ($row = $result->fetch_assoc()) {
        $ejemplar_id = $row['ejemplar_id'];

        $stmt = $conn->prepare("UPDATE prestamos SET estado = 'devuelto', fecha_devolucion = NOW(), observaciones = 'Confirmado' WHERE id = ?");
        $stmt->bind_param("i", $loan_id);
        if (!$stmt->execute()) {
            error_log("Fallo UPDATE préstamo: " . $stmt->error);
            return false;
        }

        $stmt = $conn->prepare("UPDATE ejemplares SET estado = 'disponible' WHERE id = ?");
        $stmt->bind_param("i", $ejemplar_id);
        if (!$stmt->execute()) {
            error_log("Fallo UPDATE ejemplar: " . $stmt->error);
        }

        return true;
    }

    error_log("No se encontró préstamo con id $loan_id");
    return false;
}

public function markAsNotReturned($loan_id) {
    $conn = $this->db->getConexion();

    // 1. Obtener el ejemplar relacionado
    $stmt = $conn->prepare("SELECT ejemplar_id FROM prestamos WHERE id = ?");
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ejemplar_id = $row['ejemplar_id'];

        // ⚠ CAMBIO AQUÍ: 'perdido' en lugar de 'no devuelto'
        $stmt = $conn->prepare("UPDATE prestamos SET estado = 'perdido', observaciones = 'No devuelto' WHERE id = ?");
        $stmt->bind_param("i", $loan_id);
        $updateLoan = $stmt->execute();

        return $updateLoan;
    }

    return false;
}
public function getActiveLoans() {
    $conn = $this->db->getConexion();

    $sql = "SELECT p.*, u.nombre_completo, u.correo, l.titulo, l.isbn
            FROM prestamos p
            JOIN usuarios u ON p.usuario_id = u.id
            JOIN ejemplares e ON p.ejemplar_id = e.id
            JOIN libros l ON e.libro_id = l.id
            WHERE p.estado = 'activo'
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

}