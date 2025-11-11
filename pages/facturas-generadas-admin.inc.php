<!-- Facturas Generadas -->
<div class="content-wrapper bg-light loaded">
    <!-- Header -->
    <div class="container py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-8">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="bi bi-receipt-cutoff display-6 text-primary me-2"></i>
                    Facturas Generadas
                </h2>
                <p class="text-muted mb-0">Gestiona las facturas generadas por los usuarios.</p>
            </div>
            <div class="col-4 text-end">
                <button type="button" class="btn btn-outline-primary btn-lg rounded-3" onclick="window.history.back()">
                    <i class="bi bi-arrow-left me-2"></i>Regresar
                </button>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-12">
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
                <!-- Listado de Facturas-->
                <div id="vistaFacturas" class="p-4">
                    <ul class="list-group list-group-flush">

                        <!-- Factura 1 - Vigente -->
                        <li class="list-group-item py-4 border-bottom factura-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">FAC-2024-001234</h6>
                                    <small class="text-muted">07 Nov 2024 • 14:30</small>
                                    <div class="mt-2 small text-muted">
                                        <span class="me-3"><strong>Cliente:</strong> ACME Corporation SA</span>
                                        <span class="me-3"><strong>RFC:</strong> ACM890123ABC</span>
                                        <span class="me-3"><strong>Sucursal:</strong> Centro</span>
                                        <span><strong>Total:</strong> <span class="text-success">$12,500.00</span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-success bg-opacity-10 rounded-3 p-3 my-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <small class="text-success d-block fw-semibold">Completada</small>
                                        <small class="text-muted">06 Nov 2024 • 16:45</small>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
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
                        </li>
                        <!-- Factura 2 - Cancelada -->
                        <li class="list-group-item py-4 border-bottom factura-card opacity-75">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold text-muted mb-1">FAC-2024-001232</h6>
                                    <small class="text-muted">05 Nov 2024 • 09:20</small>
                                    <div class="mt-2 small text-muted">
                                        <span class="me-3"><strong>Cliente:</strong> Empresa ABC SA</span>
                                        <span class="me-3"><strong>RFC:</strong> ABC456789GHI</span>
                                        <span class="me-3"><strong>Sucursal:</strong> Sur</span>
                                        <span><strong>Total:</strong> $5,200.00</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-danger bg-opacity-10 rounded-3 p-3 my-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-x-circle text-danger me-2"></i>
                                    <div>
                                        <small class="text-danger d-block fw-semibold">Cancelada</small>
                                        <small class="text-muted">Motivo: Error en datos del cliente</small>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="verFactura('001232')">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" disabled>
                                        <i class="bi bi-x-circle me-1"></i>Cancelada
                                    </button>
                                </div>
                            </div>
                        </li>

                        <!-- Factura 4 - Pendiente -->
                        <li class="list-group-item py-4 border-bottom factura-card border-warning">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">FAC-2024-001231</h6>
                                    <small class="text-muted">04 Nov 2024 • 17:45</small>
                                    <div class="mt-2 small text-muted">
                                        <span class="me-3"><strong>Cliente:</strong> Constructora DEF SA</span>
                                        <span class="me-3"><strong>RFC:</strong> DEF789012JKL</span>
                                        <span class="me-3"><strong>Sucursal:</strong> Centro</span>
                                        <span><strong>Total:</strong> <span class="text-success">$25,800.00</span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-warning bg-opacity-10 rounded-3 p-3 my-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock text-warning me-2"></i>
                                    <div>
                                        <small class="text-warning d-block fw-semibold">Factura Pendiente</small>
                                        <small class="text-muted">Vence: 14 Nov 2024</small>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary" onclick="verFactura('001231')">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <small class="text-muted">Mostrando 1-4 de 1,847 facturas</small>
                        <nav>
                            <ul class="pagination pagination-lg mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Anterior</span>
                                </li>
                                <li class="page-item active"><span class="page-link">1</span></li>
                                <li class="page-item"><a class="page-link" href="#" onclick="cargarPagina(2)">2</a></li>
                                <li class="page-item"><a class="page-link" href="#" onclick="cargarPagina(3)">3</a></li>
                                <li class="page-item"><span class="page-link">...</span></li>
                                <li class="page-item"><a class="page-link" href="#" onclick="cargarPagina(462)">462</a></li>
                                <li class="page-item"><a class="page-link" href="#" onclick="cargarPagina(2)">Siguiente</a></li>
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