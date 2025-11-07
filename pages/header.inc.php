<!--begin::Header-->
<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center text-decoration-none" href="panel?pg=facturar-login">
            <span class="fw-bold text-primary">Facturación Electrónica</span>
        </a>

        <!-- Mobile menu toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse " id="navbarNav">
            <!-- Left side navigation -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item mx-1">
                    <a class="nav-link d-flex align-items-center rounded-pill px-3 py-2 <?php echo ($pagePath == '' || $pagePath == 'inicio') ? 'bg-primary text-white fw-bold' : 'text-dark'; ?>" href="inicio">
                        <i class="bi bi-house me-2"></i> Inicio
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link d-flex align-items-center rounded-pill px-3 py-2 <?php echo ($pagePath == 'facturar') ? 'bg-primary text-white fw-bold' : 'text-dark'; ?>" href="facturar">
                        <i class="bi bi-receipt-cutoff me-2"></i> Facturar
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link d-flex align-items-center rounded-pill px-3 py-2 <?php echo ($pagePath == 'historial') ? 'bg-primary text-white fw-bold' : 'text-dark'; ?>" href="historial">
                        <i class="bi bi-clock-history me-2"></i> Historial
                    </a>
                </li>
            </ul>

            <!-- Right side navigation -->
            <ul class="navbar-nav">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative rounded-circle p-2" href="#" id="notificationDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-5 text-primary"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                            <span class="visually-hidden">notificaciones no leídas</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="notificationDropdown">
                        <li>
                            <h6 class="dropdown-header text-primary fw-bold">Notificaciones</h6>
                        </li>
                        <li><a class="dropdown-item rounded-2 mx-2" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-check-circle text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <small class="text-muted">Factura generada exitosamente</small>
                                    </div>
                                </div>
                            </a></li>
                        <li><a class="dropdown-item rounded-2 mx-2" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-info-circle text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <small class="text-muted">Sistema actualizado</small>
                                    </div>
                                </div>
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center text-primary fw-semibold" href="#">Ver todas</a></li>
                    </ul>
                </li>

                <!-- Fullscreen toggle -->
                <li class="nav-item">
                    <a class="nav-link rounded-circle p-2" href="#" onclick="toggleFullscreen()">
                        <i class="bi bi-arrows-fullscreen fs-5 text-primary" id="fullscreenIcon"></i>
                    </a>
                </li>

                <!-- User dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center px-3 py-2" href="#" id="userDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary rounded-circle p-2 me-2">
                            <i class="bi bi-person-fill text-white fs-6"></i>
                        </div>
                        <span class="d-none d-md-inline fw-semibold text-primary">
                            <?php echo !empty($_SESSION['USR_NAME']) ? $_SESSION['USR_NAME'] : 'Usuario'; ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="userDropdown">
                        <li>
                            <h6 class="dropdown-header text-primary fw-bold">Mi Cuenta</h6>
                        </li>
                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarPerfil()">
                                <i class="bi bi-person me-2 text-primary"></i> Mi Perfil
                            </a></li>
                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarConfiguracion()">
                                <i class="bi bi-gear me-2 text-secondary"></i> Configuración
                            </a></li>
                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarFacturas()">
                                <i class="bi bi-file-earmark-text me-2 text-info"></i> Mis Facturas
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarSoporte()">
                                <i class="bi bi-question-circle me-2 text-warning"></i> Soporte
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item rounded-2 mx-2 text-danger" href="#" onclick="cerrarSesion()">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                            </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!--end::Header-->

<nav aria-label="breadcrumb" class="bg-light border-top py-2">
    <div class="container-fluid">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="<?php echo HOMEURL; ?>" class="text-decoration-none text-primary">
                    <i class="bi bi-house me-1"></i>Inicio
                </a>
            </li>
            <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">
                <?php echo ucfirst(str_replace('-', ' ', $pagePath)); ?>
            </li>
        </ol>
    </div>
</nav>

<script>
    // Función para toggle fullscreen
    function toggleFullscreen() {
        const icon = document.getElementById('fullscreenIcon');
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().then(() => {
                icon.className = 'bi bi-fullscreen-exit fs-5';
            });
        } else {
            document.exitFullscreen().then(() => {
                icon.className = 'bi bi-arrows-fullscreen fs-5';
            });
        }
    }

// Funciones de navegación
function mostrarHistorial() {
    window.location.href = 'historial';
    
}

function mostrarPerfil() {
    window.location.href = 'perfil';
}

function mostrarConfiguracion() {
    window.location.href = 'configuracion';
}

function mostrarFacturas() {
    window.location.href = 'historial';
}

function mostrarSoporte() {
    window.location.href = 'soporte';
}

function cerrarSesion() {
    if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
        // Aquí puedes agregar lógica de logout
        window.location.href = 'facturar-login';
    }
}    // Auto-hide navbar on scroll (opcional)
    let lastScrollTop = 0;
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            navbar.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            navbar.style.transform = 'translateY(0)';
        }

        lastScrollTop = scrollTop;
    });
</script>