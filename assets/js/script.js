// Global variables
let currentUser = null;
let isAuthModalOpen = false;
let isBookModalOpen = false;

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Load user reservations if logged in
    loadUserReservations();
    
    // Set up form event listeners
    setupFormListeners();
    
    // Close modals when clicking outside
    setupModalListeners();
}

// Auth Modal Functions
function openAuthModal() {
    const modal = document.getElementById('authModal');
    modal.classList.add('active');
    isAuthModalOpen = true;
    document.body.style.overflow = 'hidden';
}

function closeAuthModal() {
    const modal = document.getElementById('authModal');
    modal.classList.remove('active');
    isAuthModalOpen = false;
    document.body.style.overflow = '';
    
    // Reset forms
    document.getElementById('loginForm').reset();
    document.getElementById('registerForm').reset();
    hideError();
}

function switchTab(tab) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const tabBtns = document.querySelectorAll('.tab-btn');
    const modalTitle = document.getElementById('modalTitle');
    
    // Update tab buttons
    tabBtns.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    // Show/hide forms
    if (tab === 'login') {
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
        modalTitle.textContent = 'Iniciar Sesión';
    } else {
        loginForm.classList.remove('active');
        registerForm.classList.add('active');
        modalTitle.textContent = 'Registrarse';
    }
    
    hideError();
}

// Book Modal Functions
function showBookDetails(bookId) {
    const modal = document.getElementById('bookModal');
    const content = document.getElementById('bookModalContent');

    content.innerHTML = '<div class="text-center"><p>Cargando...</p></div>';
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

   fetch(`index.php?page=libro&action=details&id=${bookId}`) // ← ¡correcto si usas Controladorinicio!
        .then(response => response.json())
        .then(book => {
            content.innerHTML = generateBookDetailsHTML(book);  // Asegúrate de que esta función esté definida
        })
        .catch(error => {
            content.innerHTML = '<div class="text-center"><p>Error al cargar los detalles del libro</p></div>';
            console.error('Error:', error);
        });
}

function closeBookModal() {
    const modal = document.getElementById('bookModal');
    modal.classList.remove('active');
    isBookModalOpen = false;
    document.body.style.overflow = '';
}

function generateBookDetailsHTML(book) {
    const availableCount = book.ejemplares ? book.ejemplares.filter(e => e.estado === 'disponible').length : 0;
    const isAvailable = availableCount > 0;
    const isLoggedIn = document.querySelector('.user-info') !== null;
    
    return `
        <div class="book-details-content">
            <div class="book-details-grid">
                <div class="book-cover-large">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                
                <div class="book-details-info">
                    <h3 class="book-details-title">${book.titulo}</h3>
                    <p class="book-details-author">Autor: Varios</p>
                    
                    <div class="book-details-meta">
                        <div class="meta-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>${book.publicacion_anio}</span>
                        </div>
                        ${book.editorial ? `<span>Editorial: ${book.editorial.nombre}</span>` : ''}
                    </div>
                    
                    <div class="isbn-info">
                        <strong>ISBN:</strong> ${book.isbn}
                    </div>
                    
                    ${book.descripcion ? `
                        <div class="book-description">
                            <strong>Descripción:</strong>
                            <p>${book.descripcion}</p>
                        </div>
                    ` : ''}
                </div>
            </div>
            
            <div class="availability-section">
                <div class="availability-header">
                    <div>
                        <strong>Disponibilidad:</strong>
                        <div class="availability-status ${isAvailable ? 'available' : 'unavailable'}">
                            <div class="status-dot"></div>
                            <span>
                                ${isAvailable 
                                    ? `${availableCount} ejemplar${availableCount > 1 ? 'es' : ''} disponible${availableCount > 1 ? 's' : ''}`
                                    : 'No disponible'
                                }
                            </span>
                        </div>
                    </div>
                    
                    ${isLoggedIn && isAvailable ? `
                        <button onclick="reserveBookFromModal(${book.id})" class="reserve-btn">
                            Reservar Libro
                        </button>
                    ` : ''}
                </div>
                
                ${book.ejemplares && book.ejemplares.filter(e => e.estado === 'disponible').length > 0 ? `
                    <div class="locations">
                        <strong>Ubicaciones:</strong>
                        <div class="location-list">
                            ${book.ejemplares
                                .filter(e => e.estado === 'disponible')
                                .map(e => `
                                    <div class="location-item">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <span>${e.ubicacion}</span>
                                    </div>
                                `).join('')}
                        </div>
                    </div>
                ` : ''}
                
                ${!isLoggedIn ? `
                    <div class="login-prompt">
                        <p>Inicia sesión para poder reservar libros.</p>
                    </div>
                ` : ''}
            </div>
        </div>
        
        <style>
            .book-details-content {
                padding: 0;
            }
            
            .book-details-grid {
                display: flex;
                gap: 2rem;
                margin-bottom: 2rem;
            }
            
            .book-cover-large {
                width: 12rem;
                height: 16rem;
                background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            
            .book-cover-large svg {
                color: #2563eb;
            }
            
            .book-details-info {
                flex: 1;
            }
            
            .book-details-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: #374151;
                margin-bottom: 0.5rem;
            }
            
            .book-details-author {
                color: #6b7280;
                margin-bottom: 1rem;
            }
            
            .book-details-meta {
                display: flex;
                align-items: center;
                gap: 1rem;
                font-size: 0.875rem;
                color: #6b7280;
                margin-bottom: 1rem;
            }
            
            .meta-item {
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }
            
            .isbn-info {
                background: #f9fafb;
                padding: 0.75rem;
                border-radius: 0.375rem;
                margin-bottom: 1rem;
                font-size: 0.875rem;
            }
            
            .book-description {
                font-size: 0.875rem;
            }
            
            .book-description p {
                color: #6b7280;
                line-height: 1.6;
                margin-top: 0.5rem;
            }
            
            .availability-section {
                background: #f9fafb;
                padding: 1rem;
                border-radius: 0.5rem;
            }
            
            .availability-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1rem;
            }
            
            .locations {
                font-size: 0.875rem;
            }
            
            .location-list {
                margin-top: 0.5rem;
            }
            
            .location-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: #6b7280;
                margin-bottom: 0.25rem;
            }
            
            .login-prompt {
                background: #dbeafe;
                color: #1d4ed8;
                padding: 0.75rem;
                border-radius: 0.375rem;
                font-size: 0.875rem;
            }
            
            @media (max-width: 768px) {
                .book-details-grid {
                    flex-direction: column;
                    align-items: center;
                }
                
                .book-cover-large {
                    width: 10rem;
                    height: 13rem;
                }
                
                .availability-header {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 1rem;
                }
            }
        </style>
    `;
}

// Book Reservation Functions
function reserveBook(bookId) {
    if (!document.querySelector('.user-info')) {
        openAuthModal();
        return;
    }

    const formData = new FormData();
    formData.append('libro_id', bookId);

    fetch('index.php?page=books&action=reserve', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();

        try {
            const data = JSON.parse(text); // Verifica si es JSON válido

            if (data.success) {
                showMessage(data.message, 'success');
                loadUserReservations();
            } else {
                showMessage(data.message || 'Ocurrió un error', 'error');
            }
        } catch (e) {
            console.error('Respuesta inesperada del servidor:', text);
            showMessage('Error del servidor. Ver consola.', 'error');
        }
    })
    .catch(error => {
        showMessage('Error al procesar la reserva', 'error');
        console.error('Error de red:', error);
    });
}
// Loan Management Functions
function confirmLoan(loanId) {
    const formData = new FormData();
    formData.append('loan_id', loanId);
    
    fetch('index.php?page=loans&action=confirm', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Error al confirmar préstamo', 'error');
        console.error('Error:', error);
    });
}

// User Reservations
function loadUserReservations() {
    const container = document.getElementById('userReservations');
    if (!container) return;
    
    // Mock user reservations for demo
    container.innerHTML = `
        <div class="reservation-item">
            <div class="reservation-book">
                <div class="book-cover-mini">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div class="reservation-info">
                    <h4>Fundamentals of Database Systems</h4>
                    <div class="reservation-date">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Reservado: 25/01/2024
                    </div>
                    <div class="reservation-status">
                        <span class="status-badge status-pending">Pendiente de confirmación</span>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .reservation-item {
                border: 1px solid #e5e7eb;
                border-radius: 0.375rem;
                padding: 0.75rem;
                margin-bottom: 0.75rem;
                transition: all 0.2s;
            }
            
            .reservation-item:hover {
                background: #f9fafb;
            }
            
            .reservation-book {
                display: flex;
                gap: 0.75rem;
            }
            
            .book-cover-mini {
                width: 2.5rem;
                height: 3rem;
                background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
                border-radius: 0.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            
            .book-cover-mini svg {
                color: #2563eb;
            }
            
            .reservation-info {
                flex: 1;
                min-width: 0;
            }
            
            .reservation-info h4 {
                font-size: 0.875rem;
                font-weight: 500;
                color: #374151;
                margin-bottom: 0.25rem;
                line-height: 1.3;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .reservation-date {
                display: flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.75rem;
                color: #6b7280;
                margin-bottom: 0.5rem;
            }
            
            .status-badge.status-pending {
                background: #fef3c7;
                color: #d97706;
                font-size: 0.75rem;
                padding: 0.125rem 0.5rem;
                border-radius: 9999px;
                font-weight: 500;
            }
        </style>
    `;
}

// Form Handling
function setupFormListeners() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }
}

function handleLogin(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const submitBtn = event.target.querySelector('.submit-btn');

    submitBtn.disabled = true;
    submitBtn.textContent = 'Iniciando sesión...';

    fetch('index.php?page=auth&action=login', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // 🔁 CAMBIADO: era .text()
    .then(data => {
        if (data.success) {
            location.reload(); // ✅ esto fuerza la recarga y actualiza la sesión
        } else {
            showError(data.message || 'Credenciales incorrectas');
        }
    })
    .catch(error => {
        showError('Error al iniciar sesión');
        console.error('Error:', error);
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Iniciar Sesión';
    });
}




function handleRegister(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const password = formData.get('contraseña');
    const confirmPassword = formData.get('confirmar_contraseña');

    if (password !== confirmPassword) {
        showError('Las contraseñas no coinciden');
        return;
    }

    if (password.length < 6) {
        showError('La contraseña debe tener al menos 6 caracteres');
        return;
    }

    const submitBtn = event.target.querySelector('.submit-btn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Registrando...';

    fetch('index.php?page=auth&action=register', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        console.log("🔍 Respuesta sin parsear:", text);
        try {
            const data = JSON.parse(text);
            if (data.redirect) {
                window.location.href = data.redirect;
            } else if (data.message) {
                showError(data.message);
            } else {
                showError("Error desconocido en el registro");
            }
        } catch (e) {
            showError("⚠️ El servidor no respondió correctamente");
            console.error("❌ Respuesta no válida JSON:", text);
        }
    })
    .catch(error => {
        showError('Error al registrar usuario');
        console.error('🚨 Error:', error);
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Registrarse';
    });
}


document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');
    if (form) {
        form.addEventListener('submit', handleRegister);
        console.log("🟢 Formulario de registro conectado correctamente");
    } else {
        console.log("🔴 No se encontró el formulario con ID registerForm");
    }
});



// Este código se asegura que la función se asocie correctamente al formulario
document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }
});


// Modal Event Listeners
function setupModalListeners() {
    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        const authModal = document.getElementById('authModal');
        const bookModal = document.getElementById('bookModal');
        
        if (event.target === authModal && isAuthModalOpen) {
            closeAuthModal();
        }
        
        if (event.target === bookModal && isBookModalOpen) {
            closeBookModal();
        }
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (isAuthModalOpen) {
                closeAuthModal();
            }
            if (isBookModalOpen) {
                closeBookModal();
            }
        }
    });
}

// Utility Functions
function togglePassword(button) {
    const input = button.parentElement.querySelector('input');
    const isPassword = input.type === 'password';
    
    input.type = isPassword ? 'text' : 'password';
    
    const svg = button.querySelector('svg');
    if (isPassword) {
        svg.innerHTML = `
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        `;
    } else {
        svg.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        `;
    }
}

function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

function hideError() {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.style.display = 'none';
}

function showMessage(message, type = 'info') {
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.className = `message-toast message-${type}`;
    messageDiv.textContent = message;
    
    // Add styles
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 1001;
        animation: slideIn 0.3s ease-out;
        max-width: 300px;
        word-wrap: break-word;
    `;
    
    if (type === 'success') {
        messageDiv.style.backgroundColor = '#059669';
    } else if (type === 'error') {
        messageDiv.style.backgroundColor = '#dc2626';
    } else {
        messageDiv.style.backgroundColor = '#2563eb';
    }
    
    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    
    if (!document.querySelector('style[data-toast]')) {
        style.setAttribute('data-toast', 'true');
        document.head.appendChild(style);
    }
    
    document.body.appendChild(messageDiv);
    
    // Remove after 3 seconds
    setTimeout(() => {
        messageDiv.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.parentNode.removeChild(messageDiv);
            }
        }, 300);
    }, 3000);
}

// Search functionality
function handleSearch() {
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(event) {
            const searchInput = this.querySelector('input[name="search"]');
            if (!searchInput.value.trim()) {
                event.preventDefault();
                window.location.href = 'index.php';
            }
        });
    }
}

// Initialize search
document.addEventListener('DOMContentLoaded', handleSearch);