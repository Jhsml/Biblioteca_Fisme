<?php
require_once 'config/Conexion.php';

class Genero {
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->getConexion();
    }

    public function getAll() {
        $generos = [];
        $sql = "SELECT * FROM generos";
        $result = $this->db->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $generos[] = $row;
            }
        }

        return $generos;
    }
}