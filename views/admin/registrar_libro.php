<?php 
include_once '../../views/layout/Encabezado.php'; 
require_once '../../models/Editorial.php';
$editorialModel = new Editorial();
$editoriales = $editorialModel->getAll();
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="form-container form-container-centered">
                <h2>Registrar Nuevo Libro</h2>
                <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div id="success-message" class="alert alert-success custom-success-message" style="background: #198754; color: #fff; border-radius: 8px; border: none; box-shadow: 0 2px 8px rgba(25,135,84,0.08); font-weight: 500; text-align: center;">
                        <svg style="vertical-align:middle;margin-right:8px;" width="22" height="22" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="2" fill="#198754"/><path d="M8 12l2 2l4-4" stroke="#fff" stroke-width="2" fill="none"/></svg>
                        Libro registrado exitosamente.
                    </div>
                    <script>
                        setTimeout(function() {
                            var msg = document.getElementById('success-message');
                            if (msg) { msg.style.display = 'none'; }
                        }, 2000);
                    </script>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="alert alert-danger custom-error-message" style="background: #dc3545; color: #fff; border-radius: 8px; border: none; box-shadow: 0 2px 8px rgba(220,53,69,0.08); font-weight: 500; text-align: center;">
                        <svg style="vertical-align:middle;margin-right:8px;" width="22" height="22" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="2" fill="#dc3545"/><path d="M8 8l8 8M16 8l-8 8" stroke="#fff" stroke-width="2" fill="none"/></svg>
                        Error al registrar el libro.
                        <?php if ($_GET['error'] !== '1') echo '<br><small>' . htmlspecialchars($_GET['error']) . '</small>'; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="../../controllers/Controladorlibro.php">
                    <input type="hidden" name="action" value="registrar_libro">
                    <div class="form-group">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN:</label>
                        <input type="text" id="isbn" name="isbn" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="publicacion_anio">Año de Publicación:</label>
                        <input type="number" id="publicacion_anio" name="publicacion_anio" min="1000" max="2100" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editorial_id">Editorial:</label>
                        <select id="editorial_id" name="editorial_id" class="form-control" required>
                            <option value="">Seleccione una editorial</option>
                            <?php foreach ($editoriales as $editorial): ?>
                                <option value="<?php echo $editorial['id']; ?>"><?php echo htmlspecialchars($editorial['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrar Libro</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once '../../views/layout/Piedepagina.php'; ?>
