<div class="page-header">
    <h1>Historial de Préstamos</h1>
    <p>Registro completo de préstamos</p>
</div>

<div class="content-card">
    <?php if (empty($allLoans)): ?>
        <div class="no-results">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
            <p>No hay historial de préstamos</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Vencimiento</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allLoans as $loan): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                    <div class="user-name"><?php echo htmlspecialchars($loan['usuarios']['nombre_completo']); ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="book-info">
                                    <div class="book-title"><?php echo htmlspecialchars($loan['libros']['titulo']); ?></div>
                                    <div class="book-isbn">ISBN: <?php echo htmlspecialchars($loan['libros']['isbn']); ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="date-info">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <?php echo date('d/m/Y', strtotime($loan['fecha_prestamo'])); ?>
                                </div>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($loan['fecha_vencimiento'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $loan['estado']; ?>">
                                    <?php 
                                    switch($loan['estado']) {
                                        case 'activo': echo 'Activo'; break;
                                        case 'devuelto': echo 'Devuelto'; break;
                                        case 'perdido': echo 'Perdido'; break;
                                        default: echo ucfirst($loan['estado']);
                                    }
                                    ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($loan['observaciones'] ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>