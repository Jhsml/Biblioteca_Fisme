<?php
require_once 'models/Escuela.php';
$escuelaModel = new Escuela();
$escuelas = $escuelaModel->getAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Biblioteca - UNTRM</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <h1>UNTRM</h1>
                    <p>Sistema de Biblioteca</p>
                </div>

                <!-- Search Bar -->
                <div class="search-container">
                    <form method="GET" action="index.php" class="search-form">
                        <input type="text" 
                               name="search" 
                               placeholder="Buscar libros por título, autor o ISBN..."
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                               class="search-input">
                        <button type="submit" class="search-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- User Actions -->
                <div class="user-actions">
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="user-info">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span><?php echo htmlspecialchars($_SESSION['user']['nombre_completo']); ?></span>
                        </div>
                        <a href="index.php?page=auth&action=logout" class="logout-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16,17 21,12 16,7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Cerrar Sesión
                        </a>
                    <?php else: ?>
                        <button onclick="openAuthModal()" class="login-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10,17 15,12 10,7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                            Iniciar 
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
        
    </header>

    <main class="main-content">
        <div class="container">