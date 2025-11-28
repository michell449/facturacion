<!-- Tickets Pendientes de Facturación -->
<div class="content-wrapper bg-light loaded">
    <!-- Header -->
    <div class="container py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-8">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="bi bi-ticket-detailed display-6 text-primary me-2"></i>
                    Tickets
                </h2>
                <p class="text-muted mb-0">Gestiona los tickets pendientes de facturación.</p>
            </div>
            <div class="col-4 text-end">
                <button type="button" class="btn btn-outline-primary btn-lg rounded-3" onclick="window.history.back()">
                    <i class="bi bi-arrow-left me-2"></i>Regresar
                </button>
            </div>
        </div>

        <div class="container py-4">
            <!-- Filtros y Controles -->
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-0 bg-light"
                                            placeholder="Buscar por folio..." id="searchFolio">
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
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="bi bi-calendar text-muted"></i>
                                        </span>
                                        <input type="date" class="form-control border-0 bg-light" id="filterFecha">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="bi bi-currency-dollar text-muted"></i>
                                        </span>
                                        <input type="number" class="form-control border-0 bg-light"
                                            placeholder="Monto..." id="filterMonto" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select border-0 bg-light" id="filterEstadoFacturacion">
                                        <option value="">Todos los estados</option>
                                        <option value="pendiente">Sin Facturar</option>
                                        <option value="facturado">Facturado</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <div class="btn-group w-100" role="group">
                                        <button class="btn btn-outline-primary" title="Exportar">
                                            <i class="bi bi-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Tickets -->
            <div class="row g-4 py-4">
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h6 class="fw-bold text-primary mb-1">TKT-001234</h6>
                                        <small class="text-muted">Sucursal Centro</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="mb-1">Número de Cuenta: <span class="text-primary">CTA-00456789</span></h6>
                                    <small class="text-muted">Estado: </small>
                                    <span class="badge bg-warning text-dark">Sin Facturar</span>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-receipt me-1"></i>Folio Ticket: F-789456
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h6 class="text-success mb-1">$1,250.00</h6>
                                        <small class="text-muted">Importe Total</small>
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
                                <div class="col-md-3 text-end">
                                    <div class="btn-group-vertical" role="group">
                                        <button class="btn btn-success btn-sm mb-2" title="Procesar Factura">
                                            <i class="bi bi-check-circle me-1"></i>Facturar
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" title="Ver Detalles">
                                            <i class="bi bi-eye me-1"></i>Ver Detalles
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h6 class="fw-bold text-primary mb-1">TKT-001235</h6>
                                        <small class="text-muted">Sucursal Norte</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="mb-1">Número de Cuenta: <span class="text-primary">CTA-00456790</span></h6>
                                    <small class="text-muted">Estado: </small>
                                    <span class="badge bg-success">Facturado</span>
                                    <br>
                                    <small class="text-success">
                                        <i class="bi bi-file-earmark-check me-1"></i>Folio Factura: FAC-A-2025-1235
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h6 class="text-success mb-1">$850.00</h6>
                                        <small class="text-muted">Importe Total</small>
                                        <br>
                                        <small class="text-success">
                                            <i class="bi bi-check-circle me-1"></i>Procesado
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted d-block">Fecha de Compra:</small>
                                    <strong>04/11/2025</strong>
                                    <br>
                                    <small class="text-success">
                                        <i class="bi bi-calendar-check me-1"></i>Facturado
                                    </small>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="btn-group-vertical" role="group">
                                        <button class="btn btn-info btn-sm mb-2" title="Reenviar Factura">
                                            <i class="bi bi-arrow-repeat me-1"></i>Reenviar
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm mb-2" title="Cancelar Factura">
                                            <i class="bi bi-x-circle me-1"></i>Cancelar
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" title="Ver Detalles">
                                            <i class="bi bi-eye me-1"></i>Ver
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h6 class="fw-bold text-primary mb-1">TKT-001236</h6>
                                        <small class="text-muted">Sucursal Centro</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="mb-1">Número de Cuenta: <span class="text-primary">CTA-00456791</span></h6>
                                    <small class="text-muted">Estado: </small>
                                    <span class="badge bg-warning text-dark">Sin Facturar</span>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-receipt me-1"></i>Folio Ticket: F-789458
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h6 class="text-success mb-1">$2,100.00</h6>
                                        <small class="text-muted">Importe Total</small>
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
                                <div class="col-md-3 text-end">
                                    <div class="btn-group-vertical" role="group">
                                        <button class="btn btn-success btn-sm mb-2" title="Procesar Factura">
                                            <i class="bi bi-check-circle me-1"></i>Facturar
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" title="Ver Detalles">
                                            <i class="bi bi-eye me-1"></i>Ver Detalles
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
            // Búsqueda por folio en tiempo real
            document.getElementById('searchFolio').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                filtrarTickets();
                console.log('Buscando folio:', searchTerm);
            });

            // Filtros
            document.getElementById('filterSucursal').addEventListener('change', function() {
                filtrarTickets();
                console.log('Filtrar por sucursal:', this.value);
            });

            document.getElementById('filterFecha').addEventListener('change', function() {
                filtrarTickets();
                console.log('Filtrar por fecha:', this.value);
            });

            document.getElementById('filterMonto').addEventListener('input', function() {
                filtrarTickets();
                console.log('Filtrar por monto:', this.value);
            });

            document.getElementById('filterEstadoFacturacion').addEventListener('change', function() {
                filtrarTickets();
                console.log('Filtrar por estado:', this.value);
            });

            // Función para filtrar tickets
            function filtrarTickets() {
                const folio = document.getElementById('searchFolio').value.toLowerCase();
                const sucursal = document.getElementById('filterSucursal').value;
                const fecha = document.getElementById('filterFecha').value;
                const monto = document.getElementById('filterMonto').value;
                const estado = document.getElementById('filterEstadoFacturacion').value;

                // Aquí iría la lógica de filtrado
                console.log('Aplicando filtros:', { folio, sucursal, fecha, monto, estado });
            }

            // Botones de acción - Facturar
            document.querySelectorAll('button[title="Procesar Factura"]').forEach(button => {
                button.addEventListener('click', function() {
                    const ticketId = this.closest('.card').querySelector('h6').textContent;
                    if (confirm(`¿Procesar factura para ${ticketId}?`)) {
                        // Aquí iría la lógica para procesar la factura
                        alert('Factura procesada exitosamente');
                    }
                });
            });

            // Botones de acción - Reenviar Factura
            document.querySelectorAll('button[title="Reenviar Factura"]').forEach(button => {
                button.addEventListener('click', function() {
                    const ticketId = this.closest('.card').querySelector('h6').textContent;
                    if (confirm(`¿Reenviar factura para ${ticketId}?`)) {
                        // Aquí iría la lógica para reenviar la factura
                        alert('Factura reenviada exitosamente');
                    }
                });
            });

            // Botones de acción - Cancelar Factura
            document.querySelectorAll('button[title="Cancelar Factura"]').forEach(button => {
                button.addEventListener('click', function() {
                    const ticketId = this.closest('.card').querySelector('h6').textContent;
                    if (confirm(`¿Está seguro de cancelar la factura para ${ticketId}? Esta acción no se puede deshacer.`)) {
                        // Aquí iría la lógica para cancelar la factura
                        alert('Factura cancelada');
                    }
                });
            });

            // Ver detalles
            document.querySelectorAll('button[title="Ver Detalles"]').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = new bootstrap.Modal(document.getElementById('ticketDetailsModal'));
                    modal.show();
                });
            });

            // Auto-refresh cada 30 segundos
            setInterval(function() {
                console.log('Actualizando tickets...');
                // Aquí iría la lógica para actualizar la lista
            }, 30000);
        });
    </script>