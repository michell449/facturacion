<!-- Facturas Generadas -->
<div class="content-wrapper bg-light loaded">
    <!-- Header -->
    <div class="bg-success text-white py-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                            <i class="bi bi-receipt display-6 text-white"></i>
                        </div>
                        <div>
                            <h1 class="fw-bold mb-2">Facturas Generadas</h1>
                            <p class="lead mb-0 opacity-90">Consulta y administra todas las facturas emitidas</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="btn-group">
                        <button class="btn btn-light btn-lg fw-semibold" onclick="exportarFacturas()">
                            <i class="bi bi-download me-2"></i>Exportar
                        </button>
                        <button class="btn btn-warning btn-lg fw-semibold" data-bs-toggle="modal" data-bs-target="#filtrosAvanzadosModal">
                            <i class="bi bi-funnel me-2"></i>Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <!-- Resumen Estadísticas -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Total Facturas</div>
                                <h3 class="fw-bold text-primary mb-0">1,847</h3>
                                <small class="text-success">
                                    <i class="bi bi-arrow-up me-1"></i>+12% vs mes anterior
                                </small>
                            </div>
                            <div class="bg-primary bg-opacity-20 rounded-circle p-3">
                                <i class="bi bi-receipt text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Monto Total</div>
                                <h3 class="fw-bold text-success mb-0">$2.8M</h3>
                                <small class="text-success">
                                    <i class="bi bi-arrow-up me-1"></i>+18% vs mes anterior
                                </small>
                            </div>
                            <div class="bg-success bg-opacity-20 rounded-circle p-3">
                                <i class="bi bi-currency-dollar text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Promedio Diario</div>
                                <h3 class="fw-bold text-info mb-0">62</h3>
                                <small class="text-info">
                                    <i class="bi bi-graph-up me-1"></i>Facturas/día
                                </small>
                            </div>
                            <div class="bg-info bg-opacity-20 rounded-circle p-3">
                                <i class="bi bi-graph-up text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Este Mes</div>
                                <h3 class="fw-bold text-warning mb-0">456</h3>
                                <small class="text-warning">
                                    <i class="bi bi-calendar-check me-1"></i>Noviembre 2024
                                </small>
                            </div>
                            <div class="bg-warning bg-opacity-20 rounded-circle p-3">
                                <i class="bi bi-calendar-month text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 rounded-end-3" 
                           placeholder="Buscar por folio, cliente, RFC..." id="buscarFactura">
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-lg rounded-3" id="filtroSucursal">
                    <option value="">Todas las sucursales</option>
                    <option value="centro">Sucursal Centro</option>
                    <option value="norte">Sucursal Norte</option>
                    <option value="sur">Sucursal Sur</option>
                    <option value="oriente">Sucursal Oriente</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-lg rounded-3" id="filtroEstado">
                    <option value="">Todos los estados</option>
                    <option value="vigente">Vigente</option>
                    <option value="cancelada">Cancelada</option>
                    <option value="pagada">Pagada</option>
                    <option value="pendiente">Pendiente</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control form-control-lg rounded-3" id="fechaDesde">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control form-control-lg rounded-3" id="fechaHasta">
            </div>
        </div>

        <!-- Lista de Facturas -->
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Listado de Facturas</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="toggleVista('tabla')">
                            <i class="bi bi-table"></i>
                        </button>
                        <button class="btn btn-primary" onclick="toggleVista('tarjetas')">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Vista de Tarjetas (por defecto) -->
                <div id="vistaFacturas" class="p-4">
                    <div class="row g-4">
                        <!-- Factura 1 - Vigente -->
                        <div class="col-lg-6">
                            <div class="card border-0 rounded-4 shadow-sm h-100 factura-card">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="fw-bold text-primary mb-1">FAC-2024-001234</h6>
                                            <small class="text-muted">07 Nov 2024 • 14:30</small>
                                        </div>
                                        <span class="badge bg-success rounded-pill">Vigente</span>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Cliente</small>
                                            <strong class="small">ACME Corporation SA</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">RFC</small>
                                            <strong class="small">ACM890123ABC</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Sucursal</small>
                                            <strong class="small">Centro</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Total</small>
                                            <strong class="text-success">$12,500.00</strong>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-light rounded-3 p-3 mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-qr-code text-primary me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">UUID</small>
                                                    <code class="small">12345678-ABCD-1234-EFGH-567890123456</code>
                                                </div>
                                            </div>
                                            <button class="btn btn-sm btn-outline-primary" onclick="copiarUUID('12345678-ABCD-1234-EFGH-567890123456')">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary" onclick="verFactura('001234')">
                                                <i class="bi bi-eye me-1"></i>Ver
                                            </button>
                                            <button type="button" class="btn btn-outline-success" onclick="descargarPDF('001234')">
                                                <i class="bi bi-download me-1"></i>PDF
                                            </button>
                                            <button type="button" class="btn btn-outline-info" onclick="descargarXML('001234')">
                                                <i class="bi bi-code me-1"></i>XML
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="cancelarFactura('001234')">
                                                <i class="bi bi-x-circle me-1"></i>Cancelar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Factura 2 - Pagada -->
                        <div class="col-lg-6">
                            <div class="card border-0 rounded-4 shadow-sm h-100 factura-card">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="fw-bold text-primary mb-1">FAC-2024-001233</h6>
                                            <small class="text-muted">06 Nov 2024 • 11:15</small>
                                        </div>
                                        <span class="badge bg-info rounded-pill">Pagada</span>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Cliente</small>
                                            <strong class="small">Servicios XYZ SA de CV</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">RFC</small>
                                            <strong class="small">XYZ123456DEF</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Sucursal</small>
                                            <strong class="small">Norte</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Total</small>
                                            <strong class="text-success">$8,750.00</strong>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-light rounded-3 p-3 mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <div>
                                                    <small class="text-success d-block fw-semibold">Pagada</small>
                                                    <small class="text-muted">06 Nov 2024 • 16:45</small>
                                                </div>
                                            </div>
                                            <span class="badge bg-success">Completa</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary" onclick="verFactura('001233')">
                                                <i class="bi bi-eye me-1"></i>Ver
                                            </button>
                                            <button type="button" class="btn btn-outline-success" onclick="descargarPDF('001233')">
                                                <i class="bi bi-download me-1"></i>PDF
                                            </button>
                                            <button type="button" class="btn btn-outline-info" onclick="descargarXML('001233')">
                                                <i class="bi bi-code me-1"></i>XML
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" disabled>
                                                <i class="bi bi-shield-check me-1"></i>Pagada
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Factura 3 - Cancelada -->
                        <div class="col-lg-6">
                            <div class="card border-0 rounded-4 shadow-sm h-100 factura-card opacity-75">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="fw-bold text-muted mb-1">FAC-2024-001232</h6>
                                            <small class="text-muted">05 Nov 2024 • 09:20</small>
                                        </div>
                                        <span class="badge bg-danger rounded-pill">Cancelada</span>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Cliente</small>
                                            <strong class="small text-muted">Empresa ABC SA</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">RFC</small>
                                            <strong class="small text-muted">ABC456789GHI</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Sucursal</small>
                                            <strong class="small text-muted">Sur</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Total</small>
                                            <strong class="text-muted">$5,200.00</strong>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-danger bg-opacity-10 rounded-3 p-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-x-circle text-danger me-2"></i>
                                            <div>
                                                <small class="text-danger d-block fw-semibold">Cancelada</small>
                                                <small class="text-muted">Motivo: Error en datos del cliente</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-secondary" onclick="verFactura('001232')">
                                                <i class="bi bi-eye me-1"></i>Ver
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" disabled>
                                                <i class="bi bi-x-circle me-1"></i>Cancelada
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Factura 4 - Pendiente -->
                        <div class="col-lg-6">
                            <div class="card border-0 rounded-4 shadow-sm h-100 factura-card border-warning">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="fw-bold text-primary mb-1">FAC-2024-001231</h6>
                                            <small class="text-muted">04 Nov 2024 • 17:45</small>
                                        </div>
                                        <span class="badge bg-warning text-dark rounded-pill">Pendiente</span>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Cliente</small>
                                            <strong class="small">Constructora DEF SA</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">RFC</small>
                                            <strong class="small">DEF789012JKL</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Sucursal</small>
                                            <strong class="small">Centro</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Total</small>
                                            <strong class="text-success">$25,800.00</strong>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock text-warning me-2"></i>
                                            <div>
                                                <small class="text-warning d-block fw-semibold">Pago Pendiente</small>
                                                <small class="text-muted">Vence: 14 Nov 2024</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary" onclick="verFactura('001231')">
                                                <i class="bi bi-eye me-1"></i>Ver
                                            </button>
                                            <button type="button" class="btn btn-outline-success" onclick="descargarPDF('001231')">
                                                <i class="bi bi-download me-1"></i>PDF
                                            </button>
                                            <button type="button" class="btn btn-warning" onclick="enviarRecordatorio('001231')">
                                                <i class="bi bi-bell me-1"></i>Recordatorio
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <small class="text-muted">Mostrando 1-4 de 1,847 facturas</small>
                        <nav>
                            <ul class="pagination pagination-lg mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Anterior</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#" onclick="cargarPagina(2)">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#" onclick="cargarPagina(3)">3</a>
                                </li>
                                <li class="page-item">
                                    <span class="page-link">...</span>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#" onclick="cargarPagina(462)">462</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#" onclick="cargarPagina(2)">Siguiente</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Filtros Avanzados -->
<div class="modal fade" id="filtrosAvanzadosModal" tabindex="-1" aria-labelledby="filtrosAvanzadosModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="filtrosAvanzadosModalLabel">
                    <i class="bi bi-funnel me-2"></i>Filtros Avanzados
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formFiltrosAvanzados">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rango de Fechas</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="date" class="form-control" id="fechaDesdeAvanzado">
                                </div>
                                <div class="col-6">
                                    <input type="date" class="form-control" id="fechaHastaAvanzado">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rango de Montos</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control" placeholder="Desde $" id="montoDesde">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" placeholder="Hasta $" id="montoHasta">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Estados</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="estadoVigente" checked>
                                <label class="form-check-label" for="estadoVigente">Vigente</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="estadoPagada" checked>
                                <label class="form-check-label" for="estadoPagada">Pagada</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="estadoPendiente" checked>
                                <label class="form-check-label" for="estadoPendiente">Pendiente</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="estadoCancelada">
                                <label class="form-check-label" for="estadoCancelada">Cancelada</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sucursales</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sucursalCentro" checked>
                                <label class="form-check-label" for="sucursalCentro">Centro</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sucursalNorte" checked>
                                <label class="form-check-label" for="sucursalNorte">Norte</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sucursalSur" checked>
                                <label class="form-check-label" for="sucursalSur">Sur</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sucursalOriente" checked>
                                <label class="form-check-label" for="sucursalOriente">Oriente</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                    Limpiar Filtros
                </button>
                <button type="button" class="btn btn-primary btn-lg fw-semibold" onclick="aplicarFiltros()">
                    <i class="bi bi-funnel me-2"></i>Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Búsqueda en tiempo real
        document.getElementById('buscarFactura').addEventListener('input', function() {
            const termino = this.value.toLowerCase();
            const facturas = document.querySelectorAll('.factura-card');
            
            facturas.forEach(factura => {
                const contenido = factura.textContent.toLowerCase();
                factura.closest('.col-lg-6').style.display = 
                    contenido.includes(termino) ? 'block' : 'none';
            });
        });

        // Filtros simples
        ['filtroSucursal', 'filtroEstado'].forEach(id => {
            document.getElementById(id).addEventListener('change', aplicarFiltrosSimples);
        });

        // Filtros de fecha
        ['fechaDesde', 'fechaHasta'].forEach(id => {
            document.getElementById(id).addEventListener('change', aplicarFiltrosSimples);
        });
    });

    function aplicarFiltrosSimples() {
        console.log('Aplicando filtros simples...');
        // Aquí iría la lógica de filtrado
    }

    function toggleVista(tipo) {
        console.log('Cambiando vista a:', tipo);
        // Aquí se implementaría el cambio entre vista de tabla y tarjetas
    }

    function verFactura(folio) {
        alert('Abriendo factura: FAC-2024-' + folio);
    }

    function descargarPDF(folio) {
        alert('Descargando PDF de factura: FAC-2024-' + folio);
    }

    function descargarXML(folio) {
        alert('Descargando XML de factura: FAC-2024-' + folio);
    }

    function cancelarFactura(folio) {
        if (confirm('¿Estás seguro de cancelar la factura FAC-2024-' + folio + '?')) {
            alert('Iniciando proceso de cancelación...');
        }
    }

    function enviarRecordatorio(folio) {
        alert('Enviando recordatorio de pago para factura: FAC-2024-' + folio);
    }

    function copiarUUID(uuid) {
        navigator.clipboard.writeText(uuid).then(() => {
            alert('UUID copiado al portapapeles');
        });
    }

    function exportarFacturas() {
        alert('Iniciando exportación de facturas...');
    }

    function aplicarFiltros() {
        // Recopilar valores de filtros avanzados
        const filtros = {
            fechaDesde: document.getElementById('fechaDesdeAvanzado').value,
            fechaHasta: document.getElementById('fechaHastaAvanzado').value,
            montoDesde: document.getElementById('montoDesde').value,
            montoHasta: document.getElementById('montoHasta').value,
            estados: {
                vigente: document.getElementById('estadoVigente').checked,
                pagada: document.getElementById('estadoPagada').checked,
                pendiente: document.getElementById('estadoPendiente').checked,
                cancelada: document.getElementById('estadoCancelada').checked
            },
            sucursales: {
                centro: document.getElementById('sucursalCentro').checked,
                norte: document.getElementById('sucursalNorte').checked,
                sur: document.getElementById('sucursalSur').checked,
                oriente: document.getElementById('sucursalOriente').checked
            }
        };

        console.log('Aplicando filtros avanzados:', filtros);
        bootstrap.Modal.getInstance(document.getElementById('filtrosAvanzadosModal')).hide();
        alert('Filtros aplicados exitosamente');
    }

    function limpiarFiltros() {
        document.getElementById('formFiltrosAvanzados').reset();
        // Marcar todos los checkboxes por defecto
        ['estadoVigente', 'estadoPagada', 'estadoPendiente', 'sucursalCentro', 'sucursalNorte', 'sucursalSur', 'sucursalOriente'].forEach(id => {
            document.getElementById(id).checked = true;
        });
    }

    function cargarPagina(pagina) {
        console.log('Cargando página:', pagina);
        alert('Cargando página ' + pagina + '...');
    }
</script>