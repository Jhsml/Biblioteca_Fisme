<?php
require_once __DIR__ . '/../config/Conexion.php';

class Libro {
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->getConexion();
    }

    public function getAllBooks($search = '', $genero_id = null) {
        $libros = [];
        $sql = "SELECT l.*, e.nombre AS editorial 
                FROM libros l 
                LEFT JOIN editoriales e ON l.editorial_id = e.id";

        if (!empty($search)) {
            $search = $this->db->real_escape_string($search);
            $sql .= " WHERE l.titulo LIKE '%$search%' OR l.isbn LIKE '%$search%'";
        }

        $result = $this->db->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['disponibles'] = $this->contarDisponibles($row['id']);
                $libros[] = $row;
            }
        }

        return $libros;
    }

    private function contarDisponibles($libro_id) {
        $sql = "SELECT COUNT(*) AS disponibles FROM ejemplares WHERE libro_id = $libro_id AND estado = 'disponible'";
        $result = $this->db->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return (int)$row['disponibles'];
        }
        return 0;
    }

    public function getById($id) {
        $libro = null;
        $id = (int)$id;

        $sql = "SELECT l.*, e.nombre AS editorial 
                FROM libros l 
                LEFT JOIN editoriales e ON l.editorial_id = e.id 
                WHERE l.id = $id";

        $result = $this->db->query($sql);
        if ($result && $result->num_rows > 0) {
            $libro = $result->fetch_assoc();
            $libro['ejemplares'] = $this->getEjemplaresByLibro($id);
        }

        return $libro;
    }

    private function getEjemplaresByLibro($libro_id) {
        $ejemplares = [];
        $sql = "SELECT * FROM ejemplares WHERE libro_id = $libro_id";
        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $ejemplares[] = $row;
            }
        }
        return $ejemplares;
    }

    public function getPopularBooks($limit = 5) {
        $sql = "SELECT l.*, e.nombre AS editorial 
                FROM libros l 
                LEFT JOIN editoriales e ON l.editorial_id = e.id 
                ORDER BY l.fecha_registro DESC 
                LIMIT $limit";

        $result = $this->db->query($sql);
        $libros = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['disponibles'] = $this->contarDisponibles($row['id']);
                $libros[] = $row;
            }
        }
        return $libros;
    }

    public function getAll() {
        $libros = [];
        $sql = "SELECT * FROM libros";
        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $libros[] = $row;
            }
        }
        return $libros;
    }

    public function registrarLibro($data, &$errorMsg = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO libros (isbn, titulo, descripcion, publicacion_anio, editorial_id) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                $errorMsg = $this->db->error;
                return false;
            }
            $stmt->bind_param(
                "ssssi",
                $data['isbn'],
                $data['titulo'],
                $data['descripcion'],
                $data['publicacion_anio'],
                $data['editorial_id']
            );
            $result = $stmt->execute();
            if (!$result) {
                $errorMsg = $stmt->error;
            }
            $stmt->close();
            return $result;
        } catch (mysqli_sql_exception $e) {
            $errorMsg = $e->getMessage();
            return false;
        }
    }
}
?>