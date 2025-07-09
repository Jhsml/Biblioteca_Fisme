<?php
require_once 'models/Libro.php';

class Controladorlibro {
    private $bookModel;

    public function __construct() {
        $this->bookModel = new Libro();
    }

   public function details() {
    header('Content-Type: application/json');

    $id = $_GET['id'] ?? 0;
    $book = $this->bookModel->getById($id);

    if (!$book) {
        http_response_code(404);
        echo json_encode(['error' => 'Libro no encontrado']);
        exit;
    }

    echo json_encode($book);
    exit;
}

   public function reserve() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');

    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $libro_id = $_POST['libro_id'] ?? 0;
        $usuario_id = $_SESSION['user']['id'];

        require_once 'models/Prestamo.php';
        $loanModel = new Prestamo();

        $reservation = $loanModel->createReservation($usuario_id, $libro_id);

        if ($reservation) {
            echo json_encode(['success' => true, 'message' => 'Libro reservado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No hay ejemplares disponibles']);
        }
    } else {
        http_response_code(405); // Método no permitido
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
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
    public function verDetalle() {
    $id = $_GET['id'] ?? 0;
    $detalle = $this->bookModel->getById($id);

    if (!$detalle) {
        include 'views/layout/Encabezado.php';
        echo "<h2>Libro no encontrado</h2>";
        include 'views/layout/Piedepagina.php';
        return;
    }

    include 'views/layout/Encabezado.php';
    include 'views/home/vista_detalle_libro.php';
    include 'views/layout/Piedepagina.php';
    
}
}
?>


