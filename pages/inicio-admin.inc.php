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
        <!-- Panel de Gestión Rápida -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
                            Gestión Rápida
                        </h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Sucursales -->
                        <div class="col-md-6">
                            <div class="card bg-primary bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-building display-4 text-primary mb-3"></i>
                                    <h6 class="fw-bold text-primary mb-2">Gestión de Sucursales</h6>
                                    <p class="text-muted mb-3">Administrar ubicaciones y configuraciones</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=sucursales-admin" class="btn btn-primary btn-sm">
                                            <i class="bi bi-list-ul me-2"></i>Ver Sucursales
                                        </a>
                                        <a href="panel?pg=nueva-sucursal-admin" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-plus-circle me-2"></i>Nueva Sucursal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Certificados y Sellos -->
                        <div class="col-md-6">
                            <div class="card bg-warning bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-award display-4 text-warning mb-3"></i>
                                    <h6 class="fw-bold text-warning mb-2">Certificados Digitales</h6>
                                    <p class="text-muted mb-3">Gestionar constancias y sellos</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=constancias-admin" class="btn btn-warning btn-sm">
                                            <i class="bi bi-file-earmark-pdf me-2"></i>Constancias
                                        </a>
                                        <a href="panel?pg=sellos-admin" class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-shield-check me-2"></i>Sellos Digitales
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
                                    <h6 class="fw-bold text-success mb-2">Control de Facturación</h6>
                                    <p class="text-muted mb-3">Tickets y facturas generadas</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=tickets-pendientes-admin" class="btn btn-success btn-sm">
                                            <i class="bi bi-clock me-2"></i>Tickets Pendientes
                                        </a>
                                        <a href="panel?pg=facturas-generadas-admin" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-file-check me-2"></i>Ver Facturas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reportes -->
                        <div class="col-md-6">
                            <div class="card bg-info bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-graph-up display-4 text-info mb-3"></i>
                                    <h6 class="fw-bold text-info mb-2">Reportes y Análisis</h6>
                                    <p class="text-muted mb-3">Estadísticas y reportes detallados</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=reportes-admin" class="btn btn-info btn-sm">
                                            <i class="bi bi-bar-chart me-2"></i>Ver Reportes
                                        </a>
                                        <a href="panel?pg=estadisticas-admin" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-pie-chart me-2"></i>Estadísticas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Actividad Reciente y Alertas -->
        <div class="col-lg-4">
            <!-- Alertas del Sistema -->
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h6 class="fw-bold text-danger mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Alertas Importantes
                    </h6>
                </div>
                <div class="card-body p-3">
                    <!-- Alerta Certificado -->
                    <div class="alert alert-warning border-0 rounded-3 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-clock-fill text-warning me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Certificado por vencer</h6>
                                <small>Sucursal Centro - Vence en 15 días</small>
                            </div>
                        </div>
                    </div>

                    <!-- Alerta Tickets -->
                    <div class="alert alert-info border-0 rounded-3 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-receipt text-info me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Tickets acumulados</h6>
                                <small>23 tickets pendientes de procesar</small>
                            </div>
                        </div>
                    </div>

                    <!-- Alerta Sistema -->
                    <div class="alert alert-success border-0 rounded-3 mb-0">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Sistema actualizado</h6>
                                <small>Última actualización exitosa - v2.1.0</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-activity me-2"></i>
                        Actividad Reciente
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="timeline">
                        <!-- Actividad 1 -->
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-success rounded-circle p-2 me-3">
                                <i class="bi bi-check text-white small"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 small">Factura generada</h6>
                                <small class="text-muted">Sucursal Norte - $1,250.00</small>
                                <br><small class="text-success">Hace 5 min</small>
                            </div>
                        </div>

                        <!-- Actividad 2 -->
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-primary rounded-circle p-2 me-3">
                                <i class="bi bi-person-plus text-white small"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 small">Nueva sucursal registrada</h6>
                                <small class="text-muted">Sucursal Sur - Plaza Central</small>
                                <br><small class="text-primary">Hace 1 hora</small>
                            </div>
                        </div>

                        <!-- Actividad 3 -->
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-warning rounded-circle p-2 me-3">
                                <i class="bi bi-upload text-dark small"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 small">Constancia actualizada</h6>
                                <small class="text-muted">Sucursal Centro - CSF renovada</small>
                                <br><small class="text-warning">Hace 3 horas</small>
                            </div>
                        </div>

                        <!-- Actividad 4 -->
                        <div class="d-flex align-items-start">
                            <div class="bg-info rounded-circle p-2 me-3">
                                <i class="bi bi-gear text-white small"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 small">Configuración modificada</h6>
                                <small class="text-muted">Parámetros de facturación</small>
                                <br><small class="text-info">Hace 5 horas</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="panel?pg=logs-admin" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list me-1"></i>Ver todo el historial
                        </a>
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