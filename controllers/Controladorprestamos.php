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
  public function reject() {
    header('Content-Type: application/json'); // Asegura tipo JSON

    if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id'] < 2) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $loan_id = $_POST['loan_id'] ?? 0;

        $result = $this->loanModel->rejectLoan($loan_id); // ya corregido

        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Préstamo rechazado exitosamente' : 'No se pudo rechazar el préstamo'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
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

public function markReturned() {
    header('Content-Type: application/json');

    if (!isset($_POST['loan_id'])) {
        echo json_encode(['success' => false, 'message' => 'ID faltante']);
        exit;
    }

    $loan_id = $_POST['loan_id'];
    require_once 'models/Prestamo.php';
    $model = new Prestamo();

    if ($model->marcarComoDevuelto($loan_id)) {
        echo json_encode(['success' => true, 'message' => 'Marcado como devuelto']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo marcar como devuelto']);
    }
    exit; // ✅ para cortar ejecución completamente
}


   public function marcarNoDevuelto() {
    header('Content-Type: application/json');

    if (!isset($_POST['loan_id'])) {
        echo json_encode(['success' => false, 'message' => 'ID del préstamo faltante']);
        return;
    }

    $loan_id = $_POST['loan_id'];
    require_once 'models/Prestamo.php';
    $model = new Prestamo();

    $resultado = $model->markAsNotReturned($loan_id);

    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Préstamo marcado como no devuelto']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo marcar el préstamo']);
    }
}
    public function mostrarHistorialActivos() {
    $prestamoModel = new Prestamo();
    $prestamosActivos = $prestamoModel->getActiveLoans(); // Nuevo método
    include 'views/loans/historial.php';
}
}
?>