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
                                        <!-- Opciones se cargan dinámicamente -->
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

            <!-- Resumen de Tickets -->
            <div class="row g-4 py-2" id="ticketsResumen">
                <div class="col-md-3">
                    <div class="card bg-primary text-white border-0 rounded-4">
                        <div class="card-body text-center">
                            <i class="bi bi-ticket-detailed display-6 mb-2"></i>
                            <h4 class="mb-1" id="totalTickets">0</h4>
                            <small>Total Tickets</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark border-0 rounded-4">
                        <div class="card-body text-center">
                            <i class="bi bi-clock-history display-6 mb-2"></i>
                            <h4 class="mb-1" id="ticketsPendientes">0</h4>
                            <small>Pendientes</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white border-0 rounded-4">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle display-6 mb-2"></i>
                            <h4 class="mb-1" id="ticketsFacturados">0</h4>
                            <small>Facturados</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white border-0 rounded-4">
                        <div class="card-body text-center">
                            <i class="bi bi-currency-dollar display-6 mb-2"></i>
                            <h4 class="mb-1" id="totalImporte">$0.00</h4>
                            <small>Importe Total</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Loading State -->
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
            
            <!-- Lista de Tickets -->
            <div class="row g-4 py-4" id="ticketsContainer" style="display: none;">
                <!-- Los tickets se cargan dinámicamente aquí -->
            </div>
            <!-- Paginación -->
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
                                        <!-- Paginación dinámica -->
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
        // Variables globales
        let paginaActual = 1;
        let ticketsData = [];
        let sucursalesData = [];
        
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar tickets al iniciar
            cargarTickets();
            
            // Configurar eventos de filtros
            document.getElementById('searchFolio').addEventListener('input', debounce(aplicarFiltros, 300));
            document.getElementById('filterSucursal').addEventListener('change', aplicarFiltros);
            document.getElementById('filterFecha').addEventListener('change', aplicarFiltros);
            document.getElementById('filterMonto').addEventListener('input', debounce(aplicarFiltros, 500));
            document.getElementById('filterEstadoFacturacion').addEventListener('change', aplicarFiltros);
        });
        
        // Función para cargar tickets desde el servidor
        function cargarTickets(pagina = 1) {
            paginaActual = pagina;
            
            // Mostrar loading
            document.getElementById('loadingState').style.display = 'block';
            document.getElementById('ticketsContainer').style.display = 'none';
            document.getElementById('paginacionContainer').style.display = 'none';
            
            // Construir parámetros de la URL
            const params = new URLSearchParams({
                pagina: pagina,
                limite: 20
            });
            
            // Agregar filtros si existen
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ticketsData = data.tickets;
                        mostrarTickets(data);
                        actualizarResumen(data.resumen);
                        actualizarPaginacion(data);
                        cargarSucursales(data.tickets);
                    } else {
                        mostrarError(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarError('Error de conexión al cargar tickets');
                });
        }
        
        // Función para mostrar los tickets en el template
        function mostrarTickets(data) {
            const container = document.getElementById('ticketsContainer');
            
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
            
            // Ocultar loading y mostrar contenido
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('ticketsContainer').style.display = 'block';
        }
        
        // Función para crear el HTML de un ticket
        function crearTicketHTML(ticket) {
            const estadoClass = ticket.estatus === 'facturado' ? 'bg-success' : 'bg-warning text-dark';
            const estadoTexto = ticket.estatus === 'facturado' ? 'Facturado' : 'Pendiente';
            
            let urgenciaClass = 'text-success';
            if (ticket.urgencia === 'alta') urgenciaClass = 'text-danger';
            else if (ticket.urgencia === 'media') urgenciaClass = 'text-warning';
            
            return `
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h6 class="fw-bold text-primary mb-1">${ticket.folio_ticket}</h6>
                                        <small class="text-muted">${ticket.sucursal_completa}</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="mb-1">Productos: <span class="text-primary">${ticket.total_productos}</span></h6>
                                    <small class="text-muted">Estado: </small>
                                    <span class="badge ${estadoClass}">${estadoTexto}</span>
                                    <br>
                                    <small class="text-muted" title="${ticket.productos_detalle || 'Sin detalles'}">
                                        <i class="bi bi-receipt me-1"></i>${(ticket.productos_detalle || 'Sin productos').substring(0, 40)}${ticket.productos_detalle && ticket.productos_detalle.length > 40 ? '...' : ''}
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h6 class="text-success mb-1">$${ticket.importe_formateado}</h6>
                                        <small class="text-muted">Importe Total</small>
                                        <br>
                                        <small class="text-muted">
                                            Subtotal: $${ticket.subtotal_formateado}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted d-block">Fecha de Venta:</small>
                                    <strong>${ticket.fecha_formateada}</strong>
                                    <br>
                                    <small class="${urgenciaClass}">
                                        <i class="bi bi-clock me-1"></i>${ticket.mensaje_urgencia}
                                    </small>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="btn-group-vertical" role="group">
                                        ${ticket.estatus === 'pendiente' ? `
                                            <button class="btn btn-success btn-sm mb-2" onclick="facturarTicket(${ticket.id_ticket})">
                                                <i class="bi bi-check-circle me-1"></i>Facturar
                                            </button>
                                        ` : `
                                            <button class="btn btn-outline-success btn-sm mb-2" disabled>
                                                <i class="bi bi-file-earmark-check me-1"></i>Facturado
                                            </button>
                                        `}
                                        <button class="btn btn-outline-primary btn-sm" onclick="verDetallesTicket(${ticket.id_ticket})">
                                            <i class="bi bi-eye me-1"></i>Ver Detalles
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Función para actualizar el resumen
        function actualizarResumen(resumen) {
            document.getElementById('totalTickets').textContent = resumen.pendientes + resumen.facturados;
            document.getElementById('ticketsPendientes').textContent = resumen.pendientes;
            document.getElementById('ticketsFacturados').textContent = resumen.facturados;
            document.getElementById('totalImporte').textContent = `$${resumen.total_importe_formateado}`;
        }
        
        // Función para cargar sucursales únicas
        function cargarSucursales(tickets) {
            const sucursales = [...new Map(tickets.map(t => [t.id_empresa, {
                id: t.id_empresa,
                nombre: t.sucursal_completa
            }])).values()];
            
            const select = document.getElementById('filterSucursal');
            const currentValue = select.value;
            
            select.innerHTML = '<option value="">Todas las sucursales</option>';
            sucursales.forEach(sucursal => {
                select.innerHTML += `<option value="${sucursal.id}">${sucursal.nombre}</option>`;
            });
            
            if (currentValue) select.value = currentValue;
        }
        
        // Función para actualizar paginación
        function actualizarPaginacion(data) {
            const container = document.getElementById('paginacionContainer');
            const paginacion = document.getElementById('paginacion');
            const info = document.getElementById('infoPaginacion');
            
            if (data.total_paginas <= 1) {
                container.style.display = 'none';
                return;
            }
            
            // Actualizar información
            const inicio = (data.pagina_actual - 1) * data.limite + 1;
            const fin = Math.min(data.pagina_actual * data.limite, data.total_tickets);
            info.textContent = `Mostrando ${inicio}-${fin} de ${data.total_tickets} tickets`;
            
            // Generar paginación
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
        
        // Función para aplicar filtros
        function aplicarFiltros() {
            cargarTickets(1); // Resetear a la primera página
        }
        
        // Función para mostrar errores
        function mostrarError(mensaje) {
            const container = document.getElementById('ticketsContainer');
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
            
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('ticketsContainer').style.display = 'block';
        }
        
        // Función debounce para evitar muchas llamadas
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
        
        // Función para facturar ticket
        function facturarTicket(idTicket) {
            if (confirm('¿Desea procesar la factura para este ticket?')) {
                // Implementar lógica de facturación
                alert('Función de facturación en desarrollo');
            }
        }
        
        // Función para ver detalles del ticket
        function verDetallesTicket(idTicket) {
            // Mostrar modal con loading
            const modal = new bootstrap.Modal(document.getElementById('ticketDetailsModal'));
            const modalBody = document.querySelector('#ticketDetailsModal .modal-body');
            const modalTitle = document.querySelector('#ticketDetailsModalLabel');
            
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
            
            // Obtener detalles completos del servidor
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
                    
                    // Construir tabla de productos
                    let tablaProductos = '';
                    if (productos && productos.length > 0) {
                        tablaProductos = `
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID Prod.</th>
                                            <th>Descripción</th>
                                            <th class="text-center">Cant.</th>
                                            <th class="text-end">P. Unit.</th>
                                            <th class="text-end">Importe</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                        
                        productos.forEach(producto => {
                            tablaProductos += `
                                <tr>
                                    <td>${producto.id_prod_serv}</td>
                                    <td>${producto.descr}</td>
                                    <td class="text-center">${producto.cant}</td>
                                    <td class="text-end">$${parseFloat(producto.precio_unit).toLocaleString('es-MX', {minimumFractionDigits: 2})}</td>
                                    <td class="text-end">$${parseFloat(producto.importe).toLocaleString('es-MX', {minimumFractionDigits: 2})}</td>
                                </tr>`;
                        });
                        
                        tablaProductos += `
                                    </tbody>
                                </table>
                            </div>`;
                    } else {
                        tablaProductos = '<p class="text-muted mb-0">No hay productos registrados para este ticket</p>';
                    }
                    
                    modalBody.innerHTML = `
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary"><i class="bi bi-info-circle me-2"></i>Información General</h6>
                                        <p><strong>Folio:</strong> ${ticket.folio_ticket}</p>
                                        <p><strong>Sucursal:</strong> ${ticket.nombre_sucursal} (${ticket.codigo_suc})</p>
                                        <p><strong>RFC:</strong> ${ticket.rfc}</p>
                                        <p><strong>Fecha de Venta:</strong> ${new Date(ticket.fecha_venta).toLocaleDateString('es-MX')}</p>
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
                                        <p><strong>Subtotal:</strong> $${parseFloat(ticket.subtotal).toLocaleString('es-MX', {minimumFractionDigits: 2})}</p>
                                        <p><strong>Impuestos:</strong> $${parseFloat(ticket.impuesto_t).toLocaleString('es-MX', {minimumFractionDigits: 2})}</p>
                                        <hr>
                                        <p><strong>Total:</strong> <span class="h5 text-success">$${parseFloat(ticket.importe_t).toLocaleString('es-MX', {minimumFractionDigits: 2})}</span></p>
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
                                                <p><strong>Método:</strong> ${metodoPago.metodo_pago}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Forma de Pago:</strong> ${metodoPago.forma_pago}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Monto:</strong> $${parseFloat(metodoPago.monto).toLocaleString('es-MX', {minimumFractionDigits: 2})}</p>
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
                        <p class="text-muted">No se pudo conectar con el servidor</p>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                `;
            });
        }
    </script>