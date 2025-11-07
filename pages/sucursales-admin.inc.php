<!-- Gestión de Sucursales -->
<div class="content-wrapper bg-light loaded">
    <!-- Header de Sucursales -->
    <div class="bg-primary text-white py-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                            <i class="bi bi-building display-6 text-white"></i>
                        </div>
                        <div>
                            <h1 class="fw-bold mb-2">Gestión de Sucursales</h1>
                            <p class="lead mb-0 opacity-90">Administra todas las ubicaciones de tu empresa</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="panel?pg=nueva-sucursal-admin" class="btn btn-warning btn-lg fw-semibold">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Sucursal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <!-- Filtros y Búsqueda -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 bg-light" placeholder="Buscar sucursal..." id="searchSucursales">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select border-0 bg-light" id="filterEstado">
                                    <option value="">Todos los estados</option>
                                    <option value="activa">Activas</option>
                                    <option value="inactiva">Inactivas</option>
                                    <option value="mantenimiento">En Mantenimiento</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select border-0 bg-light" id="filterCiudad">
                                    <option value="">Todas las ciudades</option>
                                    <option value="mexico">Ciudad de México</option>
                                    <option value="guadalajara">Guadalajara</option>
                                    <option value="monterrey">Monterrey</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-primary w-100" type="button">
                                    <i class="bi bi-funnel me-1"></i>Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 bg-info bg-opacity-10">
                    <div class="card-body p-3 text-center">
                        <h5 class="text-info mb-1">8 Sucursales</h5>
                        <small class="text-muted">7 Activas • 1 Mantenimiento</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Sucursales -->
        <div class="row g-4">
            <!-- Sucursal 1 - Centro -->
            <div class="col-xl-4 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-20 rounded-circle p-2 me-3">
                                    <i class="bi bi-building text-success fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Sucursal Centro</h6>
                                    <small class="text-muted">SUC-001</small>
                                </div>
                            </div>
                            <span class="badge bg-success rounded-pill">Activa</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-2"></i>Ubicación
                            </h6>
                            <p class="mb-0">Av. Reforma 123, Col. Centro</p>
                            <small class="text-muted">Ciudad de México, CDMX, 06000</small>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded-3">
                                    <h6 class="text-primary mb-0">45</h6>
                                    <small class="text-muted">Tickets Hoy</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded-3">
                                    <h6 class="text-success mb-0">$12,500</h6>
                                    <small class="text-muted">Facturado</small>
                                </div>
                            </div>
                        </div>

                        <!-- Estados de Certificados -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-shield-check me-2"></i>Certificados
                            </h6>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <small>Constancia Fiscal</small>
                                <span class="badge bg-warning text-dark">Vence en 15 días</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small>Sello Digital</small>
                                <span class="badge bg-success">Vigente</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-upload me-1"></i>Docs
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sucursal 2 - Norte -->
            <div class="col-xl-4 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-20 rounded-circle p-2 me-3">
                                    <i class="bi bi-building text-success fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Sucursal Norte</h6>
                                    <small class="text-muted">SUC-002</small>
                                </div>
                            </div>
                            <span class="badge bg-success rounded-pill">Activa</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-2"></i>Ubicación
                            </h6>
                            <p class="mb-0">Blvd. Norte 456, Col. Lindavista</p>
                            <small class="text-muted">Ciudad de México, CDMX, 07300</small>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded-3">
                                    <h6 class="text-primary mb-0">32</h6>
                                    <small class="text-muted">Tickets Hoy</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded-3">
                                    <h6 class="text-success mb-0">$8,750</h6>
                                    <small class="text-muted">Facturado</small>
                                </div>
                            </div>
                        </div>

                        <!-- Estados de Certificados -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-shield-check me-2"></i>Certificados
                            </h6>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <small>Constancia Fiscal</small>
                                <span class="badge bg-success">Vigente</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small>Sello Digital</small>
                                <span class="badge bg-success">Vigente</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-upload me-1"></i>Docs
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sucursal 3 - Sur (En Mantenimiento) -->
            <div class="col-xl-4 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-20 rounded-circle p-2 me-3">
                                    <i class="bi bi-building text-warning fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Sucursal Sur</h6>
                                    <small class="text-muted">SUC-003</small>
                                </div>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill">Mantenimiento</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-2"></i>Ubicación
                            </h6>
                            <p class="mb-0">Av. Sur 789, Col. Del Valle</p>
                            <small class="text-muted">Ciudad de México, CDMX, 03100</small>
                        </div>

                        <div class="alert alert-warning border-0 rounded-3 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <small>Sucursal en mantenimiento programado</small>
                            </div>
                        </div>

                        <!-- Estados de Certificados -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-shield-check me-2"></i>Certificados
                            </h6>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <small>Constancia Fiscal</small>
                                <span class="badge bg-success">Vigente</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small>Sello Digital</small>
                                <span class="badge bg-success">Vigente</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" disabled>
                                    <i class="bi bi-gear me-1"></i>Mantenimiento
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-play me-1"></i>Activar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Más sucursales... -->
            <div class="col-xl-4 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 h-100 border-3 border-primary border-dashed">
                    <div class="card-body p-5 text-center">
                        <i class="bi bi-plus-circle display-1 text-primary mb-3 opacity-50"></i>
                        <h5 class="text-primary fw-bold mb-2">Agregar Nueva Sucursal</h5>
                        <p class="text-muted mb-4">Expande tu negocio con una nueva ubicación</p>
                        <a href="panel?pg=nueva-sucursal-admin" class="btn btn-primary">
                            <i class="bi bi-building me-2"></i>Crear Sucursal
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen de Estados -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-bar-chart me-2"></i>Resumen General de Sucursales
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Gráfico de Estados -->
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Estado de Sucursales</h6>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-success rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                                    <span class="flex-grow-1">Activas</span>
                                    <span class="fw-bold text-success">7</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-warning rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                                    <span class="flex-grow-1">En Mantenimiento</span>
                                    <span class="fw-bold text-warning">1</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                                    <span class="flex-grow-1">Inactivas</span>
                                    <span class="fw-bold text-danger">0</span>
                                </div>
                            </div>

                            <!-- Certificados por Vencer -->
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Certificados por Vencer</h6>
                                <div class="alert alert-warning border-0 rounded-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle me-3"></i>
                                        <div>
                                            <h6 class="mb-1">1 Certificado requiere atención</h6>
                                            <small>Sucursal Centro - Vence en 15 días</small>
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función de búsqueda en tiempo real
        document.getElementById('searchSucursales').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.col-xl-4.col-lg-6');
            
            cards.forEach(card => {
                const sucursalName = card.querySelector('h6.fw-bold');
                if (sucursalName && sucursalName.textContent.toLowerCase().includes(searchTerm)) {
                    card.style.display = 'block';
                } else if (searchTerm === '') {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Filtros
        document.getElementById('filterEstado').addEventListener('change', function() {
            // Lógica de filtrado por estado
            console.log('Filtrar por estado:', this.value);
        });

        document.getElementById('filterCiudad').addEventListener('change', function() {
            // Lógica de filtrado por ciudad
            console.log('Filtrar por ciudad:', this.value);
        });
    });
</script>