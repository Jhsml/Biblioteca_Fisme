</div>
    </main>

    <!-- Auth Modal -->
    <div id="authModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Iniciar Sesión</h2>
                <button onclick="closeAuthModal()" class="close-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div class="modal-body">
                <!-- Tab Navigation -->
                <div class="tab-nav">
                    <button class="tab-btn active" onclick="switchTab('login')">Iniciar Sesión</button>
                    <button class="tab-btn" onclick="switchTab('register')">Registrarse</button>
                </div>

                <div id="errorMessage" class="error-message" style="display: none;"></div>

                <!-- Login Form -->
                <form id="loginForm" class="auth-form active">
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" required placeholder="usuario@ejemplo.com">
                    </div>

                    <div class="form-group">
                        <label>Contraseña</label>
                        <div class="password-input">
                            <input type="password" name="contraseña" required placeholder="Tu contraseña">
                            <button type="button" onclick="togglePassword(this)" class="password-toggle">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Iniciar Sesión</button>

                   
                </form>

                <!-- Register Form -->
                <form id="registerForm" class="auth-form">
                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre_completo" required placeholder="Tu nombre completo">
                    </div>

                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" required placeholder="usuario@ejemplo.com">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Tipo de Documento</label>
                            <select name="tipo_documento">
                                <option value="DNI">DNI</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="Carnet de Extranjería">Carnet de Extranjería</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Número de Documento</label>
                            <input type="text" name="num_documento" required placeholder="12345678">
                        </div>
                    </div>

                   <div class="form-group">
    <label>Escuela</label>
    <select name="escuela_id" required>
        <?php foreach ($escuelas as $escuela): ?>
            <option value="<?php echo $escuela['id']; ?>">
                <?php echo htmlspecialchars($escuela['nombre']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

                    <div class="form-group">
                        <label>Contraseña</label>
                        <div class="password-input">
                            <input type="password" name="contraseña" required placeholder="Mínimo 6 caracteres">
                            <button type="button" onclick="togglePassword(this)" class="password-toggle">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirmar Contraseña</label>
                        <input type="password" name="confirmar_contraseña" required placeholder="Repite tu contraseña">
                    </div>

                    <button type="submit" class="submit-btn">Registrarse</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Book Details Modal -->
    <div id="bookModal" class="modal">
        <div class="modal-content book-modal">
            <div class="modal-header">
                <h2>Detalles del Libro</h2>
                <button onclick="closeBookModal()" class="close-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="bookModalContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>


    <script src="assets/js/script.js"></script>
</body>
</html>