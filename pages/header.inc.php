<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 w-100" style="padding-left: 0; padding-right: 0;">
    <div class="d-flex justify-content-between align-items-center w-100" style="padding-left: 1rem; padding-right: 1rem;">
        <a class="navbar-brand d-flex align-items-center text-decoration-none" href="panel?pg=facturar-login">
            <span class="fw-bold text-primary">Facturación Electrónica</span>
        </a>

        <!-- Mobile menu toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <!-- Left side navigation -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item mx-1">
                    <a class="nav-link d-flex align-items-center rounded-pill px-3 py-2 <?php echo ($pagePath == '' || $pagePath == 'inicio') ? 'bg-primary text-white fw-bold' : 'text-dark'; ?>" href="panel?pg=inicio">
                        <i class="bi bi-house me-2"></i> Inicio
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link d-flex align-items-center rounded-pill px-3 py-2 <?php echo ($pagePath == 'facturar') ? 'bg-primary text-white fw-bold' : 'text-dark'; ?>" href="panel?pg=facturar">
                        <i class="bi bi-receipt-cutoff me-2"></i> Facturar mis Compras
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link d-flex align-items-center rounded-pill px-3 py-2 <?php echo ($pagePath == 'historial') ? 'bg-primary text-white fw-bold' : 'text-dark'; ?>" href="panel?pg=historial">
                        <i class="bi bi-clock-history me-2"></i> Historial
                    </a>
                </li>
            </ul>

            <!-- Right side navigation -->
            <ul class="navbar-nav">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative rounded-circle p-2" href="#" id="notificationDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false" onclick="cargarNotificacionesUsuario()">
                        <i class="bi bi-bell fs-5 text-primary"></i>
                        <span id="badgeNotificacionesUsuario" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;">
                            <span class="visually-hidden">notificaciones</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="notificationDropdown" style="min-width: 320px;">
                        <li>
                            <h6 class="dropdown-header text-primary fw-bold">Notificaciones</h6>
                        </li>
                        <li id="listaNotificacionesUsuario">
                            <div class="px-3 py-2 text-muted">Sin notificaciones recientes</div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center text-primary fw-semibold" href="panel?pg=historial">Ver todas</a></li>
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
                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarFacturas()">
                                <i class="bi bi-file-earmark-text me-2 text-info"></i> Mis Facturas
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
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

<nav aria-label="breadcrumb" class="bg-light border-top py-2" style="padding-left: 1rem;">
    <div class="w-100">
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

    async function cerrarSesion() {
        const result = await Swal.fire({
            title: '¿Cerrar sesión?',
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Continuar'
        });

        if (result.isConfirmed) {
            try {
                Swal.fire({
                    title: 'Cerrando sesión...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const response = await fetch('core/logout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Cache-Control': 'no-cache'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Error del servidor: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sesión cerrada',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect || 'index.php?pg=facturar-login';
                    });
                } else {
                    throw new Error(data.message || 'Error al cerrar sesión');
                }
            } catch (error) {
                console.error('Error al cerrar sesión:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al cerrar sesión. Inténtalo de nuevo.'
                });
            }
        }
    } 
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

    // Notificaciones de usuario (respuestas de cancelación)
    async function cargarNotificacionesUsuario() {
        try {
            const res = await fetch('core/obtener-notificaciones-usuario.php', { cache: 'no-store' });
            const data = await res.json();

            const badge = document.getElementById('badgeNotificacionesUsuario');
            const lista = document.getElementById('listaNotificacionesUsuario');

            if (!data.success) {
                badge.style.display = 'none';
                lista.innerHTML = '<div class="px-3 py-2 text-danger">No se pudieron cargar las notificaciones</div>';
                return;
            }

            const total = data.total || 0;
            if (total > 0) {
                badge.textContent = total > 99 ? '99+' : total;
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }

            if (Array.isArray(data.items) && data.items.length > 0) {
                lista.innerHTML = data.items.map(item => {
                    const iconClass = item.estado === 'aprobada' ? 'bi-check-circle text-success' : 'bi-x-circle text-danger';
                    const titulo = item.estado === 'aprobada' ? 'Cancelación aprobada' : 'Cancelación rechazada';
                    return `
                        <a class="dropdown-item rounded-2 mx-2" href="panel?pg=historial">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi ${iconClass}"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <div class="d-flex justify-content-between">
                                        <small class="fw-semibold text-primary">${titulo}</small>
                                        <small class="text-muted">${item.fecha_respuesta}</small>
                                    </div>
                                    <small class="text-muted">Factura ${item.folio} · ${item.moneda} ${item.total}</small><br>
                                    <small class="">${item.respuesta_admin || ''}</small>
                                </div>
                            </div>
                        </a>
                    `;
                }).join('');
            } else {
                lista.innerHTML = '<div class="px-3 py-2 text-muted">Sin notificaciones recientes</div>';
            }
        } catch (e) {
            const badge = document.getElementById('badgeNotificacionesUsuario');
            const lista = document.getElementById('listaNotificacionesUsuario');
            badge.style.display = 'none';
            lista.innerHTML = '<div class="px-3 py-2 text-danger">Error al cargar las notificaciones</div>';
            console.error('Error al cargar notificaciones:', e);
        }
    }

    async function actualizarContadorNotificacionesUsuario() {
        try {
            const res = await fetch('core/obtener-notificaciones-usuario.php', { cache: 'no-store' });
            const data = await res.json();
            const badge = document.getElementById('badgeNotificacionesUsuario');
            const total = data && data.success ? (data.total || 0) : 0;
            if (total > 0) {
                badge.textContent = total > 99 ? '99+' : total;
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }
        } catch (e) {
            const badge = document.getElementById('badgeNotificacionesUsuario');
            badge.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        actualizarContadorNotificacionesUsuario();
        setInterval(actualizarContadorNotificacionesUsuario, 300000); // 5 minutos
    });
</script>