<!--begin::Header Admin-->
<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 w-100" style="padding-left: 0; padding-right: 0;">
    <div class="d-flex justify-content-between align-items-center w-100" style="padding-left: 1rem; padding-right: 1rem;">
        <div class="row w-100 align-items-center">
            <!-- Brand Admin -->
            <div class="col-auto">
                <a class="navbar-brand d-flex align-items-center text-decoration-none mb-0" href="panel?pg=facturar-login">
                    <div class="bg-primary rounded-circle p-2 me-3">
                        <i class="bi bi-speedometer2 text-white fs-5"></i>
                    </div>
                    <div>
                        <span class="fw-bold text-primary">Administración</span>
                        <small class="d-block text-muted">Facturación Electrónica</small>
                    </div>
                </a>
            </div>
            <!-- Mobile menu toggle -->
            <div class="col-auto ms-auto d-lg-none">
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdminNav"
                    aria-controls="navbarAdminNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list fs-4 text-dark"></i>
                </button>
            </div>

            <!-- Admin Navigation -->
            <div class="col-12 col-lg">
                <div class="collapse navbar-collapse" id="navbarAdminNav">
                    <div class="row w-100 align-items-center">
                        <!-- Left side admin navigation -->
                        <div class="col-12 col-lg-auto">
                            <ul class="navbar-nav flex-row flex-lg-row justify-content-center justify-content-lg-start mb-2 mb-lg-0">
                                <li class="nav-item mx-1">
                                    <a class="nav-link d-flex align-items-center rounded-pill px-3 py-2 <?php echo ($pagePath == 'inicio-admin' || $pagePath == '') ? 'bg-primary text-white fw-bold' : 'text-dark'; ?>" href="panel?pg=inicio-admin">
                                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item dropdown mx-1">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center rounded-pill px-3 py-2 <?php echo (strpos($pagePath, 'sucursal') !== false) ? 'bg-primary text-white fw-bold' : 'text-dark'; ?>" href="#" id="sucursalesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-building me-2"></i> Sucursales
                                    </a>
                                    <ul class="dropdown-menu shadow border-0 rounded-3" aria-labelledby="sucursalesDropdown">
                                        <li><a class="dropdown-item rounded-2 mx-2" href="panel?pg=gestion-sucursales">
                                                <i class="bi bi-list-ul me-2 text-primary"></i> Gestión de Sucursales
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="panel?pg=nueva-sucursal-admin">
                                                <i class="bi bi-plus-circle me-2 text-success"></i> Nueva Sucursal
                                            </a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown mx-1">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center rounded-pill px-3 py-2 <?php echo (strpos($pagePath, 'ticket') !== false || strpos($pagePath, 'factura') !== false) ? 'bg-primary text-white fw-bold' : 'text-dark'; ?>" href="#" id="facturacionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-receipt-cutoff me-2"></i> Facturación
                                    </a>
                                    <ul class="dropdown-menu shadow border-0 rounded-3" aria-labelledby="facturacionDropdown">
                                        <li><a class="dropdown-item rounded-2 mx-2" href="panel?pg=tickets-por-facturar">
                                                <i class="bi bi-clock me-2 text-warning"></i> Tickets Pendientes
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="panel?pg=facturas-generadas-admin">
                                                <i class="bi bi-file-check me-2 text-success"></i> Facturas Generadas
                                            </a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown mx-1">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center rounded-pill px-3 py-2 <?php echo (strpos($pagePath, 'constancia') !== false || strpos($pagePath, 'sello') !== false) ? 'bg-primary text-white fw-bold' : 'text-dark'; ?>" href="#" id="documentosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-file-earmark-lock me-2"></i> Documentos
                                    </a>
                                    <ul class="dropdown-menu shadow border-0 rounded-3" aria-labelledby="documentosDropdown">
                                        <li><a class="dropdown-item rounded-2 mx-2" href="panel?pg=constancias-admin">
                                                <i class="bi bi-file-text me-2 text-info"></i> Constancias Fiscales
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="panel?pg=sellos-admin">
                                                <i class="bi bi-shield-check me-2 text-primary"></i> Sellos Digitales
                                            </a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                        <!-- Right side admin navigation -->
                        <div class="col-12 col-lg-auto ms-lg-auto">
                            <ul class="navbar-nav flex-row justify-content-center justify-content-lg-end">
                                <!-- Admin Notifications -->
                                <li class="nav-item dropdown mx-1">
                                    <a class="nav-link position-relative rounded-circle p-2" href="#" id="adminNotificationDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-bell fs-5 text-primary"></i>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            5
                                            <span class="visually-hidden">alertas administrativas</span>
                                        </span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="adminNotificationDropdown">
                                        <li>
                                            <h6 class="dropdown-header text-primary fw-bold">
                                                <i class="bi bi-shield-exclamation me-2"></i>Alertas Administrativas
                                            </h6>
                                        </li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-exclamation-triangle text-warning"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <small class="fw-semibold">23 tickets sin procesar</small>
                                                        <small class="d-block text-muted">Requieren atención urgente</small>
                                                    </div>
                                                </div>
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-file-earmark-x text-danger"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <small class="fw-semibold">Certificado próximo a vencer</small>
                                                        <small class="d-block text-muted">Sucursal Norte - 5 días</small>
                                                    </div>
                                                </div>
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-info-circle text-info"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <small class="fw-semibold">Backup completado</small>
                                                        <small class="d-block text-muted">Base de datos - 02:00 AM</small>
                                                    </div>
                                                </div>
                                            </a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-center text-primary fw-semibold" href="#">Ver todas las alertas</a></li>
                                    </ul>
                                </li>

                                <!-- Quick Actions -->
                                <li class="nav-item dropdown mx-1">
                                    <a class="nav-link rounded-circle p-2" href="#" id="quickActionsDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-lightning fs-5 text-primary"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="quickActionsDropdown">
                                        <li>
                                            <h6 class="dropdown-header text-primary fw-bold">
                                                <i class="bi bi-lightning me-2"></i>Acciones Rápidas
                                            </h6>
                                        </li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="panel?pg=nueva-sucursal-admin">
                                                <i class="bi bi-plus-circle me-2 text-success"></i> Nueva Sucursal
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="panel?pg=tickets-por-facturar">
                                                <i class="bi bi-clock me-2 text-warning"></i> Procesar Tickets
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="generarReporte()">
                                                <i class="bi bi-graph-up me-2 text-info"></i> Generar Reporte
                                            </a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="backupSistema()">
                                                <i class="bi bi-download me-2 text-primary"></i> Backup Sistema
                                            </a></li>
                                    </ul>
                                </li>

                                <!-- Fullscreen toggle -->
                                <li class="nav-item mx-1">
                                    <a class="nav-link rounded-circle p-2" href="#" onclick="toggleFullscreen()">
                                        <i class="bi bi-arrows-fullscreen fs-5 text-primary" id="fullscreenIcon"></i>
                                    </a>
                                </li>

                                <!-- Admin User dropdown -->
                                <li class="nav-item dropdown mx-1">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center px-3 py-2" href="#" id="adminUserDropdown"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="bg-primary rounded-circle p-2 me-2">
                                            <i class="bi bi-person-gear text-white fs-6"></i>
                                        </div>
                                        <div class="text-start d-none d-md-block">
                                            <span class="fw-bold text-primary d-block" style="font-size: 0.85rem;">
                                                <?php echo !empty($_SESSION['USR_NAME']) ? $_SESSION['USR_NAME'] : 'Admin'; ?>
                                            </span>
                                            <small class="text-muted">Administrador</small>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="adminUserDropdown">
                                        <li>
                                            <h6 class="dropdown-header text-primary fw-bold">
                                                <i class="bi bi-gear me-2"></i>Panel de Control
                                            </h6>
                                        </li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarPerfilAdmin()">
                                                <i class="bi bi-person-gear me-2 text-primary"></i> Mi Perfil Admin
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarConfiguracionSistema()">
                                                <i class="bi bi-sliders me-2 text-secondary"></i> Configuración Sistema
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarUsuariosSistema()">
                                                <i class="bi bi-people me-2 text-info"></i> Gestión de Usuarios
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarReportes()">
                                                <i class="bi bi-graph-up me-2 text-success"></i> Reportes y Analytics
                                            </a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarSoporteTecnico()">
                                                <i class="bi bi-tools me-2 text-warning"></i> Soporte Técnico
                                            </a></li>
                                        <li><a class="dropdown-item rounded-2 mx-2" href="#" onclick="mostrarLogs()">
                                                <i class="bi bi-journal-text me-2 text-info"></i> Ver Logs del Sistema
                                            </a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item rounded-2 mx-2 text-danger" href="#" onclick="cerrarSesionAdmin()">
                                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión Admin
                                            </a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<!--end::Header Admin-->

<nav aria-label="breadcrumb" class="bg-light border-top" style="padding-left: 1rem;">
    <div class="w-100">
        <div class="row align-items-center">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="panel?pg=inicio-admin" class="text-decoration-none text-primary fw-semibold">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">
                        <?php
                        $pageTitle = ucfirst(str_replace(['-', '_'], ' ', $pagePath));
                        echo $pageTitle === 'Inicio admin' ? 'Panel Principal' : $pageTitle;
                        ?>
                    </li>
                </ol>
            <div class="col-auto ms-auto">
                <div class="d-flex align-items-center">
                    <small class="text-muted me-3">
                        <i class="bi bi-clock me-1"></i>
                        <span id="relojAdmin"><?php echo date('H:i:s'); ?></span>
                    </small>
                    <small class="text-muted">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?php echo date('d/m/Y'); ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Reloj en tiempo real para el panel admin
    function actualizarRelojAdmin() {
        const ahora = new Date();
        const horas = String(ahora.getHours()).padStart(2, '0');
        const minutos = String(ahora.getMinutes()).padStart(2, '0');
        const segundos = String(ahora.getSeconds()).padStart(2, '0');
        const tiempoFormateado = `${horas}:${minutos}:${segundos}`;

        const reloj = document.getElementById('relojAdmin');
        if (reloj) {
            reloj.textContent = tiempoFormateado;
        }
    }

    // Actualizar reloj cada segundo
    setInterval(actualizarRelojAdmin, 1000);
    actualizarRelojAdmin(); // Ejecutar inmediatamente

    // Función para toggle fullscreen mejorada
    function toggleFullscreen() {
        const icon = document.getElementById('fullscreenIcon');
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().then(() => {
                icon.className = 'bi bi-fullscreen-exit fs-5 text-primary';
            });
        } else {
            document.exitFullscreen().then(() => {
                icon.className = 'bi bi-arrows-fullscreen fs-5 text-primary';
            });
        }
    }

    // Funciones específicas de administración
    function generarReporte() {
        // Aquí iría la lógica para generar reportes
        alert('Generando reporte del sistema...');
    }

    function backupSistema() {
        if (confirm('¿Deseas crear un backup completo del sistema?')) {
            alert('Iniciando proceso de backup...');
        }
    }

    function mostrarPerfilAdmin() {
        window.location.href = 'panel?pg=perfil-admin';
    }

    function mostrarConfiguracionSistema() {
        window.location.href = 'panel?pg=configuracion-sistema';
    }

    function mostrarUsuariosSistema() {
        window.location.href = 'panel?pg=gestion-usuarios';
    }

    function mostrarReportes() {
        window.location.href = 'panel?pg=reportes-analytics';
    }

    function mostrarSoporteTecnico() {
        window.location.href = 'panel?pg=soporte-tecnico';
    }

    function mostrarLogs() {
        window.location.href = 'panel?pg=logs-sistema';
    }

    function cerrarSesionAdmin() {
        if (confirm('¿Estás seguro de que deseas cerrar la sesión de administrador?')) {
            // Aquí iría la lógica de logout admin
            window.location.href = 'panel?pg=admin-login';
        }
    }

    // Navbar auto-hide mejorado para admin
    let lastScrollTop = 0;
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop && scrollTop > 200) {
            // Scrolling down - hide navbar
            navbar.style.transform = 'translateY(-100%)';
            navbar.style.transition = 'transform 0.3s ease-in-out';
        } else {
            // Scrolling up - show navbar
            navbar.style.transform = 'translateY(0)';
            navbar.style.transition = 'transform 0.3s ease-in-out';
        }

        lastScrollTop = scrollTop;
    });

    // Notificaciones automáticas admin
    document.addEventListener('DOMContentLoaded', function() {
        // Simulación de verificación de estado del sistema
        setTimeout(function() {
            const statusIcon = document.querySelector('.bi-activity');
            if (statusIcon) {
                // Cambiar color según el estado del sistema
                setInterval(function() {
                    // Aquí se podría hacer una consulta AJAX al servidor
                    console.log('Verificando estado del sistema...');
                }, 60000); // Cada minuto
            }
        }, 1000);
    });
</script>