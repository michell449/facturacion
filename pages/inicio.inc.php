<?php
// pages/inicio.inc.php

$facturas_exitosas = 0;
$facturas_pendientes = 0;
$total_facturado_mes = 0.0;
$fecha_actual = date('d \d\e F \d\e Y');
$hora_actual = date('H:i');
?>

<!-- Hero Dashboard Section -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                        <i class="bi bi-speedometer2 display-6"></i>
                    </div>
                    <div>
                        <h1 class="display-5 fw-bold mb-2">¡Bienvenido!</h1>
                        <p class="lead mb-0 opacity-90">sistema de facturación electrónica</p>
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
                        <div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 mb-3">
                                <i class="bi bi-file-earmark-check text-primary fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-primary mb-1"><?php echo $facturas_exitosas; ?></h3>
                            <p class="text-muted mb-0">Facturas Generadas</p>
                        </div>
                        <div class="text-end">
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 0%
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
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 mb-3">
                                <i class="bi bi-currency-dollar text-success fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-success mb-1">$<?php echo number_format($total_facturado_mes, 2); ?></h3>
                            <p class="text-muted mb-0">Facturado este Mes</p>
                        </div>
                        <div class="text-end">
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 0%
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
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 mb-3">
                                <i class="bi bi-clock-history text-warning fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-warning mb-1"><?php echo $facturas_pendientes; ?></h3>
                            <p class="text-muted mb-0">Pendientes</p>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">
                                <i class="bi bi-dash"></i> 0%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-4">
                Acciones
            </h3>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-lg h-100 action-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="bg-primary bg-opacity-10 rounded-4 p-4">
                                <i class="bi bi-receipt-cutoff text-primary display-6"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="fw-bold mb-2">Generar Nueva Factura</h4>
                            <p class="text-muted mb-3">Convierte tu ticket de compra en factura electrónica</p>
                            <a href="panel?pg=facturar" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i>
                                Crear Factura
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-lg h-100 action-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="bg-success bg-opacity-10 rounded-4 p-4">
                                <i class="bi bi-file-earmark-text text-success display-6"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="fw-bold mb-2">Ver Mi Historial</h4>
                            <p class="text-muted mb-3">Consulta, descarga y administra tus facturas</p>
                            <a href="panel?pg=historial" class="btn btn-success btn-lg">
                                <i class="bi bi-archive me-2"></i>
                                Ver Historial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- actividad reciente -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        Actividad Reciente
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Factura generada</h6>
                                <p class="text-muted mb-1">Ticket #123456789 - $1,250.00</p>
                                <small class="text-muted">Hace 2 horas</small>
                            </div>
                        </div>
                        <div class="timeline-item ">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Perfil actualizado</h6>
                                <p class="text-muted mb-1">Datos fiscales modificados</p>
                                <small class="text-muted">Ayer</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Factura generada</h6>
                                <p class="text-muted mb-1">Ticket #987654321 - $850.00</p>
                                <small class="text-muted">Hace 3 días</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="historial" class="btn btn-outline-primary">
                            Ver todo el historial
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        Mi Perfil
                    </h5>
                </div>
                <div class="card-body pt-0 text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill text-primary fs-1"></i>
                    </div>
                    <h5 class="fw-bold mb-1"></h5>
                    <p class="text-muted mb-3">Usuario Registrado</p>
                    
                    <div class="row text-center mb-4">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="fw-bold text-primary mb-0"><?php echo $facturas_exitosas; ?></h6>
                                <small class="text-muted">Facturas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="fw-bold text-success mb-0">98.5%</h6>
                            <small class="text-muted">Éxito</small>
                        </div>
                    </div>

                    <a href="perfil" class="btn btn-outline-primary w-100">
                        <i class="bi bi-gear me-2"></i>
                        Administrar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Help & Support Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3">¿Necesitas ayuda?</h5>
                    <p class="text-muted mb-4">Nuestro equipo de soporte está disponible para asistirte</p>
                    <div class="row g-3 justify-content-center">
                        <div class="col-auto">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bi bi-question-circle me-2"></i>
                                Centro de Ayuda
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="#" class="btn btn-outline-success">
                                <i class="bi bi-whatsapp me-2"></i>
                                WhatsApp
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="#" class="btn btn-outline-info">
                                <i class="bi bi-envelope me-2"></i>
                                Email Soporte
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>