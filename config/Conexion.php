<?php
class Conexion {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "untrm";
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli($this->servername, 
                                     $this->username, 
                                     $this->password, 
                                     $this->db_name);
        if ($this->conexion->connect_error) {
            die("Conexión falló: " . $this->conexion->connect_error);
        }
    }

    public function getConexion() {
        return $this->conexion;
    }

    
     
}