<?php
// pages/inicio.inc.php
$fecha_actual = date('d \d\e F \d\e Y');
$hora_actual = date('H:i');
?>
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                        <i class="bi bi-speedometer2 display-6"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-2">¡Bienvenido al Panel de Administración!</h1>
                        <p class="lead mb-0 opacity-90">Controla y gestiona completamente el sistema de facturación</p>

                        <small class="opacity-75">
                            <i class="bi bi-calendar3 me-2"></i><?php echo $fecha_actual; ?>
                            <i class="bi bi-clock ms-3 me-2"></i><?php echo $hora_actual; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Main Dashboard Content -->
<div class="container py-5">
    <!-- Statistics Cards -->

    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100 stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted fw-semibold mb-2">Tickets Pendientes</h6>
                                <h2 class="fw-bold text-warning mb-0">23</h2>
                                <small class="text-success">
                                    <i class="bi bi-arrow-down me-1"></i>-12% vs ayer
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100 stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted fw-semibold mb-2">Facturas Hoy</h6>
                            <h2 class="fw-bold text-success mb-0">156</h2>
                            <small class="text-success">
                                <i class="bi bi-arrow-up me-1"></i>+18% vs ayer
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100 stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted fw-semibold mb-2">Sucursales Activas</h6>
                            <h2 class="fw-bold text-primary mb-0">8</h2>
                            <small class="text-primary">
                                <i class="bi bi-building me-1"></i>Todas operativas
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Panel de Gestión  -->
        <div class="col-lg-12">
            <div class="card shadow-lg border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold text-dark mb-3">
                            Gestión
                        </h5>
                    </div>
                </div>
                <div class="card-body p-4 ">
                    <div class="row g-4">
                        <!-- Sucursales -->
                        <div class="col-md-6">
                            <div class="card bg-primary bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-building display-4 text-primary mb-3"></i>
                                    <h6 class="fw-bold text-primary mb-2">Sucursales</h6>
                                    <p class="text-muted mb-3">Administrar ubicaciones y configuraciones</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=gestion-sucursales" class="btn btn-primary btn-sm">
                                            <i class="bi bi-list-ul me-2"></i>Ver Sucursales
                                        </a>
                                        <a href="panel?pg=nueva-sucursal-admin" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-plus-circle me-2"></i>Nueva Sucursal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Facturación -->
                        <div class="col-md-6">
                            <div class="card bg-success bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-receipt-cutoff display-4 text-success mb-3"></i>
                                    <h6 class="fw-bold text-success mb-2">Control de facturas</h6>
                                    <p class="text-muted mb-3">Tickets y facturas generadas</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=tickets" class="btn btn-success btn-sm">
                                            <i class="bi bi-clock me-2"></i>Tickets
                                        </a>
                                        <a href="panel?pg=facturas-generadas-admin" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-file-check me-2"></i>Ver Facturas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nueva sección de Configuración -->
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="card bg-warning bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-gear display-4 text-warning mb-3"></i>
                                    <h6 class="fw-bold text-warning mb-2">Configuración</h6>
                                    <p class="text-muted mb-3">Personaliza el sistema de facturación</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=config-facturas" class="btn btn-warning btn-sm">
                                            <i class="bi bi-file-earmark-text me-2"></i>Formato de Facturas
                                        </a>
                                        <a href="panel?pg=config-correo" class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-envelope-at me-2"></i>Correo de Envío
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-info bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-sliders display-4 text-info mb-3"></i>
                                    <h6 class="fw-bold text-info mb-2">Configuraciones Avanzadas</h6>
                                    <p class="text-muted mb-3">Folios, series y formato de productos</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=config-folios" class="btn btn-info btn-sm">
                                            <i class="bi bi-hash me-2"></i>Folios y Series
                                        </a>
                                        <a href="panel?pg=config-productos" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-boxes me-2"></i>Agrupación de Productos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Actualización automática de estadísticas cada 30 segundos
        setInterval(function() {
            // Aquí iría la lógica para actualizar las estadísticas via AJAX
            const timestamp = new Date().toLocaleString('es-ES');
            console.log('Actualizando estadísticas del dashboard...', timestamp);
        }, 30000);

        // Efecto de carga para las tarjetas de estadísticas
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.transform = 'translateY(0)';
                card.style.opacity = '1';
            }, index * 100);
        });
    });
</script>