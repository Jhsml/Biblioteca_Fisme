<?php
require_once 'models/Prestamo.php';
require_once 'models/Usuario.php';
require_once 'models/Libro.php';

class Controladorprestamos {
    private $loanModel;

    public function __construct() {
        $this->loanModel = new Prestamo();
    }

    public function pending() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id'] < 2) {
            header('Location: index.php');
            exit;
        }

        $pendingLoans = $this->loanModel->getPendingReservations();

        include 'views/layout/Encabezado.php';
        include 'views/loans/Pendiente.php';
        include 'views/layout/Piedepagina.php';
    }

    public function history() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id'] < 2) {
            header('Location: index.php');
            exit;
        }

        $allLoans = $this->loanModel->getAllLoans();

        include 'views/layout/Encabezado.php';
        include 'views/loans/Historia.php';
        include 'views/layout/Piedepagina.php';
    }

    public function confirm() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id'] < 2) {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loan_id = $_POST['loan_id'] ?? 0;

            $result = $this->loanModel->confirmLoan($loan_id);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Préstamo confirmado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al confirmar préstamo']);
            }
        }
    }

    public function reports() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id'] < 2) {
            header('Location: index.php');
            exit;
        }

        $allLoans = $this->loanModel->getAllLoans();

        // Obtener usuarios y libros desde modelos reales
        $usuarioModel = new Usuario();
        $usuarios = $usuarioModel->getAll(); // este método debes implementarlo

        $libroModel = new Libro();
        $libros = $libroModel->getAll(); // también debes implementarlo

        include 'views/layout/Encabezado.php';
        include 'views/loans/Informes.php';
        include 'views/layout/Piedepagina.php';
    }
}
?>