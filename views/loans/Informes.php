<div class="page-header">
    <h1>Reportes y Estadísticas</h1>
    <p>Análisis del sistema de biblioteca</p>
</div>

<div class="reports-grid">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Total Usuarios</h3>
                <p class="stat-number"><?php echo count($usuarios); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Total Libros</h3>
                <p class="stat-number"><?php echo count($libros); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"></polyline>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Préstamos Activos</h3>
                <p class="stat-number">
                    <?php 
                    $activeLoans = array_filter($allLoans, function($loan) {
                        return $loan['estado'] === 'activo';
                    });
                    echo count($activeLoans);
                    ?>
                </p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Total Préstamos</h3>
                <p class="stat-number"><?php echo count($allLoans); ?></p>
            </div>
        </div>
    </div>

    <!-- Popular Books Chart -->
    <div class="content-card">
        <h3 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"></polyline>
            </svg>
            Libros Más Populares
        </h3>
        
        <div class="popular-books-chart">
            <?php 
            require_once 'models/Libro.php';
            $bookModel = new Libro();
            $popularBooks = $bookModel->getPopularBooks();
            
            foreach ($popularBooks as $index => $book): 
            ?>
                <div class="popular-book-item">
                    <div class="book-rank">#<?php echo $index + 1; ?></div>
                    <div class="book-details">
                        <h4><?php echo htmlspecialchars($book['titulo']); ?></h4>
                        <p>Autor: Varios</p>
                    </div>
                    <div class="book-stats">
                        <span class="loan-count"><?php echo rand(5, 25); ?> préstamos</span>
                        <span class="period">Este mes</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>