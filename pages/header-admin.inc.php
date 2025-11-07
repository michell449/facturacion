<!--begin::Admin Header-->
<nav class="navbar navbar-expand-lg bg-dark shadow-lg py-3">
    <div class="container-fluid">
        <!-- Admin Brand -->
        <a class="navbar-brand d-flex align-items-center text-decoration-none" href="panel?pg=inicio-admin">
            <div class="bg-warning rounded-circle p-2 me-3">
                <i class="bi bi-shield-check text-dark fs-5"></i>
            </div>
            <div>
                <span class="fw-bold text-white">Panel Administrativo</span>
                <br>
                <small class="text-warning">Sistema de Control</small>
            </div>
        </a>

        <!-- Mobile menu toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
            aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-4 text-white"></i>
        </button>

        <!-- Admin Navbar content -->
        <div class="collapse navbar-collapse" id="adminNavbar">
            <!-- Left side navigation -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item mx-1">
                    <a class="nav-link d-flex align-items-center rounded-pill px-3 py-2 <?php echo ($pagePath == 'inicio-admin') ? 'bg-warning text-dark fw-bold' : 'text-white'; ?>" href="panel?pg=inicio-admin">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown mx-1">
                    <a class="nav-link dropdown-toggle d-flex align-items-center rounded-pill px-3 py-2 text-white" href="#" id="sucursalesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-building me-2"></i> Sucursales
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark border-0 rounded-3 shadow-lg" aria-labelledby="sucursalesDropdown">
                        <li><a class="dropdown-item rounded-2" href="panel?pg=sucursales-admin"><i class="bi bi-list-ul me-2"></i>Ver Todas</a></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=nueva-sucursal-admin"><i class="bi bi-plus-circle me-2"></i>Nueva Sucursal</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=constancias-admin"><i class="bi bi-file-earmark-pdf me-2"></i>Constancias Fiscales</a></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=sellos-admin"><i class="bi bi-award me-2"></i>Sellos Digitales</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown mx-1">
                    <a class="nav-link dropdown-toggle d-flex align-items-center rounded-pill px-3 py-2 text-white" href="#" id="facturacionDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-receipt-cutoff me-2"></i> Facturación
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark border-0 rounded-3 shadow-lg" aria-labelledby="facturacionDropdown">
                        <li><a class="dropdown-item rounded-2" href="panel?pg=tickets-pendientes-admin"><i class="bi bi-clock-history me-2"></i>Tickets Pendientes</a></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=facturas-generadas-admin"><i class="bi bi-file-check me-2"></i>Facturas Generadas</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=reportes-admin"><i class="bi bi-graph-up me-2"></i>Reportes</a></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=estadisticas-admin"><i class="bi bi-pie-chart me-2"></i>Estadísticas</a></li>
                    </ul>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link d-flex align-items-center rounded-pill px-3 py-2 <?php echo ($pagePath == 'usuarios-admin') ? 'bg-warning text-dark fw-bold' : 'text-white'; ?>" href="panel?pg=usuarios-admin">
                        <i class="bi bi-people me-2"></i> Usuarios
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link d-flex align-items-center rounded-pill px-3 py-2 <?php echo ($pagePath == 'configuracion-admin') ? 'bg-warning text-dark fw-bold' : 'text-white'; ?>" href="panel?pg=configuracion-admin">
                        <i class="bi bi-gear me-2"></i> Configuración
                    </a>
                </li>
            </ul>

            <!-- Right side navigation -->
            <ul class="navbar-nav">
                <!-- System Status -->
                <li class="nav-item me-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success rounded-pill d-flex align-items-center">
                            <i class="bi bi-check-circle me-1"></i>Sistema Activo
                        </span>
                    </div>
                </li>
                
                <!-- Admin Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative rounded-circle p-2 bg-warning bg-opacity-20" href="#" id="adminNotificationDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-5 text-warning"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            5
                            <span class="visually-hidden">alertas administrativas</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow-lg border-0 rounded-3" aria-labelledby="adminNotificationDropdown" style="min-width: 350px;">
                        <li>
                            <h6 class="dropdown-header text-warning fw-bold">
                                <i class="bi bi-shield-exclamation me-2"></i>Alertas del Sistema
                            </h6>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-3 rounded-2" href="#">
                                <div class="d-flex align-items-start">
                                    <div class="bg-danger rounded-circle p-2 me-3">
                                        <i class="bi bi-exclamation-triangle text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-white">Certificado por vencer</h6>
                                        <small class="text-muted">Sucursal Centro - Vence en 15 días</small>
                                        <br><small class="text-warning">Hace 2 horas</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-3 rounded-2" href="#">
                                <div class="d-flex align-items-start">
                                    <div class="bg-warning rounded-circle p-2 me-3">
                                        <i class="bi bi-clock text-dark"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-white">Tickets pendientes</h6>
                                        <small class="text-muted">23 tickets sin procesar</small>
                                        <br><small class="text-warning">Hace 1 hora</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="text-center">
                            <a class="dropdown-item text-warning fw-semibold" href="panel?pg=notificaciones-admin">
                                <i class="bi bi-arrow-right me-1"></i> Ver todas las alertas
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Admin Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center text-decoration-none" href="#" id="adminProfileDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-warning rounded-circle p-2 me-2">
                            <i class="bi bi-person-gear text-dark fs-6"></i>
                        </div>
                        <div class="text-start d-none d-lg-block">
                            <small class="text-warning fw-semibold">Administrador</small>
                            <br>
                            <small class="text-white opacity-75"><?php echo $_SESSION['USR_NAME'] ?: 'Admin System'; ?></small>
                        </div>
                        <i class="bi bi-chevron-down text-warning ms-2"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow-lg border-0 rounded-3" aria-labelledby="adminProfileDropdown">
                        <li>
                            <div class="dropdown-header text-center">
                                <div class="bg-warning rounded-circle p-3 mx-auto mb-2 d-inline-flex">
                                    <i class="bi bi-person-gear text-dark fs-4"></i>
                                </div>
                                <h6 class="text-warning fw-bold mb-0">Panel Administrativo</h6>
                                <small class="text-muted">Control Total del Sistema</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=perfil-admin"><i class="bi bi-person-circle me-2"></i>Mi Perfil</a></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=configuracion-admin"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=logs-admin"><i class="bi bi-file-text me-2"></i>Logs del Sistema</a></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=backup-admin"><i class="bi bi-cloud-arrow-up me-2"></i>Respaldos</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item rounded-2" href="panel?pg=facturar-login"><i class="bi bi-box-arrow-left me-2"></i>Vista Cliente</a></li>
                        <li><a class="dropdown-item text-danger rounded-2" href="logout.php"><i class="bi bi-power me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Admin Breadcrumb -->
<div class="bg-light border-bottom py-2">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="panel?pg=inicio-admin" class="text-decoration-none text-dark">
                        <i class="bi bi-house me-1"></i>Dashboard
                    </a>
                </li>
                <?php
                $breadcrumbs = [
                    'sucursales-admin' => 'Sucursales',
                    'nueva-sucursal-admin' => 'Nueva Sucursal',
                    'constancias-admin' => 'Constancias Fiscales',
                    'sellos-admin' => 'Sellos Digitales',
                    'tickets-pendientes-admin' => 'Tickets Pendientes',
                    'facturas-generadas-admin' => 'Facturas Generadas',
                    'reportes-admin' => 'Reportes',
                    'estadisticas-admin' => 'Estadísticas',
                    'usuarios-admin' => 'Gestión de Usuarios',
                    'configuracion-admin' => 'Configuración del Sistema'
                ];
                
                if (isset($breadcrumbs[$pagePath])) {
                    echo '<li class="breadcrumb-item active" aria-current="page">';
                    echo '<i class="bi bi-chevron-right me-1"></i>' . $breadcrumbs[$pagePath];
                    echo '</li>';
                }
                ?>
            </ol>
        </nav>
    </div>
</div>
<!--end::Admin Header-->