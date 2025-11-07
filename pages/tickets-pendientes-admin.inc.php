<!-- Tickets Pendientes de Facturación -->
<div class="content-wrapper bg-light loaded">
    <!-- Header -->
    <div class="bg-warning text-dark py-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark bg-opacity-20 rounded-circle p-3 me-4">
                            <i class="bi bi-clock-history display-6 text-dark"></i>
                        </div>
                        <div>
                            <h1 class="fw-bold mb-2">Tickets Pendientes</h1>
                            <p class="lead mb-0 opacity-90">Gestiona todas las solicitudes de facturación pendientes</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="bg-dark bg-opacity-20 rounded-4 p-3">
                        <h3 class="fw-bold text-dark mb-1">23</h3>
                        <small>Tickets Pendientes</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <!-- Filtros y Controles -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 bg-light" 
                                           placeholder="Buscar ticket..." id="searchTickets">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select border-0 bg-light" id="filterSucursal">
                                    <option value="">Todas las sucursales</option>
                                    <option value="centro">Sucursal Centro</option>
                                    <option value="norte">Sucursal Norte</option>
                                    <option value="sur">Sucursal Sur</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select border-0 bg-light" id="filterPrioridad">
                                    <option value="">Todas las prioridades</option>
                                    <option value="alta">Alta</option>
                                    <option value="media">Media</option>
                                    <option value="baja">Baja</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-calendar text-muted"></i>
                                    </span>
                                    <input type="date" class="form-control border-0 bg-light" id="filterFecha">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-outline-primary" title="Procesar Seleccionados">
                                        <i class="bi bi-check-all"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" title="Exportar">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card border-0 bg-danger bg-opacity-10 text-center">
                            <div class="card-body p-3">
                                <h5 class="text-danger mb-0">5</h5>
                                <small class="text-muted">Urgentes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 bg-success bg-opacity-10 text-center">
                            <div class="card-body p-3">
                                <h5 class="text-success mb-0">18</h5>
                                <small class="text-muted">Normales</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Tickets -->
        <div class="row g-4">
            <!-- Ticket 1 - Prioridad Alta -->
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 border-start border-danger border-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="ticket1">
                                </div>
                                <span class="badge bg-danger rounded-pill mt-2">Alta</span>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h6 class="fw-bold text-primary mb-1">TKT-001234</h6>
                                    <small class="text-muted">Sucursal Centro</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6 class="mb-1">Cliente: Juan Pérez García</h6>
                                <small class="text-muted">RFC: PEGJ850123ABC</small>
                                <br>
                                <small class="text-primary">
                                    <i class="bi bi-envelope me-1"></i>juan.perez@email.com
                                </small>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h6 class="text-success mb-1">$1,250.00</h6>
                                    <small class="text-muted">Venta: V-789456</small>
                                    <br>
                                    <small class="text-warning">
                                        <i class="bi bi-clock me-1"></i>Vence hoy
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block">Fecha de Compra:</small>
                                <strong>05/11/2025</strong>
                                <br>
                                <small class="text-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>2 días restantes
                                </small>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="btn-group-vertical" role="group">
                                    <button class="btn btn-success btn-sm mb-2" title="Procesar Factura">
                                        <i class="bi bi-check-circle me-1"></i>Facturar
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm mb-2" title="Ver Detalles">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" title="Contactar Cliente">
                                        <i class="bi bi-chat me-1"></i>Contactar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket 2 - Prioridad Media -->
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 border-start border-warning border-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="ticket2">
                                </div>
                                <span class="badge bg-warning text-dark rounded-pill mt-2">Media</span>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h6 class="fw-bold text-primary mb-1">TKT-001235</h6>
                                    <small class="text-muted">Sucursal Norte</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6 class="mb-1">Cliente: María López Hernández</h6>
                                <small class="text-muted">RFC: LOHM920315XYZ</small>
                                <br>
                                <small class="text-primary">
                                    <i class="bi bi-envelope me-1"></i>maria.lopez@email.com
                                </small>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h6 class="text-success mb-1">$850.00</h6>
                                    <small class="text-muted">Venta: V-789457</small>
                                    <br>
                                    <small class="text-info">
                                        <i class="bi bi-calendar me-1"></i>Vence mañana
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block">Fecha de Compra:</small>
                                <strong>04/11/2025</strong>
                                <br>
                                <small class="text-warning">
                                    <i class="bi bi-clock me-1"></i>3 días restantes
                                </small>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="btn-group-vertical" role="group">
                                    <button class="btn btn-success btn-sm mb-2" title="Procesar Factura">
                                        <i class="bi bi-check-circle me-1"></i>Facturar
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm mb-2" title="Ver Detalles">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" title="Contactar Cliente">
                                        <i class="bi bi-chat me-1"></i>Contactar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket 3 - Prioridad Baja -->
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 border-start border-success border-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="ticket3">
                                </div>
                                <span class="badge bg-success rounded-pill mt-2">Baja</span>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h6 class="fw-bold text-primary mb-1">TKT-001236</h6>
                                    <small class="text-muted">Sucursal Centro</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6 class="mb-1">Cliente: Carlos Rodríguez Sánchez</h6>
                                <small class="text-muted">RFC: ROSC870612DEF</small>
                                <br>
                                <small class="text-primary">
                                    <i class="bi bi-envelope me-1"></i>carlos.rodriguez@email.com
                                </small>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h6 class="text-success mb-1">$2,100.00</h6>
                                    <small class="text-muted">Venta: V-789458</small>
                                    <br>
                                    <small class="text-success">
                                        <i class="bi bi-check me-1"></i>5 días restantes
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block">Fecha de Compra:</small>
                                <strong>02/11/2025</strong>
                                <br>
                                <small class="text-success">
                                    <i class="bi bi-calendar me-1"></i>Dentro del plazo
                                </small>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="btn-group-vertical" role="group">
                                    <button class="btn btn-success btn-sm mb-2" title="Procesar Factura">
                                        <i class="bi bi-check-circle me-1"></i>Facturar
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm mb-2" title="Ver Detalles">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" title="Contactar Cliente">
                                        <i class="bi bi-chat me-1"></i>Contactar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Más tickets... -->
        </div>

        <!-- Paginación -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Mostrando 1-10 de 23 tickets</small>
                            </div>
                            <nav aria-label="Navegación de tickets">
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item active">
                                        <a class="page-link" href="#">1</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-success">
                                    <i class="bi bi-check-all me-1"></i>Procesar Seleccionados
                                </button>
                                <button type="button" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles del Ticket -->
<div class="modal fade" id="ticketDetailsModal" tabindex="-1" aria-labelledby="ticketDetailsModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="ticketDetailsModalLabel">
                    <i class="bi bi-receipt me-2"></i>Detalles del Ticket
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Contenido del modal se carga dinámicamente -->
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Búsqueda en tiempo real
        document.getElementById('searchTickets').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            // Lógica de búsqueda
            console.log('Buscando:', searchTerm);
        });

        // Filtros
        document.getElementById('filterSucursal').addEventListener('change', function() {
            console.log('Filtrar por sucursal:', this.value);
        });

        document.getElementById('filterPrioridad').addEventListener('change', function() {
            console.log('Filtrar por prioridad:', this.value);
        });

        // Botones de acción
        document.querySelectorAll('button[title="Procesar Factura"]').forEach(button => {
            button.addEventListener('click', function() {
                const ticketId = this.closest('.card').querySelector('h6').textContent;
                if (confirm(`¿Procesar factura para ${ticketId}?`)) {
                    alert('Factura procesada exitosamente');
                }
            });
        });

        document.querySelectorAll('button[title="Ver Detalles"]').forEach(button => {
            button.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('ticketDetailsModal'));
                modal.show();
            });
        });

        // Selección múltiple
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const selectedCount = document.querySelectorAll('input[type="checkbox"]:checked').length;
                console.log(`${selectedCount} tickets seleccionados`);
            });
        });

        // Auto-refresh cada 30 segundos
        setInterval(function() {
            console.log('Actualizando tickets pendientes...');
        }, 30000);
    });
</script>