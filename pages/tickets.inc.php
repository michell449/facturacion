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

    function facturar(id) {
        alert('Facturando ticket ' + id);
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
            document.getElementById('filtrosActivos').style.display = 'none';

            // Recargar tickets
            cargarTickets(1);
            
            mostrarMensajeTemporal('Filtros limpiados correctamente', 'success');
        } catch (error) {
            console.error('Error al limpiar filtros:', error);
        }
    }
</script>

