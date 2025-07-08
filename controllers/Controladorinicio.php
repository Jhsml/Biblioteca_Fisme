<?php
require_once 'models/Libro.php';
require_once 'models/Genero.php';

class Controladorinicio {
    private $bookModel;
    private $generoModel;

    public function __construct() {
        $this->bookModel = new Libro();
        $this->generoModel = new Genero();
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $genero_id = $_GET['generos'] ?? null;

        $books = $this->bookModel->getAllBooks($search, $genero_id);
        $popularBooks = $this->bookModel->getPopularBooks();
        $generos = $this->generoModel->getAll();

        include 'views/layout/Encabezado.php';
        include 'views/home/vista_libros.php';
        include 'views/layout/Piedepagina.php';
    }
}