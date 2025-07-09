<div class="layout-grid">
    <!-- Left Sidebar -->
    <aside class="sidebar-left">
        <!-- Genre Filter -->
        <?php if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id'] == 1): ?>
        <div class="widget">
            <h3 class="widget-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                Categorías
            </h3>
            <div class="genre-list">
                <a href="index.php" class="genre-item <?php echo empty($_GET['genero']) ? 'active' : ''; ?>">
                    Todas las categorías
                </a>
                <?php foreach ($generos as $genero): ?>
                    <a href="index.php?genero=<?php echo $genero['id']; ?>" 
                       class="genre-item <?php echo ($_GET['genero'] ?? '') == $genero['id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($genero['nombre']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- User Reservations -->
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol_id'] == 1): ?>
        <div class="widget">
            <h3 class="widget-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M12 1v6m0 6v6"></path>
                    <path d="m21 12-6-6-6 6-6-6"></path>
                </svg>
                Mis Reservas
            </h3>
            <div id="userReservations">
                <!-- Will be loaded via JavaScript -->
            </div>
        </div>
        <?php endif; ?>

        <!-- Librarian Panel -->
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol_id'] >= 2): ?>
        <div class="widget">
            <h3 class="widget-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Panel de Bibliotecario
            </h3>
            <div class="librarian-menu">
                <a href="index.php?page=loans&action=pending" class="menu-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                    </svg>
                    Realizar Préstamo
                </a>
                <a href="index.php?page=loans&action=history" class="menu-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    Historial de Préstamos
                </a>
                <a href="index.php?page=loans&action=reports" class="menu-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"></polyline>
                    </svg>
                    Reportes
                </a>
            </div>
        </div>
        <?php endif; ?>
    </aside>

    <!-- Main Content -->
    <div class="main-content-area">
        <?php if (empty($books)): ?>
            <div class="no-results">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <p>No se encontraron libros</p>
            </div>
        <?php else: ?>
            <div class="books-grid">
                <?php foreach ($books as $book): ?>
                    <div class="book-card" onclick="showBookDetails(<?php echo $book['id']; ?>)">
                        <div class="book-cover">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                        </div>
                        
                        <div class="book-info">
                            <h3 class="book-title"><?php echo htmlspecialchars($book['titulo']); ?></h3>
                            <p class="book-author">Autor: Varios</p>
                            
                            <div class="book-meta">
                                <span class="book-year">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <?php echo $book['publicacion_anio']; ?>
                                </span>
                                <?php if (isset($book['editorial'])): ?>
                                    <span class="book-publisher"><?php echo htmlspecialchars($book['editorial']); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="availability">
                                <div class="availability-status <?php echo $book['disponibles'] > 0 ? 'available' : 'unavailable'; ?>">
                                    <div class="status-dot"></div>
                                    <span>
                                        <?php if ($book['disponibles'] > 0): ?>
                                            <?php echo $book['disponibles']; ?> disponible<?php echo $book['disponibles'] > 1 ? 's' : ''; ?>
                                        <?php else: ?>
                                            No disponible
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($_SESSION['user']) && $book['disponibles'] > 0): ?>
                            <div class="book-actions">
                                <button onclick="event.stopPropagation(); reserveBook(<?php echo $book['id']; ?>)" class="reserve-btn">
                                    Reservar
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right Sidebar -->
    <?php if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id'] == 1): ?>
    <aside class="sidebar-right">
        <div class="widget">
            <h3 class="widget-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"></polyline>
                </svg>
                Libros Populares
            </h3>
            <div class="popular-books">
                <?php foreach ($popularBooks as $index => $book): ?>
                    <div class="popular-book" onclick="showBookDetails(<?php echo $book['id']; ?>)">
                        <div class="book-cover-small">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                        </div>
                        <div class="popular-book-info">
                            <div class="rank">#<?php echo $index + 1; ?></div>
                            <h4 class="popular-book-title"><?php echo htmlspecialchars($book['titulo']); ?></h4>
                            <p class="popular-book-author">Autor: Varios</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </aside>
    <?php endif; ?>
</div>


