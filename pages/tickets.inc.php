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
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary w-100" id="daterange-btn">
                                <i class="bi bi-calendar-range me-2"></i>
                                <span>Seleccionar fechas</span>
                                <i class="bi bi-chevron-down ms-2"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-secondary w-100" onclick="limpiarFiltros()" title="Limpiar filtros">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-info w-100" type="button" data-bs-toggle="collapse" data-bs-target="#panelConfig">
                            <i class="bi bi-gear"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="collapse mb-4" id="panelConfig">
            <div class="card card-body border-info bg-light">
                <h6 class="text-info fw-bold"><i class="bi bi-eye me-2"></i>Opciones de Visualización</h6>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Modo de Productos:</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="vistaModo" id="vDesglosado" value="desglosado" checked onchange="aplicarVista()">
                            <label class="btn btn-outline-primary btn-sm" for="vDesglosado"><i class="bi bi-list me-1"></i>Desglosado (Tal cual BD)</label>

                            <input type="radio" class="btn-check" name="vistaModo" id="vAgrupado" value="agrupado" onchange="aplicarVista()">
                            <label class="btn btn-outline-primary btn-sm" for="vAgrupado"><i class="bi bi-collection me-1"></i>Agrupado (Sumar items)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Detalle de Tickets:</label>
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" id="checkExpandirTodo" onchange="aplicarVista()">
                            <label class="form-check-label" for="checkExpandirTodo">Expandir todos los productos automáticamente</label>
                        </div>
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

<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
<script>
    let datosTickets = [];
    let config = {
        modo: 'desglosado',
        expandir: false
    };

    let fechaInicio = null;
    let fechaFin = null;

    document.addEventListener('DOMContentLoaded', () => {
        cargarTickets();
        inicializarDateRangePicker();

        // Listeners filtros
        document.getElementById('searchFolio').addEventListener('input', debounce(() => cargarTickets(1), 500));
        document.getElementById('filterEstatus').addEventListener('change', () => cargarTickets(1));
        document.getElementById('filterSucursal').addEventListener('change', () => cargarTickets(1));
    });

    function cargarTickets(pagina = 1) {
        document.getElementById('loading').style.display = 'block';
        document.getElementById('ticketsContainer').innerHTML = '';

        const params = new URLSearchParams();
        params.append('pagina', pagina);

        // Filtros básicos
        const folio = document.getElementById('searchFolio').value.trim();
        const estatus = document.getElementById('filterEstatus').value;
        const sucursal = document.getElementById('filterSucursal').value;

        if (folio) params.append('folio', folio);
        if (estatus) params.append('estatus', estatus);
        if (sucursal) params.append('sucursal', sucursal);

        // Agregar fechas si están seleccionadas
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
                    alert(data.message);
                }
            })
            .catch(e => console.error(e))
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

            // Generar HTML de productos según configuración
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
                                            ? `<button class="btn btn-success w-100 shadow-sm" onclick="facturar(${ticket.id_ticket})">
                                                 <i class="bi bi-lightning-charge me-2"></i>Facturar
                                               </button>` 
                                            : `<button class="btn btn-outline-secondary w-100" onclick="descargar(${ticket.id_ticket})">
                                                <i class="bi bi-download me-2"></i>XML / PDF
                                            </button>`
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

    function generarPaginacion(total, actual) {
        const div = document.getElementById('paginacion');
        if (total <= 1) {
            div.innerHTML = '';
            return;
        }

        let html = `<nav><ul class="pagination">`;
        for (let i = 1; i <= total; i++) {
            html += `<li class="page-item ${i===actual?'active':''}"><button class="page-link" onclick="cargarTickets(${i})">${i}</button></li>`;
        }
        html += `</ul></nav>`;
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

    function facturar(id) {
        alert('Facturando ticket ' + id);
    }

    function descargar(id) {
        alert('Descargando archivos ticket ' + id);
    }

    function inicializarDateRangePicker() {
        // Esperar a que jQuery y las librerías estén disponibles
        if (typeof $ === 'undefined' || typeof moment === 'undefined') {
            setTimeout(inicializarDateRangePicker, 100);
            return;
        }

        $('#daterange-btn').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Rango personalizado',
                weekLabel: 'S',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                monthNames: [
                    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ],
                firstDay: 1
            },
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                'Este mes': [moment().startOf('month'), moment().endOf('month')],
                'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        // Evento al aplicar el rango de fechas
        $('#daterange-btn').on('apply.daterangepicker', function(ev, picker) {
            fechaInicio = picker.startDate;
            fechaFin = picker.endDate;

            // Actualizar el texto del botón
            $(this).find('span').html(
                fechaInicio.format('DD/MM/YYYY') + ' - ' + fechaFin.format('DD/MM/YYYY')
            );

            // Recargar tickets con el filtro de fecha
            cargarTickets(1);
        });

        // Evento al cancelar
        $('#daterange-btn').on('cancel.daterangepicker', function(ev, picker) {
            fechaInicio = null;
            fechaFin = null;

            // Restaurar texto original
            $(this).find('span').html('Seleccionar fechas');

            // Recargar tickets sin filtro de fecha
            cargarTickets(1);
        });
    }

    // Función para limpiar todos los filtros
    function limpiarFiltros() {
        document.getElementById('searchFolio').value = '';
        document.getElementById('filterEstatus').value = '';
        document.getElementById('filterSucursal').value = '';

        // Limpiar date range picker
        fechaInicio = null;
        fechaFin = null;
        $('#daterange-btn span').html('Seleccionar fechas');

        cargarTickets(1);
    }
</script>
