<?php
require_once __DIR__ . '/../config/Conexion.php';

class Editorial {
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->getConexion();
    }

    public function getAll() {
        $editoriales = [];
        $sql = "SELECT id, nombre FROM editoriales ORDER BY nombre ASC";
        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $editoriales[] = $row;
            }
        }
        return $editoriales;
    }
}
?>
