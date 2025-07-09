<?php
require_once 'config/Conexion.php';

class Escuela {
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->getConexion();
    }

    public function getAll() {
        $escuelas = [];
        $sql = "SELECT * FROM escuelas";
        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $escuelas[] = $row;
            }
        }
        return $escuelas;
    }

    public function listarEscuelas() {
        $escuelas = [];
        $sql = "SELECT id, nombre FROM escuelas ORDER BY nombre ASC";
        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $escuelas[] = $row;
            }
        }
        return $escuelas;
    }
}


