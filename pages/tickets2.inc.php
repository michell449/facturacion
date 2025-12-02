<div class="content-wrapper bg-light loaded">
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
                                    <button type="button" class="btn btn-outline-info btn-sm w-100" onclick="toggleConfigProductos()">
                                        <i class="bi bi-gear me-1"></i>Config
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4" id="configProductosPanel" style="display: none;">
                <div class="col-lg-12">
                    <div class="card shadow-lg border-0 rounded-4 border-info">
                        <div class="card-header bg-info text-white py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold mb-0">
                                    <i class="bi bi-boxes me-2"></i>Configuración de Visualización de Productos
                                </h6>
                                <button class="btn btn-sm btn-outline-light" onclick="toggleConfigProductos()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <h6 class="fw-bold text-info mb-3">
                                        <i class="bi bi-eye me-2"></i>Modo de Visualización
                                    </h6>
                                    <div class="d-grid gap-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="modoVisualizacion" id="modoDesglosado" value="desglosado" checked>
                                            <label class="form-check-label fw-semibold" for="modoDesglosado">
                                                <i class="bi bi-list-ul me-2 text-primary"></i>Desglosado
                                                <small class="d-block text-muted">Cada producto en línea separada</small>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="modoVisualizacion" id="modoAgrupado" value="agrupado">
                                            <label class="form-check-label fw-semibold" for="modoAgrupado">
                                                <i class="bi bi-collection me-2 text-success"></i>Agrupado
                                                <small class="d-block text-muted">Productos similares combinados</small>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="modoVisualizacion" id="modoResumen" value="resumen">
                                            <label class="form-check-label fw-semibold" for="modoResumen">
                                                <i class="bi bi-card-text me-2 text-warning"></i>Solo Resumen
                                                <small class="d-block text-muted">Solo totales sin detalles</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4" id="criteriosAgrupacion">
                                    <h6 class="fw-bold text-info mb-3">
                                        <i class="bi bi-funnel me-2"></i>Criterios de Agrupación
                                    </h6>
                                    <div class="d-grid gap-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="agruparPorNombre" checked>
                                            <label class="form-check-label" for="agruparPorNombre">Por Nombre</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="agruparPorPrecio" checked>
                                            <label class="form-check-label" for="agruparPorPrecio">Por Precio</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="agruparPorCategoria">
                                            <label class="form-check-label" for="agruparPorCategoria">Por Categoría</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <h6 class="fw-bold text-info mb-3">
                                        <i class="bi bi-info-circle me-2"></i>Mostrar Información
                                    </h6>
                                    <div class="d-grid gap-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarCodigos" checked>
                                            <label class="form-check-label" for="mostrarCodigos">Códigos/SKU</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarPrecios" checked>
                                            <label class="form-check-label" for="mostrarPrecios">Precios Unitarios</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarCantidades" checked>
                                            <label class="form-check-label" for="mostrarCantidades">Cantidades</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarDescuentos">
                                            <label class="form-check-label" for="mostrarDescuentos">Descuentos</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary" onclick="resetearConfigVisualizacion()">
                                            <i class="bi bi-arrow-clockwise me-2"></i>Resetear
                                        </button>
                                        <button type="button" class="btn btn-info" onclick="aplicarConfigVisualizacion()">
                                            <i class="bi bi-check-circle me-2"></i>Aplicar a Tickets
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 py-2" id="ticketsResumen">
                <div class="col-md-3">
                    <div class="card bg-info bg-opacity-10 border-0 rounded-4">
                        <div class="card-body text-center text-primary">
                            <i class="bi bi-ticket-detailed display-8 mb-2"></i>
                            <h4 class="mb-1" id="totalTickets">0</h4>
                            <small>Total Tickets</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info border-0 rounded-4 bg-opacity-10">
                        <div class="card-body text-center text-primary">
                            <i class="bi bi-clock-history display-8 mb-2"></i>
                            <h4 class="mb-1" id="ticketsPendientes">0</h4>
                            <small>Pendientes</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info border-0 rounded-4 bg-opacity-10">
                        <div class="card-body text-center text-primary">
                            <i class="bi bi-check-circle display-8 mb-2"></i>
                            <h4 class="mb-1" id="ticketsFacturados">0</h4>
                            <small>Facturados</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info border-0 rounded-4 bg-opacity-10">
                        <div class="card-body text-center text-primary">
                            <i class="bi bi-currency-dollar display-8 mb-2"></i>
                            <h4 class="mb-1" id="totalImporte">$0.00</h4>
                            <small>Importe Total</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 py-4" id="loadingState">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-3 text-muted">Cargando tickets...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 py-4" id="ticketsContainer" style="display: none;">
                </div>
            <div class="row mt-4" id="paginacionContainer" style="display: none;">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted" id="infoPaginacion">Mostrando 0 tickets</small>
                                </div>
                                <nav aria-label="Navegación de tickets">
                                    <ul class="pagination mb-0" id="paginacion">
                                        </ul>
                                </nav>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary" onclick="cargarTickets()">
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
                    </div>
            </div>
        </div>
    </div>

    <script>
        let paginaActual = 1;
        let ticketsData = [];
        let sucursalesData = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar carga de datos
            cargarTickets();

            // Event Listeners para Filtros
            document.getElementById('searchFolio').addEventListener('input', debounce(aplicarFiltros, 300));
            document.getElementById('filterSucursal').addEventListener('change', aplicarFiltros);
            document.getElementById('filterFecha').addEventListener('change', aplicarFiltros);
            document.getElementById('filterMonto').addEventListener('input', debounce(aplicarFiltros, 500));
            document.getElementById('filterEstadoFacturacion').addEventListener('change', aplicarFiltros);

            // Event Listeners para Visualización (Radio Buttons)
            const radioButtons = document.querySelectorAll('input[name="modoVisualizacion"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', onModoVisualizacionChange);
            });
            
            // Inicializar estado de criterios de agrupación
            onModoVisualizacionChange();
            
            // Cargar configuración guardada si existe
            cargarConfiguracionGuardada();
        });

        function cargarTickets(pagina = 1) {
            paginaActual = pagina;

            const loadingState = document.getElementById('loadingState');
            const ticketsContainer = document.getElementById('ticketsContainer');
            const paginacionContainer = document.getElementById('paginacionContainer');

            if(loadingState) loadingState.style.display = 'block';
            if(ticketsContainer) ticketsContainer.style.display = 'none';
            if(paginacionContainer) paginacionContainer.style.display = 'none';

            const params = new URLSearchParams({
                pagina: pagina,
                limite: 20
            });

            const folio = document.getElementById('searchFolio').value.trim();
            const sucursal = document.getElementById('filterSucursal').value;
            const fecha = document.getElementById('filterFecha').value;
            const monto = document.getElementById('filterMonto').value;
            const estado = document.getElementById('filterEstadoFacturacion').value;

            if (folio) params.append('folio', folio);
            if (sucursal) params.append('id_empresa', sucursal);
            if (fecha) params.append('fecha_inicio', fecha);
            if (monto) params.append('importe_min', monto);
            if (estado) params.append('estatus', estado);

            fetch(`core/consultar-tickets.php?${params.toString()}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta del servidor');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        ticketsData = data.tickets;
                        mostrarTickets(data);
                        actualizarResumen(data.resumen);
                        actualizarPaginacion(data);
                        cargarSucursales(data.tickets);
                    } else {
                        mostrarError(data.message || 'No se pudieron cargar los datos');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarError('Error de conexión al cargar tickets. Verifique su red.');
                });
        }

        function mostrarTickets(data) {
            const container = document.getElementById('ticketsContainer');
            const loadingState = document.getElementById('loadingState');

            if (data.tickets.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body text-center p-5">
                                <i class="bi bi-ticket-detailed display-1 text-muted mb-3"></i>
                                <h5 class="text-muted">No hay tickets disponibles</h5>
                                <p class="text-muted mb-0">No se encontraron tickets con los filtros aplicados.</p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                container.innerHTML = data.tickets.map(ticket => crearTicketHTML(ticket)).join('');
            }

            if(loadingState) loadingState.style.display = 'none';
            container.style.display = 'block';
        }

        function crearTicketHTML(ticket) {
            const estadoClass = ticket.estatus === 'facturado' ? 'bg-success' : 'bg-warning text-dark';
            const estadoTexto = ticket.estatus === 'facturado' ? 'Facturado' : 'Pendiente';

            let urgenciaClass = 'text-success';
            if (ticket.urgencia === 'alta') urgenciaClass = 'text-danger';
            else if (ticket.urgencia === 'media') urgenciaClass = 'text-warning';

            const uuidSimulado = ticket.estatus === 'facturado' ?
                `${ticket.id_ticket}-${ticket.folio_ticket}-${Math.random().toString(36).substr(2, 9)}`.toUpperCase() :
                null;

            let botonesAccion = '';
            if (ticket.estatus === 'pendiente') {
                botonesAccion = `
                    <button class="btn btn-outline-success btn-md mb-1" onclick="facturarTicket(${ticket.id_ticket})" title="Generar Factura">
                        <i class="bi bi-file-earmark-plus me-1"></i>Facturar
                    </button>
                `;
            } else {
                botonesAccion = `
                    <button class="btn btn-outline-primary btn-sm mb-1" onclick="verDetallesTicket(${ticket.id_ticket})" title="Ver Detalles">
                        <i class="bi bi-envelope me-1"></i>Reenviar
                    </button>
                    <button class="btn btn-outline-danger btn-sm mb-1" onclick="cancelarFactura(${ticket.id_ticket})" title="Cancelar Factura">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                `;
            }

            // Mejorar visualización de productos múltiples
            let productosHTML = '';
            const totalProductos = ticket.total_productos || 0;
            
            if (totalProductos > 0) {
                const productosDetalle = ticket.productos_detalle || 'Sin productos';
                
                // Si hay muchos productos, mostrar resumen
                if (totalProductos > 3) {
                    const primerProducto = productosDetalle.split(';')[0] || 'Productos varios';
                    productosHTML = `
                        <small class="text-muted d-block">
                            <i class="bi bi-box-seam me-1"></i>
                            <strong class="text-primary">${totalProductos} productos</strong>
                        </small>
                        <small class="text-muted">${primerProducto.substring(0, 35)}${primerProducto.length > 35 ? '...' : ''}</small>
                        <small class="text-info d-block">
                            <i class="bi bi-eye me-1"></i>
                            <a href="#" onclick="verDetallesTicket(${ticket.id_ticket}); return false;" class="text-decoration-none">
                                Ver todos los productos
                            </a>
                        </small>
                    `;
                } else {
                    // Mostrar productos individuales si son pocos
                    const listaProductos = productosDetalle.split(';').map(p => p.trim()).filter(p => p.length > 0);
                    productosHTML = `
                        <small class="text-muted d-block">
                            <i class="bi bi-box-seam me-1"></i>
                            <strong class="text-primary">${totalProductos} producto${totalProductos > 1 ? 's' : ''}</strong>
                        </small>
                        ${listaProductos.slice(0, 3).map(producto => 
                            `<small class="text-muted d-block">• ${producto.substring(0, 40)}${producto.length > 40 ? '...' : ''}</small>`
                        ).join('')}
                    `;
                }
            } else {
                productosHTML = `
                    <small class="text-muted">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Sin productos registrados
                    </small>
                `;
            }

            return `
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4 ${ticket.estatus === 'facturado' ? 'border-success border-opacity-25' : ''}">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h4 class="fw-bold text-primary mb-1">${ticket.folio_ticket}</h4>
                                        <small>${ticket.sucursal_completa || 'Sucursal Desconocida'}</small>
                                        <div class="mb-2">
                                            <span class="badge ${estadoClass} mb-1">${estadoTexto}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="border rounded p-2 bg-light">
                                        ${productosHTML}
                                        ${ticket.estatus === 'facturado' && uuidSimulado ? `
                                        <div class="mt-2 p-2 bg-white rounded border">
                                            <small class="text-muted d-block">Folio Fiscal:</small>
                                            <small class="fw-bold text-primary font-monospace" style="font-size: 0.75rem;">${uuidSimulado}</small>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="text-center ps-3">
                                        <small class="text-muted d-block">Total </small>
                                        <h5 class="text-success mb-1 fw-bold">$${ticket.importe_formateado || '0.00'}</h5>
                                        <small class="text-muted">Subtotal: <span class="fw-bold">$${ticket.subtotal_formateado || '0.00'}</span></small>
                                        <br>
                                        <small class="text-muted">IVA: <span class="fw-bold">$${ticket.impuesto_formateado || '0.00'}</span></small>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="text-center ps-3">
                                        <small class="text-muted d-block mb-1">Fecha de Venta:</small>
                                        <strong class="d-block mb-2">${ticket.fecha_formateada || ticket.fecha_venta}</strong>
                                        <small class="${urgenciaClass}">
                                            <i class="bi bi-${ticket.estatus === 'facturado' ? 'check-circle' : 'clock-history'} me-1"></i>
                                            ${ticket.mensaje_urgencia || ''}
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="d-flex flex-column gap-1 align-items-end">
                                        ${botonesAccion}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function actualizarResumen(resumen) {
            if(!resumen) return;
            document.getElementById('totalTickets').textContent = (parseInt(resumen.pendientes) || 0) + (parseInt(resumen.facturados) || 0);
            document.getElementById('ticketsPendientes').textContent = resumen.pendientes || 0;
            document.getElementById('ticketsFacturados').textContent = resumen.facturados || 0;
            document.getElementById('totalImporte').textContent = `$${resumen.total_importe_formateado || '0.00'}`;
        }

        function cargarSucursales(tickets) {
            const select = document.getElementById('filterSucursal');
            const currentValue = select.value;
            
            // Si ya hay una selección, no queremos destruir la lista a menos que esté vacía
            // pero para este ejemplo, recargaremos basándonos en los tickets recibidos
            // para mostrar solo sucursales con actividad reciente.
            
            const sucursales = [...new Map(tickets.map(t => [t.id_empresa, {
                id: t.id_empresa,
                nombre: t.sucursal_completa
            }])).values()];

            // Solo recargar si hay sucursales nuevas o diferentes para no perder el placeholder
            // Ojo: Si el filtro de sucursal está activo, el backend solo devolverá 1 sucursal,
            // por lo que el dropdown perdería las otras opciones.
            // Solución simple: Solo llenar si el select tiene 1 opción (el default)
            if (select.options.length <= 1 && sucursales.length > 0) {
                sucursales.forEach(sucursal => {
                    select.innerHTML += `<option value="${sucursal.id}">${sucursal.nombre}</option>`;
                });
            }
            
            // Restaurar valor si existe
            if (currentValue) select.value = currentValue;
        }

        function actualizarPaginacion(data) {
            const container = document.getElementById('paginacionContainer');
            const paginacion = document.getElementById('paginacion');
            const info = document.getElementById('infoPaginacion');

            if (data.total_paginas <= 1) {
                container.style.display = 'none';
                return;
            }

            const inicio = (data.pagina_actual - 1) * data.limite + 1;
            const fin = Math.min(data.pagina_actual * data.limite, data.total_tickets);
            info.textContent = `Mostrando ${inicio}-${fin} de ${data.total_tickets} tickets`;

            let html = '';
            
            // Botón anterior
            html += `<li class="page-item ${data.pagina_actual === 1 ? 'disabled' : ''}">`;
            html += `<a class="page-link" href="#" onclick="${data.pagina_actual > 1 ? `cargarTickets(${data.pagina_actual - 1})` : 'return false'}">`;
            html += '<i class="bi bi-chevron-left"></i></a></li>';

            // Números de página
            const inicio_pag = Math.max(1, data.pagina_actual - 2);
            const fin_pag = Math.min(data.total_paginas, data.pagina_actual + 2);

            for (let i = inicio_pag; i <= fin_pag; i++) {
                html += `<li class="page-item ${i === data.pagina_actual ? 'active' : ''}">`;
                html += `<a class="page-link" href="#" onclick="cargarTickets(${i})">${i}</a></li>`;
            }

            // Botón siguiente
            html += `<li class="page-item ${data.pagina_actual === data.total_paginas ? 'disabled' : ''}">`;
            html += `<a class="page-link" href="#" onclick="${data.pagina_actual < data.total_paginas ? `cargarTickets(${data.pagina_actual + 1})` : 'return false'}">`;
            html += '<i class="bi bi-chevron-right"></i></a></li>';

            paginacion.innerHTML = html;
            container.style.display = 'block';
        }

        function aplicarFiltros() {
            cargarTickets(1);
        }

        function mostrarError(mensaje) {
            const container = document.getElementById('ticketsContainer');
            const loadingState = document.getElementById('loadingState');
            
            container.innerHTML = `
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body text-center p-5">
                            <i class="bi bi-exclamation-triangle display-4 text-danger mb-3"></i>
                            <h5 class="text-danger">Error al cargar tickets</h5>
                            <p class="text-muted mb-3">${mensaje}</p>
                            <button class="btn btn-primary" onclick="cargarTickets()">Reintentar</button>
                        </div>
                    </div>
                </div>
            `;

            if(loadingState) loadingState.style.display = 'none';
            container.style.display = 'block';
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function facturarTicket(idTicket) {
            if (confirm('¿Desea procesar la factura para este ticket?')) {
                alert('Función de facturación en desarrollo');
            }
        }

        function verDetallesTicket(idTicket) {
            const modalElement = document.getElementById('ticketDetailsModal');
            // Usar getOrCreateInstance para evitar duplicados del modal
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            
            const modalBody = modalElement.querySelector('.modal-body');
            const modalTitle = document.getElementById('ticketDetailsModalLabel');

            modalTitle.innerHTML = `<i class="bi bi-receipt me-2"></i>Cargando detalles...`;
            modalBody.innerHTML = `
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Obteniendo detalles del ticket...</p>
                </div>
            `;

            modal.show();

            fetch('core/ticket-actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `accion=obtener_detalle&id_ticket=${idTicket}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const ticket = data.ticket;
                        const productos = data.productos;
                        const metodoPago = data.metodo_pago;

                        modalTitle.innerHTML = `<i class="bi bi-receipt me-2"></i>Detalles del Ticket ${ticket.folio_ticket}`;

                        let tablaProductos = '';
                        if (productos && productos.length > 0) {
                            tablaProductos = `
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-info">
                                        <tr>
                                            <th width="15%">Código</th>
                                            <th width="35%">Descripción</th>
                                            <th width="10%" class="text-center">Cant.</th>
                                            <th width="15%" class="text-end">P. Unit.</th>
                                            <th width="15%" class="text-end">Importe</th>
                                            <th width="10%" class="text-end">Impuestos</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                            productos.forEach(producto => {
                                const impuestos = (parseFloat(producto.imp_1 || 0) + parseFloat(producto.imp_2 || 0) + parseFloat(producto.imp_3 || 0));
                                let tiposImpuesto = [];
                                if (producto.imp_1 > 0) tiposImpuesto.push(`IVA: $${parseFloat(producto.imp_1).toFixed(2)}`);
                                if (producto.imp_2 > 0) tiposImpuesto.push(`IEPS: $${parseFloat(producto.imp_2).toFixed(2)}`);
                                if (producto.imp_3 > 0) tiposImpuesto.push(`Otro: $${parseFloat(producto.imp_3).toFixed(2)}`);
                                
                                tablaProductos += `
                                <tr>
                                    <td><code class="bg-light p-1 rounded">${producto.id_prod_serv}</code></td>
                                    <td>
                                        <div class="fw-bold">${producto.descr}</div>
                                        ${tiposImpuesto.length > 0 ? 
                                            `<small class="text-muted">${tiposImpuesto.join(', ')}</small>` : ''
                                        }
                                    </td>
                                    <td class="text-center"><span class="badge bg-primary">${producto.cant}</span></td>
                                    <td class="text-end"><strong>$${(parseFloat(producto.precio_unit) || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})}</strong></td>
                                    <td class="text-end"><strong class="text-success">$${(parseFloat(producto.importe) || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})}</strong></td>
                                    <td class="text-end">
                                        ${impuestos > 0 ? 
                                            `<small class="text-warning">$${impuestos.toFixed(2)}</small>` : 
                                            '<small class="text-muted">--</small>'
                                        }
                                    </td>
                                </tr>`;
                            });

                            // Calcular totales
                            const totalCantidad = productos.reduce((sum, p) => sum + parseFloat(p.cant || 0), 0);
                            const totalImporte = productos.reduce((sum, p) => sum + parseFloat(p.importe || 0), 0);
                            const totalImpuestos = productos.reduce((sum, p) => 
                                sum + parseFloat(p.imp_1 || 0) + parseFloat(p.imp_2 || 0) + parseFloat(p.imp_3 || 0), 0);

                            tablaProductos += `
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="2" class="fw-bold">
                                                <i class="bi bi-calculator me-1"></i>
                                                Totales (${productos.length} productos)
                                            </td>
                                            <td class="text-center fw-bold">${totalCantidad.toFixed(2)}</td>
                                            <td colspan="2" class="text-end fw-bold text-success">$${totalImporte.toFixed(2)}</td>
                                            <td class="text-end fw-bold text-warning">$${totalImpuestos.toFixed(2)}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>`;
                        } else {
                            tablaProductos = `
                                <div class="text-center p-4 bg-light rounded">
                                    <i class="bi bi-box display-4 text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">No hay productos registrados para este ticket.</p>
                                </div>
                            `;
                        }

                        // Validación de fecha segura
                        let fechaVenta = ticket.fecha_venta;
                        try {
                            const dateObj = new Date(ticket.fecha_venta);
                            if(!isNaN(dateObj.getTime())) {
                                fechaVenta = dateObj.toLocaleDateString('es-MX');
                            }
                        } catch(e) { console.warn('Error parseando fecha', e); }

                        modalBody.innerHTML = `
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary"><i class="bi bi-info-circle me-2"></i>Información General</h6>
                                        <p><strong>Folio:</strong> ${ticket.folio_ticket}</p>
                                        <p><strong>Sucursal:</strong> ${ticket.nombre_sucursal || ''} (${ticket.codigo_suc || ''})</p>
                                        <p><strong>RFC:</strong> ${ticket.rfc || 'N/A'}</p>
                                        <p><strong>Fecha de Venta:</strong> ${fechaVenta}</p>
                                        <p><strong>Estado:</strong> 
                                            <span class="badge ${ticket.estatus === 'facturado' ? 'bg-success' : 'bg-warning text-dark'}">
                                                ${ticket.estatus === 'facturado' ? 'Facturado' : 'Pendiente'}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title text-success"><i class="bi bi-currency-dollar me-2"></i>Importes</h6>
                                        <p><strong>Subtotal:</strong> $${(parseFloat(ticket.subtotal) || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})}</p>
                                        <p><strong>Impuestos:</strong> $${(parseFloat(ticket.impuesto_t) || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})}</p>
                                        <hr>
                                        <p><strong>Total:</strong> <span class="h5 text-success">$${(parseFloat(ticket.importe_t) || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span></p>
                                    </div>
                                </div>
                            </div>
                            ${metodoPago ? `
                            <div class="col-12">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning"><i class="bi bi-credit-card me-2"></i>Método de Pago</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Método:</strong> ${metodoPago.metodo_pago || 'N/A'}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Forma de Pago:</strong> ${metodoPago.forma_pago || 'N/A'}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Monto:</strong> $${(parseFloat(metodoPago.monto) || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                            <div class="col-12">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title text-info"><i class="bi bi-list-ul me-2"></i>Productos (${productos ? productos.length : 0})</h6>
                                        ${tablaProductos}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    } else {
                        modalTitle.innerHTML = `<i class="bi bi-exclamation-triangle me-2"></i>Error`;
                        modalBody.innerHTML = `
                        <div class="text-center p-4">
                            <i class="bi bi-exclamation-triangle display-4 text-warning"></i>
                            <h5 class="mt-3">Error al cargar detalles</h5>
                            <p class="text-muted">${data.message}</p>
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalTitle.innerHTML = `<i class="bi bi-exclamation-triangle me-2"></i>Error de Conexión`;
                    modalBody.innerHTML = `
                    <div class="text-center p-4">
                        <i class="bi bi-wifi-off display-4 text-danger"></i>
                        <h5 class="mt-3">Error de Conexión</h5>
                        <p class="text-muted">No se pudo conectar con el servidor.</p>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                `;
                });
        }

        // Función para mostrar/ocultar panel de configuración
        function toggleConfigProductos() {
            const panel = document.getElementById('configProductosPanel');
            if (panel.style.display === 'none' || panel.style.display === '') {
                panel.style.display = 'block';
            } else {
                panel.style.display = 'none';
            }
        }

        function cargarConfiguracionGuardada() {
            try {
                const config = JSON.parse(localStorage.getItem('ticketsVisualizacionConfig'));
                if (config) {
                    // Restaurar radio buttons
                    const radio = document.querySelector(`input[name="modoVisualizacion"][value="${config.modo}"]`);
                    if(radio) radio.checked = true;
                    
                    // Restaurar checkboxes
                    if(document.getElementById('agruparPorNombre')) document.getElementById('agruparPorNombre').checked = config.agruparPorNombre;
                    if(document.getElementById('mostrarCodigos')) document.getElementById('mostrarCodigos').checked = config.mostrarCodigos;
                    // ... resto de campos
                    
                    onModoVisualizacionChange();
                }
            } catch (e) {
                console.log('No hay configuración guardada');
            }
        }

        // Función para resetear configuración a valores por defecto
        function resetearConfigVisualizacion() {
            document.getElementById('modoDesglosado').checked = true;
            document.getElementById('modoAgrupado').checked = false;
            document.getElementById('modoResumen').checked = false;
            
            document.getElementById('agruparPorNombre').checked = true;
            document.getElementById('agruparPorPrecio').checked = false;
            document.getElementById('agruparPorCategoria').checked = false;
            
            document.getElementById('mostrarCodigos').checked = true;
            document.getElementById('mostrarPrecios').checked = true;
            document.getElementById('mostrarCantidades').checked = true;
            document.getElementById('mostrarDescuentos').checked = false;
            
            onModoVisualizacionChange();
            
            try {
                localStorage.removeItem('ticketsVisualizacionConfig');
            } catch (e) {}
            
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                Toast.fire({ icon: 'info', title: 'Configuración restablecida' });
            } else {
                alert('Configuración restablecida');
            }
        }

        // función para aplicar configuración de visualización
        function aplicarConfigVisualizacion() {
            const config = {
                modo: document.querySelector('input[name="modoVisualizacion"]:checked')?.value || 'desglosado',
                agruparPorNombre: document.getElementById('agruparPorNombre')?.checked || false,
                mostrarCodigos: document.getElementById('mostrarCodigos')?.checked || true,
                // Guardar resto de configuraciones...
            };

            try {
                localStorage.setItem('ticketsVisualizacionConfig', JSON.stringify(config));
                
                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    Toast.fire({ icon: 'success', title: 'Configuración aplicada correctamente' });
                }
                
                cargarTickets(paginaActual);
                toggleConfigProductos();

            } catch (e) {
                console.warn('Error al guardar config:', e);
            }
        }

        function onModoVisualizacionChange() {
            const radio = document.querySelector('input[name="modoVisualizacion"]:checked');
            if(!radio) return;
            
            const modo = radio.value;
            const criterios = document.getElementById('criteriosAgrupacion');
            
            if (criterios) {
                criterios.style.display = modo === 'agrupado' ? 'block' : 'none';
            }
        }

        function cancelarFactura(idTicket) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¿Cancelar Factura?',
                    text: 'Esta acción cancelará la factura. ¿Está seguro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Función en desarrollo', 'La cancelación estará disponible próximamente.', 'info');
                    }
                });
            } else {
                if(confirm('¿Cancelar Factura? Esta acción cancelará la factura.')) {
                    alert('Función en desarrollo');
                }
            }
        }
    </script>
</div>


<script>
    // Variables Globales
    let datosTickets = [];
    let config = { modo: 'desglosado', expandir: false };
    
    // Variables de fecha (null por defecto)
    let fechaInicio = null;
    let fechaFin = null;

    // Inicialización cuando el DOM está listo
    $(document).ready(function() {
        
        // 1. Inicializar DateRangePicker estilo AdminLTE
        inicializarDateRangePicker();

        // 2. Listeners para filtros inputs y selects
        $('#searchFolio').on('input', debounce(function() { cargarTickets(1); }, 500));
        $('#filterEstatus').on('change', function() { cargarTickets(1); });
        $('#filterSucursal').on('change', function() { cargarTickets(1); });

        // 3. Carga inicial
        cargarTickets();
    });

    // --- LÓGICA DATE RANGE PICKER (Estilo AdminLTE) ---
    function inicializarDateRangePicker() {
        const btn = $('#daterange-btn');
        const span = btn.find('span');

        btn.daterangepicker({
            autoUpdateInput: false, // Importante para permitir estado "vacío"
            opens: 'left',
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Aplicar',
                cancelLabel: 'Limpiar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Personalizado',
                weekLabel: 'S',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                firstDay: 1
            },
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        // Evento: Aplicar fecha
        btn.on('apply.daterangepicker', function(ev, picker) {
            fechaInicio = picker.startDate;
            fechaFin = picker.endDate;
            
            // Actualizar texto del botón visualmente
            span.html(fechaInicio.format('DD/MM/YYYY') + ' - ' + fechaFin.format('DD/MM/YYYY'));
            
            // Recargar tabla
            cargarTickets(1);
        });

        // Evento: Cancelar / Limpiar fecha
        btn.on('cancel.daterangepicker', function(ev, picker) {
            fechaInicio = null;
            fechaFin = null;
            span.html('Seleccionar fechas');
            picker.setStartDate(moment());
            picker.setEndDate(moment());
            
            cargarTickets(1);
        });
    }

    // --- FUNCIÓN LIMPIAR TODOS LOS FILTROS ---
    function limpiarFiltros() {
        // Reset inputs HTML
        $('#searchFolio').val('');
        $('#filterEstatus').val('');
        $('#filterSucursal').val('');

        // Reset Variables Fecha
        fechaInicio = null;
        fechaFin = null;

        // Reset Visual DatePicker
        $('#daterange-btn span').html('Seleccionar fechas');
        // Opcional: Resetear la selección interna del picker a "Hoy"
        $('#daterange-btn').data('daterangepicker').setStartDate(moment());
        $('#daterange-btn').data('daterangepicker').setEndDate(moment());

        cargarTickets(1);
    }

    // --- CONSULTA AJAX ---
    function cargarTickets(pagina = 1) {
        $('#loading').show();
        $('#ticketsContainer').empty(); // Usar empty de jquery es más seguro

        const params = new URLSearchParams();
        params.append('pagina', pagina);

        // Obtener valores de los inputs
        const folio = $('#searchFolio').val().trim();
        const estatus = $('#filterEstatus').val();
        const sucursal = $('#filterSucursal').val();

        if (folio) params.append('folio', folio);
        if (estatus) params.append('estatus', estatus);
        if (sucursal) params.append('sucursal', sucursal);

        // Enviar fechas en formato SQL (YYYY-MM-DD)
        if (fechaInicio && fechaFin) {
            params.append('fecha_desde', fechaInicio.format('YYYY-MM-DD'));
            params.append('fecha_hasta', fechaFin.format('YYYY-MM-DD'));
        }

        fetch('core/consultar-tickets.php?' + params)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    datosTickets = data.tickets;
                    renderizarTickets();
                    actualizarResumen(data.resumen);
                    generarPaginacion(data.total_paginas, pagina);
                } else {
                    // Mostrar mensaje amigable si no hay tickets o error
                    $('#ticketsContainer').html(`<div class="col-12 text-center text-muted py-5">${data.message || 'No se encontraron resultados'}</div>`);
                }
            })
            .catch(e => {
                console.error(e);
                $('#ticketsContainer').html('<div class="col-12 text-center text-danger">Error de conexión</div>');
            })
            .finally(() => $('#loading').hide());
    }

    // --- UTILIDADES ---
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // Mantener tus funciones de renderizado existentes (aplicarVista, renderizarTickets, etc.)
    // ... (Tu código existente para renderizarTickets, generarHTMLProductos, etc. va aquí sin cambios) ...
    // Solo asegúrate de que 'renderizarTickets' usa la variable global 'datosTickets' que ya declaramos arriba.

    function aplicarVista() {
        config.modo = document.getElementById('vAgrupado').checked ? 'agrupado' : 'desglosado';
        config.expandir = document.getElementById('checkExpandirTodo').checked;
        renderizarTickets();
    }

    function renderizarTickets() {
        const container = document.getElementById('ticketsContainer');
        container.innerHTML = '';

        if (!datosTickets || datosTickets.length === 0) {
            container.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="bi bi-inbox fs-1 d-block mb-2"></i>No se encontraron tickets</div>';
            return;
        }

        datosTickets.forEach(ticket => {
             // (Aquí va TU código original del interior del forEach para generar el HTML de la tarjeta)
             // Simplemente copialo de tu versión anterior, esa parte visual estaba bien.
             const esFacturado = ticket.estatus === 'facturado';
             const colorEstado = esFacturado ? 'success' : 'warning';
             const displayDetalle = config.expandir ? 'block' : 'none';
             const iconDetalle = config.expandir ? 'bi-chevron-up' : 'bi-chevron-down';
             const tablaProductos = generarHTMLProductos(ticket.productos);

             const html = `
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 mb-2">
                        <div class="card-header bg-white py-3" style="cursor:pointer" onclick="toggleDetalle(${ticket.id_ticket})">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <span class="badge bg-${colorEstado} me-2">${ticket.estatus}</span>
                                    <span class="fw-bold text-dark">${ticket.folio_ticket}</span>
                                </div>
                                <div class="col-md-4 text-muted small">
                                    ${ticket.sucursal_fmt || 'Sucursal'}<br>
                                    <i class="bi bi-calendar me-1"></i>${ticket.fecha_fmt}
                                </div>
                                <div class="col-md-3 text-end">
                                    <h5 class="mb-0 fw-bold text-primary">$${ticket.importe_fmt}</h5>
                                    <small class="text-muted">${ticket.items_count} productos</small>
                                </div>
                                <div class="col-md-2 text-end">
                                    <i class="bi ${iconDetalle} text-muted" id="icon-${ticket.id_ticket}"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-light border-top" id="body-${ticket.id_ticket}" style="display:${displayDetalle}">
                            <div class="row">
                                <div class="col-md-9">${tablaProductos}</div>
                                <div class="col-md-3 d-flex align-items-center justify-content-center border-start">
                                    ${!esFacturado 
                                        ? `<button class="btn btn-success w-100 shadow-sm" onclick="facturar(${ticket.id_ticket})"><i class="bi bi-lightning-charge me-2"></i>Facturar</button>` 
                                        : `<button class="btn btn-outline-secondary w-100" onclick="descargar(${ticket.id_ticket})"><i class="bi bi-download me-2"></i>XML / PDF</button>`
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            container.innerHTML += html;
        });
    }
    
    // Tus otras funciones auxiliares (generarHTMLProductos, toggleDetalle, actualizarResumen, etc) se mantienen igual.
    function generarHTMLProductos(productos) {
        if (!productos || productos.length === 0) return '<small>Sin detalles</small>';
        let lista = [];
        if (config.modo === 'agrupado') {
            const agrupador = {};
            productos.forEach(p => {
                const key = p.id_prod_serv;
                if (!agrupador[key]) {
                    agrupador[key] = { ...p, cant: parseFloat(p.cant), importe: parseFloat(p.importe) };
                } else {
                    agrupador[key].cant += parseFloat(p.cant);
                    agrupador[key].importe += parseFloat(p.importe);
                }
            });
            lista = Object.values(agrupador);
        } else {
            lista = productos;
        }

        let html = `<div class="table-responsive"><table class="table table-sm table-borderless small mb-0"><thead class="text-secondary border-bottom"><tr><th>Cód. SAT</th><th>Descripción</th><th class="text-center">Cant.</th><th class="text-end">P. Unit</th><th class="text-end">Importe</th></tr></thead><tbody>`;
        lista.forEach(p => {
            html += `<tr><td><span class="badge bg-light text-dark border">${p.id_prod_serv}</span></td><td>${p.descr}</td><td class="text-center fw-bold">${parseFloat(p.cant).toFixed(2)}</td><td class="text-end text-muted">$${parseFloat(p.precio_unit).toFixed(2)}</td><td class="text-end fw-bold">$${parseFloat(p.importe).toFixed(2)}</td></tr>`;
        });
        html += `</tbody></table></div>`;
        return html;
    }

    function toggleDetalle(id) {
        const el = document.getElementById(`body-${id}`);
        const icon = document.getElementById(`icon-${id}`);
        if (el.style.display === 'none') {
            el.style.display = 'block';
            icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
        } else {
            el.style.display = 'none';
            icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
        }
    }

    function actualizarResumen(resumen) {
        if(!resumen) return;
        document.getElementById('lblPendientes').innerText = resumen.pendientes;
        document.getElementById('lblFacturados').innerText = resumen.facturados;
        document.getElementById('lblImporte').innerText = '$' + resumen.importe_fmt;
    }

    function generarPaginacion(total, actual) {
        const div = document.getElementById('paginacion');
        if (total <= 1) { div.innerHTML = ''; return; }
        let html = `<nav><ul class="pagination">`;
        for (let i = 1; i <= total; i++) {
            html += `<li class="page-item ${i===actual?'active':''}"><button class="page-link" onclick="cargarTickets(${i})">${i}</button></li>`;
        }
        html += `</ul></nav>`;
        div.innerHTML = html;
    }

    function facturar(id) { alert('Facturando ticket ' + id); }
    function descargar(id) { alert('Descargando archivos ticket ' + id); }
</script>