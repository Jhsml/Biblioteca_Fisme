<?php
require_once 'models/Libro.php';

class Controladorlibro {
    private $bookModel;

    public function __construct() {
        $this->bookModel = new Libro();
    }

    public function details() {
        $id = $_GET['id'] ?? 0;
        $book = $this->bookModel->getById($id);

        if (!$book) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Libro no encontrado']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode($book);
    }

    public function reserve() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libro_id = $_POST['libro_id'] ?? 0;
            $usuario_id = $_SESSION['user']['id'];

            require_once 'models/Loan.php';
            $loanModel = new Prestamo();

            $reservation = $loanModel->createReservation($usuario_id, $libro_id);

            if ($reservation) {
                echo json_encode(['success' => true, 'message' => 'Libro reservado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No hay ejemplares disponibles']);
            }
        }
    }

    public function index() {
        require_once 'models/Genero.php';
        require_once 'models/Libro.php';
        require_once 'models/Escuela.php';

        $escuelaModel = new Escuela();
        $escuelas = $escuelaModel->getAll();

        $generoModel = new Genero();
        $libroModel = new Libro();

        $generos = $generoModel->getAll();

        $search = $_GET['search'] ?? '';
        $genero_id = $_GET['genero'] ?? null;

        $books = $libroModel->getAllBooks($search, $genero_id);
        $popularBooks = $libroModel->getPopularBooks();

        include 'views/layout/Encabezado.php';
        include 'views/home/vista_libros.php';
        include 'views/layout/Piedepagina.php';
    }
}
?>
