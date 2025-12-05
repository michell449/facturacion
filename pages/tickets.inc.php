<div class="content-wrapper bg-light loaded">
    <div class="container py-4">

        <div class="row mb-4 align-items-center">
            <div class="col-8">
                <h2 class="text-primary fw-bold mb-0"><i class="bi bi-ticket-detailed me-2"></i>Gestión de Tickets</h2>
            </div>
            <div class="col-4 text-end">
                <button class="btn btn-outline-primary" onclick="window.history.back()">Regresar</button>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" class="form-control bg-light border-0" id="searchFolio" placeholder="Buscar folio...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select bg-light border-0" id="filterEstatus">
                            <option value="">Todos los estatus</option>
                            <option value="pendiente">Pendientes</option>
                            <option value="facturado">Facturados</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select border-0 bg-light" id="filterSucursal">
                            <option value="">Todas las sucursales</option>
                            <!-- Opciones de sucursales se cargarán dinámicamente -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="dateSelect" class="form-select border-0 bg-light" onchange="aplicarRango()">
                            <option value="todos_fechas" selected>Todos</option>
                            <option value="hoy">Hoy</option>
                            <option value="ayer">Ayer</option>
                            <option value="7dias">Últimos 7 días</option>
                            <option value="este_mes">Este Mes</option>
                            <option value="mes_pasado">Mes Pasado</option>
                            <option value="RangoPersonalizado">Rango Personalizado</option>
                        </select>
                        
                        <!-- Calendarios HTML5 para rango personalizado -->
                        <div id="rangoPersonalizado" class="mt-2" style="display: none;">
                            <div class="row g-1">
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1">Desde:</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaDesde" onchange="aplicarRangoPersonalizado()">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted mb-1">Hasta:</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaHasta" onchange="aplicarRangoPersonalizado()">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()" title="Limpiar filtros">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4 text-center">
            <div class="col-4">
                <div class="p-3 bg-info bg-opacity-10 rounded-4 shadow-sm">
                    <h5 class="text-primary fw-bold mb-0" id="lblPendientes">0</h5>
                    <small class="text-muted">Pendientes</small>
                </div>
            </div>
            <div class="col-4">
                <div class="p-3 bg-info bg-opacity-10 rounded-4 shadow-sm">
                    <h5 class="text-primary fw-bold mb-0" id="lblFacturados">0</h5>
                    <small class="text-muted">Facturados</small>
                </div>
            </div>
            <div class="col-4">
                <div class="p-3 bg-info bg-opacity-10 rounded-4 shadow-sm">
                    <h5 class="text-primary fw-bold mb-0" id="lblImporte">$0.00</h5>
                    <small class="text-muted">Importe Total</small>
                </div>
            </div>
        </div>

        <div id="loading" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
        </div>
        <div id="ticketsContainer" class="row g-3"></div>

        <div id="paginacion" class="d-flex justify-content-center mt-4"></div>
    </div>
</div>

<!-- Modal de Previsualización de Factura -->
<div class="modal fade" id="modalPrevisualizacion" tabindex="-1" aria-labelledby="modalPrevisualizacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="modalPrevisualizacionLabel">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Vista Previa de Factura
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Panel de datos del receptor -->
                    <div class="col-lg-4 bg-light p-4 border-end">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-person-fill me-2"></i>Datos del Receptor
                        </h6>
                        <form id="formDatosReceptor">
                            <div class="mb-3">
                                <label for="receptorRFC" class="form-label fw-semibold small">RFC del Cliente *</label>
                                <input type="text" class="form-control text-uppercase" id="receptorRFC" placeholder="XAXX010101000" maxlength="13" required>
                            </div>
                            <div class="mb-3">
                                <label for="receptorNombre" class="form-label fw-semibold small">Razón Social *</label>
                                <input type="text" class="form-control" id="receptorNombre" placeholder="Nombre o Razón Social" required>
                            </div>
                            <div class="mb-3">
                                <label for="receptorCP" class="form-label fw-semibold small">Código Postal *</label>
                                <input type="text" class="form-control" id="receptorCP" placeholder="12345" maxlength="5" required>
                            </div>
                            <div class="mb-3">
                                <label for="receptorRegimen" class="form-label fw-semibold small">Régimen Fiscal *</label>
                                <select class="form-select" id="receptorRegimen" required>
                                    <option value="">Selecciona régimen...</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="receptorUsoCFDI" class="form-label fw-semibold small">Uso de CFDI *</label>
                                <select class="form-select" id="receptorUsoCFDI" required>
                                    <option value="">Selecciona uso...</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="receptorFormaPago" class="form-label fw-semibold small">Forma de Pago *</label>
                                <select class="form-select" id="receptorFormaPago" required>
                                    <option value="">Selecciona forma...</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="receptorEmail" class="form-label fw-semibold small">Correo Electrónico</label>
                                <input type="email" class="form-control" id="receptorEmail" placeholder="correo@ejemplo.com">
                            </div>
                        </form>
                    </div>
                    
                    <!-- Panel de vista previa -->
                    <div class="col-lg-8 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-primary mb-0">
                                <i class="bi bi-eye me-2"></i>Vista Previa
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="actualizarPrevisualizacion()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
                            </button>
                        </div>
                        
                        <!-- Contenedor de la factura -->
                        <div id="facturaPreviewContainer" class="border rounded-3 bg-white shadow-sm" style="max-height: 60vh; overflow-y: auto;">
                            <div class="p-4" id="facturaPreviewContent">
                                <!-- Header de la factura -->
                                <div class="row mb-4 pb-3 border-bottom">
                                    <div class="col-6">
                                        <div id="previewEmisorLogo" class="mb-2">
                                            <div class="bg-primary text-white px-3 py-2 rounded d-inline-block">LOGO</div>
                                        </div>
                                        <h5 class="fw-bold mb-1" id="previewEmisorNombre">Empresa Emisora</h5>
                                        <p class="text-muted small mb-1" id="previewEmisorRFC">RFC: ---</p>
                                        <p class="text-muted small mb-0" id="previewEmisorDireccion">Dirección fiscal</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h4 class="fw-bold text-primary mb-2">FACTURA</h4>
                                        <p class="mb-1"><strong>Serie:</strong> <span id="previewSerie">A</span></p>
                                        <p class="mb-1"><strong>Folio:</strong> <span id="previewFolio">001</span></p>
                                        <p class="mb-1"><strong>Fecha:</strong> <span id="previewFecha"><?php echo date('d/m/Y H:i'); ?></span></p>
                                        <p class="mb-0"><strong>Ticket:</strong> <span id="previewTicketFolio">---</span></p>
                                    </div>
                                </div>
                                
                                <!-- Datos del receptor -->
                                <div class="row mb-4 pb-3 border-bottom">
                                    <div class="col-6">
                                        <h6 class="fw-bold text-secondary">RECEPTOR</h6>
                                        <p class="fw-bold mb-1" id="previewReceptorNombre">Nombre del Cliente</p>
                                        <p class="text-muted small mb-1" id="previewReceptorRFC">RFC: ---</p>
                                        <p class="text-muted small mb-0" id="previewReceptorCP">C.P.: ---</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <p class="mb-1"><strong>Uso CFDI:</strong> <span id="previewUsoCFDI">---</span></p>
                                        <p class="mb-1"><strong>Forma Pago:</strong> <span id="previewFormaPago">---</span></p>
                                        <p class="mb-0"><strong>Método Pago:</strong> <span id="previewMetodoPago">PUE</span></p>
                                    </div>
                                </div>
                                
                                <!-- Tabla de conceptos -->
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-primary">
                                            <tr>
                                                <th style="width: 15%">Clave SAT</th>
                                                <th>Descripción</th>
                                                <th class="text-center" style="width: 10%">Cant.</th>
                                                <th class="text-end" style="width: 12%">P. Unit.</th>
                                                <th class="text-end" style="width: 12%">Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody id="previewConceptos">
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Cargando conceptos...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Totales -->
                                <div class="row">
                                    <div class="col-7">
                                        <div class="bg-light p-3 rounded">
                                            <p class="small mb-1"><strong>Moneda:</strong> MXN - Peso Mexicano</p>
                                            <p class="small mb-0"><strong>Tipo Comprobante:</strong> I - Ingreso</p>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <td class="text-end"><strong>Subtotal:</strong></td>
                                                <td class="text-end" id="previewSubtotal">$0.00</td>
                                            </tr>
                                            <tr>
                                                <td class="text-end"><strong>IVA (16%):</strong></td>
                                                <td class="text-end" id="previewIVA">$0.00</td>
                                            </tr>
                                            <tr class="border-top">
                                                <td class="text-end"><h5 class="fw-bold mb-0">Total:</h5></td>
                                                <td class="text-end"><h5 class="fw-bold text-primary mb-0" id="previewTotal">$0.00</h5></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Pie de factura -->
                                <div class="mt-4 pt-3 border-top">
                                    <div class="row">
                                        <div class="col-3 text-center">
                                            <div class="border p-3 rounded bg-light">
                                                <i class="bi bi-qr-code display-5 text-muted"></i>
                                                <small class="d-block text-muted mt-1">Código QR</small>
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <p class="small text-muted mb-1" id="previewLeyenda">Este documento es una representación impresa de un CFDI</p>
                                            <p class="small text-muted mb-0"><strong>Sello Digital:</strong> <span class="text-break" style="font-size: 0.7rem;">••••••••••••••••••••••••••••••••</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom-4">
                <div class="d-flex justify-content-between w-100">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </button>
                    <div>
                        <button type="button" class="btn btn-outline-primary me-2" onclick="imprimirPrevisualizacion()">
                            <i class="bi bi-printer me-2"></i>Imprimir
                        </button>
                        <button type="button" class="btn btn-success" onclick="confirmarFacturacion()">
                            <i class="bi bi-check-circle me-2"></i>Generar Factura
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let datosTickets = [];
    let config = {
        modo: 'desglosado',
        expandir: false
    };

    let fechaInicio = null;
    let fechaFin = null;

    document.addEventListener('DOMContentLoaded', () => {
        // Inicializar sin filtros de fecha (mostrar todos)
        fechaInicio = null;
        fechaFin = null;

        cargarSucursales();
        cargarTickets();

        // Listeners filtros
        document.getElementById('searchFolio').addEventListener('input', debounce(() => cargarTickets(1), 500));
        document.getElementById('filterEstatus').addEventListener('change', () => cargarTickets(1));
        document.getElementById('filterSucursal').addEventListener('change', () => cargarTickets(1));
    });

    // Función para cargar sucursales
    function cargarSucursales() {
        console.log('Cargando sucursales...');
        fetch('core/consultar-tickets.php?obtener_sucursales=1')
            .then(r => {
                if (!r.ok) {
                    throw new Error(`HTTP ${r.status}: ${r.statusText}`);
                }
                return r.text();
            })
            .then(text => {
                console.log('Respuesta sucursales (raw):', text.substring(0, 500));
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Respuesta sucursales no es JSON válido:', text.substring(0, 500));
                    throw new Error('Error al cargar sucursales: respuesta inválida del servidor');
                }
            })
            .then(data => {
                console.log('Datos sucursales recibidos:', data);
                if (data.success && data.sucursales) {
                    const select = document.getElementById('filterSucursal');
                    
                    // Limpiar opciones actuales (mantener la primera "Todas las sucursales")
                    while (select.children.length > 1) {
                        select.removeChild(select.lastChild);
                    }
                    
                    // Agregar sucursales
                    data.sucursales.forEach(sucursal => {
                        const option = document.createElement('option');
                        option.value = sucursal.id;
                        option.textContent = `${sucursal.nombre}${sucursal.codigo_suc ? ' (' + sucursal.codigo_suc + ')' : ''}`;
                        select.appendChild(option);
                    });
                    
                    console.log(`${data.sucursales.length} sucursales cargadas correctamente`);
                } else {
                    console.warn('No se encontraron sucursales o error en respuesta:', data.message);
                }
            })
            .catch(e => {
                console.error('Error cargando sucursales:', e);
            });
    }

    function cargarTickets(pagina = 1) {
        document.getElementById('loading').style.display = 'block';
        document.getElementById('ticketsContainer').innerHTML = '';

        const params = new URLSearchParams();
        params.append('pagina', pagina);

        // Filtros básicos
        const folio = document.getElementById('searchFolio').value.trim();
        const estatus = document.getElementById('filterEstatus').value;
        const sucursal = document.getElementById('filterSucursal').value;

        // Log para debugging
        console.log('Filtros aplicados:', {
            folio: folio,
            estatus: estatus,
            sucursal: sucursal,
            fechaInicio: fechaInicio,
            fechaFin: fechaFin
        });

        if (folio) params.append('folio', folio);
        if (estatus) params.append('estatus', estatus);
        if (sucursal) {
            params.append('id_empresa', sucursal);
            // Mostrar indicador visual de filtro aplicado
            const sucursalText = document.getElementById('filterSucursal').selectedOptions[0]?.text || 'sucursal seleccionada';
            console.log('Filtro de sucursal aplicado:', sucursalText);
        }

        // Agregar fechas si están seleccionadas
        if (fechaInicio && fechaFin) {
            params.append('fecha_desde', formatearFecha(fechaInicio));
            params.append('fecha_hasta', formatearFecha(fechaFin));
        }

        const url = 'core/consultar-tickets.php?' + params;
        console.log('URL de consulta:', url);
        
        fetch(url)
            .then(r => {
                if (r.redirected) throw new Error('Sesión finalizada');
                if (!r.ok) throw new Error(r.statusText);
                return r.text();
            })
            .then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error("Respuesta no válida del servidor:", text);
                    throw new Error("Error en el servidor (HTML recibido)");
                }
            })
            .then(data => {
                if (data.success) {
                    datosTickets = data.tickets || [];
                    renderizarTickets();
                    if (data.resumen) actualizarResumen(data.resumen);
                    if (data.total_paginas) generarPaginacion(data.total_paginas, pagina, data.total_registros || 0);

                    if (datosTickets.length === 0) mostrarMensajeVacio('No se encontraron tickets');
                } else {
                    mostrarMensajeVacio(data.message || 'Error al cargar data');
                }
            })
            .catch(e => {
                console.error(e);
                mostrarMensajeVacio('Error de conexión o sesión expirada');
            })
            .finally(() => document.getElementById('loading').style.display = 'none');
    }

    function aplicarVista() {
        config.modo = document.getElementById('vAgrupado').checked ? 'agrupado' : 'desglosado';
        config.expandir = document.getElementById('checkExpandirTodo').checked;
        renderizarTickets();
    }

    function renderizarTickets() {
        const container = document.getElementById('ticketsContainer');
        container.innerHTML = '';

        if (datosTickets.length === 0) {
            container.innerHTML = '<div class="col-12 text-center text-muted">No se encontraron tickets</div>';
            return;
        }

        datosTickets.forEach(ticket => {
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
                                        ${ticket.sucursal_fmt}<br>
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
                                    <div class="col-md-9">
                                        ${tablaProductos}
                                    </div>
                                    <div class="col-md-3 d-flex align-items-center justify-content-center border-start">
                                        ${!esFacturado 
                                            ? `<button class="btn btn-outline-info w-100 shadow-sm" onclick="facturar(${ticket.id_ticket})">
                                            Facturar
                                            </button>` 
                                            : `
                                            <div class="d-grid gap-2 col-12 mx-auto">
                                                <button class="btn btn-outline-primary w-100" onclick="descargar(${ticket.id_ticket})">
                                                    Reenviar factura
                                                </button>
                                                <button class="btn btn-outline-danger w-100" onclick="descargar(${ticket.id_ticket})">
                                                    Cancelar
                                                </button>
                                            </div>`
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            container.innerHTML += html;
        });
    }

    function generarHTMLProductos(productos) {
        if (!productos || productos.length === 0) return '<small>Sin detalles</small>';

        let lista = [];

        if (config.modo === 'agrupado') {
            // Lógica de agrupación
            const agrupador = {};
            productos.forEach(p => {
                const key = p.id_prod_serv;
                if (!agrupador[key]) {
                    agrupador[key] = {
                        ...p,
                        cant: parseFloat(p.cant),
                        importe: parseFloat(p.importe)
                    };
                } else {
                    agrupador[key].cant += parseFloat(p.cant);
                    agrupador[key].importe += parseFloat(p.importe);
                }
            });
            lista = Object.values(agrupador);
        } else {
            lista = productos;
        }

        let html = `
                <div class="table-responsive">
                    <table class="table table-sm table-borderless small mb-0">
                        <thead class="text-secondary border-bottom">
                            <tr>
                                <th>Cód. SAT</th>
                                <th>Descripción</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">P. Unit</th>
                                <th class="text-end">Importe</th>
                            </tr>
                        </thead>
                        <tbody>`;

        lista.forEach(p => {
            html += `
                    <tr>
                        <td><span class="badge bg-light text-dark border">${p.id_prod_serv}</span></td>
                        <td>${p.descr}</td>
                        <td class="text-center fw-bold">${parseFloat(p.cant).toFixed(2)}</td>
                        <td class="text-end text-muted">$${parseFloat(p.precio_unit).toFixed(2)}</td>
                        <td class="text-end fw-bold">$${parseFloat(p.importe).toFixed(2)}</td>
                    </tr>
                `;
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
        document.getElementById('lblPendientes').innerText = resumen.pendientes;
        document.getElementById('lblFacturados').innerText = resumen.facturados;
        document.getElementById('lblImporte').innerText = '$' + resumen.importe_fmt;
    }

    function generarPaginacion(total, actual, totalRegistros = 0) {
        const div = document.getElementById('paginacion');

        if (total <= 1) {
            // Mostrar info si solo hay una página
            if (totalRegistros > 0) {
                div.innerHTML = `
                    <div class="text-center text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Mostrando ${totalRegistros} ticket(s) en total
                    </div>
                `;
            } else {
                div.innerHTML = '';
            }
            return;
        }

        let html = `
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Página ${actual} de ${total} (${totalRegistros} tickets total)
                </div>
                <nav><ul class="pagination pagination-sm mb-0">
        `;

        // Botón anterior
        if (actual > 1) {
            html += `<li class="page-item"><button class="page-link" onclick="cargarTickets(${actual - 1})">&laquo;</button></li>`;
        }

        // Páginas
        for (let i = 1; i <= total; i++) {
            if (i === 1 || i === total || (i >= actual - 2 && i <= actual + 2)) {
                html += `<li class="page-item ${i===actual?'active':''}">
                            <button class="page-link" onclick="cargarTickets(${i})">${i}</button>
                        </li>`;
            } else if (i === actual - 3 || i === actual + 3) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        // Botón siguiente
        if (actual < total) {
            html += `<li class="page-item"><button class="page-link" onclick="cargarTickets(${actual + 1})">&raquo;</button></li>`;
        }

        html += `</ul></nav></div>`;
        div.innerHTML = html;
    }

    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                func.apply(this, args);
            }, timeout);
        };
    }

    // ============================================
    // VARIABLES Y FUNCIONES DE PREVISUALIZACIÓN
    // ============================================
    
    let ticketSeleccionado = null;
    let configFactura = null;
    let modalPrevisualizacion = null;

    // Inicializar modal
    document.addEventListener('DOMContentLoaded', function() {
        modalPrevisualizacion = new bootstrap.Modal(document.getElementById('modalPrevisualizacion'));
        
        // Cargar catálogos para el formulario del receptor
        cargarCatalogosReceptor();
        
        // Listeners para actualizar preview en tiempo real
        ['receptorRFC', 'receptorNombre', 'receptorCP', 'receptorRegimen', 'receptorUsoCFDI', 'receptorFormaPago'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', actualizarPrevisualizacion);
                el.addEventListener('change', actualizarPrevisualizacion);
            }
        });
    });

    async function cargarCatalogosReceptor() {
        try {
            // Cargar regímenes fiscales
            const resRegimen = await fetch('core/listar-regimen-fiscal.php');
            const dataRegimen = await resRegimen.json();
            if (dataRegimen.success && dataRegimen.data) {
                const select = document.getElementById('receptorRegimen');
                select.innerHTML = '<option value="">Selecciona régimen...</option>';
                dataRegimen.data.forEach(r => {
                    select.innerHTML += `<option value="${r.codigo}">${r.codigo} - ${r.descripcion}</option>`;
                });
            }

            // Cargar usos de CFDI
            const resUso = await fetch('core/listar-uso-cfdi.php');
            const dataUso = await resUso.json();
            if (dataUso.success && dataUso.data) {
                const select = document.getElementById('receptorUsoCFDI');
                select.innerHTML = '<option value="">Selecciona uso...</option>';
                dataUso.data.forEach(u => {
                    select.innerHTML += `<option value="${u.codigo}">${u.codigo} - ${u.descripcion}</option>`;
                });
            }

            // Cargar formas de pago
            const resPago = await fetch('core/listar-formas-pago.php');
            const dataPago = await resPago.json();
            if (dataPago.success && dataPago.data) {
                const select = document.getElementById('receptorFormaPago');
                select.innerHTML = '<option value="">Selecciona forma...</option>';
                dataPago.data.forEach(f => {
                    select.innerHTML += `<option value="${f.clave}">${f.clave} - ${f.description}</option>`;
                });
            }
        } catch (error) {
            console.error('Error cargando catálogos:', error);
        }
    }

    async function cargarConfigFactura() {
        try {
            const response = await fetch('core/obtener-config-facturas.php');
            const result = await response.json();
            if (result.success && result.data) {
                configFactura = result.data;
                aplicarConfigEmisor();
            }
        } catch (error) {
            console.error('Error cargando configuración de factura:', error);
        }
    }

    function aplicarConfigEmisor() {
        if (!configFactura) return;

        // Aplicar datos del emisor
        document.getElementById('previewEmisorNombre').textContent = configFactura.nombreEmpresa || 'Empresa Emisora';
        document.getElementById('previewEmisorRFC').textContent = `RFC: ${configFactura.rfcEmpresa || '---'}`;
        document.getElementById('previewEmisorDireccion').textContent = configFactura.direccionEmpresa || 'Dirección fiscal';
        
        // Serie y folio
        document.getElementById('previewSerie').textContent = configFactura.serieFactura || 'A';
        const folioActual = configFactura.folioActual > 0 ? configFactura.folioActual + 1 : configFactura.folioInicial || 1;
        document.getElementById('previewFolio').textContent = String(folioActual).padStart(3, '0');
        
        // Logo
        if (configFactura.logoEmpresa) {
            document.getElementById('previewEmisorLogo').innerHTML = `<img src="${configFactura.logoEmpresa}" alt="Logo" style="max-height: 60px;">`;
        }
        
        // Leyenda
        if (configFactura.leyendaFactura) {
            document.getElementById('previewLeyenda').textContent = configFactura.leyendaFactura;
        }

        // Valores por defecto en el formulario
        if (configFactura.usoCfdi) {
            document.getElementById('receptorUsoCFDI').value = configFactura.usoCfdi;
        }
        if (configFactura.formaPago) {
            document.getElementById('receptorFormaPago').value = configFactura.formaPago;
        }
    }

    function facturar(idTicket) {
        // Buscar el ticket en los datos cargados
        ticketSeleccionado = datosTickets.find(t => t.id_ticket == idTicket);
        
        if (!ticketSeleccionado) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se encontró el ticket seleccionado'
            });
            return;
        }

        // Limpiar formulario del receptor
        document.getElementById('formDatosReceptor').reset();
        
        // Cargar configuración del emisor
        cargarConfigFactura();
        
        // Mostrar datos del ticket en la preview
        cargarDatosTicketEnPreview();
        
        // Mostrar modal
        modalPrevisualizacion.show();
    }

    function cargarDatosTicketEnPreview() {
        if (!ticketSeleccionado) return;

        // Folio del ticket
        document.getElementById('previewTicketFolio').textContent = ticketSeleccionado.folio_ticket;
        document.getElementById('previewFecha').textContent = ticketSeleccionado.fecha_fmt;

        // Cargar conceptos/productos
        const tbody = document.getElementById('previewConceptos');
        tbody.innerHTML = '';
        
        let subtotal = 0;
        
        if (ticketSeleccionado.productos && ticketSeleccionado.productos.length > 0) {
            ticketSeleccionado.productos.forEach(p => {
                const importe = parseFloat(p.importe) || 0;
                subtotal += importe;
                
                tbody.innerHTML += `
                    <tr>
                        <td><span class="badge bg-light text-dark border">${p.id_prod_serv || '---'}</span></td>
                        <td>${p.descr || 'Producto'}</td>
                        <td class="text-center">${parseFloat(p.cant).toFixed(2)}</td>
                        <td class="text-end">$${parseFloat(p.precio_unit).toFixed(2)}</td>
                        <td class="text-end fw-bold">$${importe.toFixed(2)}</td>
                    </tr>
                `;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Sin conceptos</td></tr>';
        }

        // Calcular totales
        const iva = subtotal * 0.16;
        const total = subtotal + iva;

        document.getElementById('previewSubtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('previewIVA').textContent = '$' + iva.toFixed(2);
        document.getElementById('previewTotal').textContent = '$' + total.toFixed(2);
    }

    function actualizarPrevisualizacion() {
        // Actualizar datos del receptor en la preview
        const rfc = document.getElementById('receptorRFC').value.toUpperCase();
        const nombre = document.getElementById('receptorNombre').value;
        const cp = document.getElementById('receptorCP').value;
        const usoCfdi = document.getElementById('receptorUsoCFDI');
        const formaPago = document.getElementById('receptorFormaPago');

        document.getElementById('previewReceptorNombre').textContent = nombre || 'Nombre del Cliente';
        document.getElementById('previewReceptorRFC').textContent = `RFC: ${rfc || '---'}`;
        document.getElementById('previewReceptorCP').textContent = `C.P.: ${cp || '---'}`;
        
        if (usoCfdi.selectedIndex > 0) {
            document.getElementById('previewUsoCFDI').textContent = usoCfdi.options[usoCfdi.selectedIndex].text;
        }
        if (formaPago.selectedIndex > 0) {
            document.getElementById('previewFormaPago').textContent = formaPago.options[formaPago.selectedIndex].text;
        }
    }

    function imprimirPrevisualizacion() {
        const contenido = document.getElementById('facturaPreviewContent').innerHTML;
        const ventana = window.open('', '_blank');
        ventana.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Vista Previa de Factura</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                    @media print { 
                        body { padding: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    ${contenido}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                    }
                <\/script>
            </body>
            </html>
        `);
        ventana.document.close();
    }

    function confirmarFacturacion() {
        // Validar formulario del receptor
        const form = document.getElementById('formDatosReceptor');
        if (!form.checkValidity()) {
            form.reportValidity();
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor complete todos los campos obligatorios del receptor'
            });
            return;
        }

        // Validar RFC
        const rfc = document.getElementById('receptorRFC').value.trim();
        if (rfc.length < 12 || rfc.length > 13) {
            Swal.fire({
                icon: 'warning',
                title: 'RFC inválido',
                text: 'El RFC debe tener 12 caracteres (persona moral) o 13 caracteres (persona física)'
            });
            return;
        }

        // Confirmar facturación
        Swal.fire({
            title: '¿Confirmar facturación?',
            html: `
                <p>Se generará la factura para el ticket:</p>
                <p class="fw-bold">${ticketSeleccionado.folio_ticket}</p>
                <p class="text-muted">Total: $${ticketSeleccionado.importe_fmt}</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Sí, generar factura',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                generarFactura();
            }
        });
    }

    async function generarFactura() {
        // Mostrar loading
        Swal.fire({
            title: 'Generando factura...',
            html: 'Por favor espere mientras se procesa la factura',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Preparar datos
        const datosFactura = {
            id_ticket: ticketSeleccionado.id_ticket,
            receptor: {
                rfc: document.getElementById('receptorRFC').value.toUpperCase(),
                nombre: document.getElementById('receptorNombre').value,
                codigo_postal: document.getElementById('receptorCP').value,
                regimen_fiscal: document.getElementById('receptorRegimen').value,
                uso_cfdi: document.getElementById('receptorUsoCFDI').value,
                forma_pago: document.getElementById('receptorFormaPago').value,
                email: document.getElementById('receptorEmail').value
            }
        };

        try {
            // TODO: Implementar endpoint de generación de factura
            // const response = await fetch('core/generar-factura.php', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify(datosFactura)
            // });
            // const result = await response.json();

            // Simulación temporal
            await new Promise(resolve => setTimeout(resolve, 2000));

            Swal.fire({
                icon: 'success',
                title: '¡Factura generada!',
                html: `
                    <p>La factura se ha generado correctamente.</p>
                    <p class="text-muted small">Ticket: ${ticketSeleccionado.folio_ticket}</p>
                `,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                modalPrevisualizacion.hide();
                cargarTickets(); // Recargar lista de tickets
            });

        } catch (error) {
            console.error('Error al generar factura:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al generar la factura. Intente nuevamente.'
            });
        }
    }


    function descargar(id) {
        alert('Descargando archivos ticket ' + id);
    }

    function formatearFecha(fecha) {
        return fecha.toISOString().split('T')[0];
    }

    function aplicarRango() {
        const seleccion = document.getElementById('dateSelect').value;
        const hoy = new Date();
        let inicio, fin;
        
        const rangoDiv = document.getElementById('rangoPersonalizado');
        if (seleccion === 'RangoPersonalizado') {
            rangoDiv.style.display = 'block';
            document.getElementById('fechaDesde').value = formatearFecha(hoy);
            document.getElementById('fechaHasta').value = formatearFecha(hoy);
            return; 
        } else {
            rangoDiv.style.display = 'none';
        }

        switch (seleccion) {
            case 'todos_fechas':
                // Sin filtro de fechas - mostrar todos
                fechaInicio = null;
                fechaFin = null;
                break;
            case 'hoy':
                inicio = new Date(hoy);
                fin = new Date(hoy);
                fechaInicio = inicio;
                fechaFin = fin;
                break;
            case 'ayer':
                inicio = new Date(hoy.getTime() - 24 * 60 * 60 * 1000);
                fin = new Date(hoy.getTime() - 24 * 60 * 60 * 1000);
                fechaInicio = inicio;
                fechaFin = fin;
                break;
            case '7dias':
                inicio = new Date(hoy.getTime() - 6 * 24 * 60 * 60 * 1000);
                fin = new Date(hoy);
                fechaInicio = inicio;
                fechaFin = fin;
                break;
            case 'este_mes':
                inicio = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                fin = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
                fechaInicio = inicio;
                fechaFin = fin;
                break;
            case 'mes_pasado':
                inicio = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
                fin = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
                fechaInicio = inicio;
                fechaFin = fin;
                break;
        }

        cargarTickets(1);
    }
    
    function aplicarRangoPersonalizado() {
        const fechaDesde = document.getElementById('fechaDesde').value;
        const fechaHasta = document.getElementById('fechaHasta').value;
        
        if (fechaDesde && fechaHasta) {
            const desde = new Date(fechaDesde);
            const hasta = new Date(fechaHasta);
            
            fechaInicio = desde;
            fechaFin = hasta;
            cargarTickets(1);
        }
    }

    function mostrarMensajeVacio(mensaje = 'No se encontraron tickets') {
        document.getElementById('ticketsContainer').innerHTML = `
            <div class="col-12 text-center text-muted py-5">
                <h5>${mensaje}</h5>
                <p class="small">Intenta ajustar los filtros de búsqueda</p>
                <button class="btn btn-outline-primary btn-sm" onclick="limpiarFiltros()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Limpiar filtros
                </button>
            </div>
        `;
    }

    function limpiarFiltros() {
        try {
            // Limpiar campos de filtro
            document.getElementById('searchFolio').value = '';
            document.getElementById('filterEstatus').value = '';
            document.getElementById('filterSucursal').value = '';
            document.getElementById('dateSelect').value = 'todos_fechas';
            
            // Ocultar calendarios personalizados
            document.getElementById('rangoPersonalizado').style.display = 'none';
            document.getElementById('fechaDesde').value = '';
            document.getElementById('fechaHasta').value = '';

            // Resetear fechas a null (mostrar todos)
            fechaInicio = null;
            fechaFin = null;

            // Ocultar indicadores de filtros
            const filtrosActivos = document.getElementById('filtrosActivos');
            if (filtrosActivos) filtrosActivos.style.display = 'none';

            // Recargar tickets
            cargarTickets(1);
            
            mostrarMensajeTemporal('Filtros limpiados correctamente', 'success');
        } catch (error) {
            console.error('Error al limpiar filtros:', error);
        }
    }

    function mostrarMensajeTemporal(mensaje, tipo = 'info') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
        Toast.fire({
            icon: tipo,
            title: mensaje
        });
    }
</script>

