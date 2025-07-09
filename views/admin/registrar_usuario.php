<?php
// Redirigir siempre a la ruta principal para evitar error Not Found y mantener el flujo correcto
if ($_SERVER['SCRIPT_NAME'] !== '/untrm/gestion_usuarios.php') {
    header('Location: /untrm/gestion_usuarios.php');
    exit;
}
// Incluir encabezado y pie de página con rutas absolutas
include_once dirname(__DIR__, 2) . '/views/layout/Encabezado.php';
// Llamar al CSS externo para el diseño de gestión de usuarios
echo '<link rel="stylesheet" href="/untrm/assets/css/gestion_usuarios.css">';
// $usuarios y $escuelas deben ser proporcionados por el controlador
?>
<div class="container mt-4">
    <div class="gestion-usuarios-wrapper">
        <!-- Formulario de registro a la izquierda -->
        <div class="gestion-usuarios-box">
            <div class="card shadow border-0 mb-0">
                <div class="card-header bg-gradient-primary text-white d-flex align-items-center" style="background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                        <circle cx="12" cy="7" r="4"></circle>
                        <path d="M5.5 21v-2a4.5 4.5 0 0 1 9 0v2"></path>
                    </svg>
                    <h3 class="mb-0" style="font-size:1.2rem;">Registrar Nuevo Usuario</h3>
                </div>
                <div class="card-body bg-light">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">Usuario registrado exitosamente.</div>
                    <?php elseif (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">Error al registrar usuario: <?php echo htmlspecialchars($_GET['error']); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="/untrm/gestion_usuarios.php">
                        <input type="hidden" name="action" value="registrar_usuario">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Apellido</label>
                                <input type="text" name="apellido" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rol</label>
                                <select name="rol_id" class="form-select" required>
                                    <option value="1">Usuario</option>
                                    <option value="2">Bibliotecario</option>
                                    <option value="3">Administrador</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Escuela</label>
                                <select name="escuela_id" class="form-select">
                                    <option value="">Sin escuela</option>
                                    <?php if (!empty($escuelas)): ?>
                                        <?php foreach ($escuelas as $escuela): ?>
                                            <option value="<?php echo $escuela['id']; ?>"><?php echo htmlspecialchars($escuela['nombre']); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-2">Registrar Usuario</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Usuarios registrados a la derecha -->
        <div class="gestion-usuarios-box right">
            <div class="card shadow border-0 h-100 mb-0">
                <div class="card-header bg-gradient-success text-white d-flex align-items-center" style="background: linear-gradient(90deg, #28a745 0%, #218838 100%);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                        <circle cx="12" cy="7" r="4"></circle>
                        <path d="M5.5 21v-2a4.5 4.5 0 0 1 9 0v2"></path>
                    </svg>
                    <h3 class="mb-0" style="font-size:1.2rem;">Usuarios Registrados</h3>
                </div>
                <div class="card-body bg-light p-2">
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong><?php echo htmlspecialchars($usuario['nombre_completo']); ?></strong>
                                        <small class="text-muted d-block" style="font-size:0.85em;">Rol: <?php echo htmlspecialchars($usuario['nombre_rol']); ?></small>
                                    </span>
                                    <?php if (isset($usuario['correo'])): ?>
                                        <span class="badge bg-primary rounded-pill" style="font-size:0.9em;">
                                            <?php echo htmlspecialchars($usuario['correo']); ?>
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">No hay usuarios registrados.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once dirname(__DIR__, 2) . '/views/layout/Piedepagina.php'; ?>
