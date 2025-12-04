<?php
// pages/inicio.inc.php
$fecha_actual = date('d \d\e F \d\e Y');
$hora_actual = date('H:i');
?>
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                        <i class="bi bi-speedometer2 display-6"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-2">¡Bienvenido al Panel de Administración!</h1>
                        <p class="lead mb-0 opacity-90">Controla y gestiona completamente el sistema de facturación</p>

                        <small class="opacity-75">
                            <i class="bi bi-calendar3 me-2"></i><?php echo $fecha_actual; ?>
                            <i class="bi bi-clock ms-3 me-2"></i><?php echo $hora_actual; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Main Dashboard Content -->
<div class="container py-4">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted fw-semibold mb-2">Tickets Pendientes</h6>
                                <h2 class="fw-bold text-warning mb-0">23</h2>
                                <small class="text-success">
                                    <i class="bi bi-arrow-down me-1"></i>-12% vs ayer
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-lg stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted fw-semibold mb-2">Facturas Hoy</h6>
                            <h2 class="fw-bold text-success mb-0">156</h2>
                            <small class="text-success">
                                <i class="bi bi-arrow-up me-1"></i>+18% vs ayer
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-lg stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted fw-semibold mb-2">Sucursales Activas</h6>
                            <h2 class="fw-bold text-primary mb-0">8</h2>
                            <small class="text-primary">
                                <i class="bi bi-building me-1"></i>Todas operativas
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Panel de Gestión  -->
        <div class="col-lg-12">
            <div class="card shadow-lg border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold text-dark mb-3">
                            Gestión
                        </h5>
                    </div>
                </div>
                <div class="card-body p-4 ">
                    <div class="row g-4">
                        <!-- Sucursales -->
                        <div class="col-md-6">
                            <div class="card bg-primary bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-building display-4 text-primary mb-3"></i>
                                    <h6 class="fw-bold text-primary mb-2">Sucursales</h6>
                                    <p class="text-muted mb-3">Administrar ubicaciones y configuraciones</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=gestion-sucursales" class="btn btn-primary btn-sm">
                                            <i class="bi bi-list-ul me-2"></i>Ver Sucursales
                                        </a>
                                        <a href="panel?pg=nueva-sucursal-admin" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-plus-circle me-2"></i>Nueva Sucursal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Facturación -->
                        <div class="col-md-6">
                            <div class="card bg-info bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-receipt-cutoff display-4 text-info mb-3"></i>
                                    <h6 class="fw-bold text-info mb-2">Control de facturas</h6>
                                    <p class="text-muted mb-3">Tickets y facturas generadas</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=tickets" class="btn btn-info btn-sm">
                                            <i class="bi bi-clock me-2"></i>Tickets
                                        </a>
                                        <a href="panel?pg=facturas-generadas-admin" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-file-check me-2"></i>Ver Facturas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nueva sección de Configuración -->
                    <div class="row g-4 mt-2" style="display:none">
                        <div class="col-md-6">
                            <div class="card bg-warning bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-gear display-4 text-warning mb-3"></i>
                                    <h6 class="fw-bold text-warning mb-2">Configuración</h6>
                                    <p class="text-muted mb-3">Personaliza el sistema de facturación</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=config-facturas" class="btn btn-warning btn-sm">
                                            <i class="bi bi-file-earmark-text me-2"></i>Formato de Facturas
                                        </a>
                                        <a href="panel?pg=config-correo" class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-envelope-at me-2"></i>Correo de Envío
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-info bg-opacity-10 border-0 h-100">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-sliders display-4 text-info mb-3"></i>
                                    <h6 class="fw-bold text-info mb-2">Configuraciones Avanzadas</h6>
                                    <p class="text-muted mb-3">Folios, series y formato de productos</p>
                                    <div class="d-grid gap-2">
                                        <a href="panel?pg=config-folios" class="btn btn-info btn-sm">
                                            <i class="bi bi-hash me-2"></i>Folios y Series
                                        </a>
                                        <a href="panel?pg=config-productos" class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-boxes me-2"></i>Agrupación de Productos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Filtros -->
    <div class="card shadow-sm mb-4 border-1 border-primary">
        <div class="card-body">
            <h4 class="text-primary">Gestion de tickets</h4>
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Sucursal</label>
                    <select class="form-select" id="filterSucursal" onchange="cargarDatos()">
                        <option value="">Todas las sucursales</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Período</label>
                    <select class="form-select" id="filterPeriodo" onchange="cambiarPeriodo()">
                        <option value="todos_fechas">Todos</option>
                        <option value="hoy">Hoy</option>
                        <option value="ayer">Ayer</option>
                        <option value="7dias">Últimos 7 días</option>
                        <option value="este_mes" selected>Este Mes</option>
                        <option value="mes_pasado">Mes Pasado</option>
                        <option value="personalizado">Rango Personalizado</option>
                    </select>
                </div>
                <div class="col-md-2 d-none" id="contFechaDesde">
                    <label class="form-label small text-muted mb-1">Desde</label>
                    <input type="date" class="form-control" id="fechaDesde">
                </div>
                <div class="col-md-2 d-none" id="contFechaHasta">
                    <label class="form-label small text-muted mb-1">Hasta</label>
                    <input type="date" class="form-control" id="fechaHasta">
                </div>
                <div class="col-md-1 d-none" id="contBtnAplicar">
                    <button class="btn btn-primary" onclick="cargarDatos()">
                        <i class="bi bi-search"></i>Buscar
                    </button>
                </div>
                <div class="col-auto ms-auto">
                    <div class="d-grid gap-2">
                        <a href="panel?pg=tickets" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul me-1"></i>Ver Tickets
                        </a>
                        <button class="btn btn-outline-primary" onclick="cargarDatos()" id="btnActualizar">
                            <i class="bi bi-arrow-clockwise"></i> Cargar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- KPIs Principales -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Tickets</h6>
                            <h2 class="fw-bold text-primary mb-0" id="totalTickets">-</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-ticket-detailed fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Facturados</h6>
                            <h2 class="fw-bold text-primary mb-0" id="totalFacturados">-</h2>
                            <small class="text-primary" id="pctFacturados"></small>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-check-circle fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Pendientes</h6>
                            <h2 class="fw-bold text-primary mb-0" id="totalPendientes">-</h2>
                            <small class="text-primary" id="pctPendientes"></small>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-clock-history fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Monto Total</h6>
                            <h2 class="fw-bold text-primary mb-0" id="montoTotal">$0</h2>
                            <small class="text-primary" id="pctMonto"></small>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-cash-coin fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Progreso General -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold">Progreso de Facturación</span>
                <span class="badge bg-primary" id="badgeProgreso">0%</span>
            </div>
            <div class="progress" style="height: 12px;">
                <div class="progress-bar bg-primary" id="barraFacturados" style="width: 0%"></div>
                <div class="progress-bar bg-info" id="barraPendientes" style="width: 0%"></div>
            </div>
            <div class="d-flex justify-content-between mt-2 small">
                <span><i class="bi bi-circle-fill text-primary me-1"></i>Facturados</span>
                <span><i class="bi bi-circle-fill text-info me-1"></i>Pendientes</span>
            </div>
        </div>
    </div>

    <!-- Gráficos y Tablas -->
    <div class="row g-3">
        <!-- Tabla por Día -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-calendar-day me-2"></i>Por Día</h6>
                    <span class="badge bg-secondary" id="badgeDias">0</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 200px;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Día</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Fact.</th>
                                    <th class="text-center">Pend.</th>
                                </tr>
                            </thead>
                            <tbody id="tablaDias">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla por Mes -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Por Mes</h6>
                    <span class="badge bg-secondary" id="badgeMeses">0</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 220px;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Mes</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Fact.</th>
                                    <th class="text-center">Pend.</th>
                                    <th class="text-end" style="width: 120px;">Progreso</th>
                                </tr>
                            </thead>
                            <tbody id="tablaMeses">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chartEstado, chartMetodos;

    // Obtener fechas según período
    function obtenerFechas() {
        const periodo = document.getElementById('filterPeriodo').value;
        const hoy = new Date();
        let desde, hasta;

        if (periodo === 'hoy') {
            desde = hasta = hoy.toISOString().split('T')[0];
        } else if (periodo === 'ayer') {
            const ayer = new Date(hoy);
            ayer.setDate(hoy.getDate() - 1);
            desde = hasta = ayer.toISOString().split('T')[0];
        } else if (periodo === '7dias') {
            const hace7Dias = new Date(hoy);
            hace7Dias.setDate(hoy.getDate() - 7);
            desde = hace7Dias.toISOString().split('T')[0];
            hasta = hoy.toISOString().split('T')[0];
        } else if (periodo === 'este_mes') {
            desde = new Date(hoy.getFullYear(), hoy.getMonth(), 1).toISOString().split('T')[0];
            hasta = hoy.toISOString().split('T')[0];
        } else if (periodo === 'mes_pasado') {
            const primerDiaMesPasado = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
            const ultimoDiaMesPasado = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
            desde = primerDiaMesPasado.toISOString().split('T')[0];
            hasta = ultimoDiaMesPasado.toISOString().split('T')[0];
        } else if (periodo === 'personalizado') {
            desde = document.getElementById('fechaDesde').value;
            hasta = document.getElementById('fechaHasta').value;
        }
        return {
            desde,
            hasta
        };
    }

    function cambiarPeriodo() {
        const personalizado = document.getElementById('filterPeriodo').value === 'personalizado';
        document.getElementById('contFechaDesde').classList.toggle('d-none', !personalizado);
        document.getElementById('contFechaHasta').classList.toggle('d-none', !personalizado);
        document.getElementById('contBtnAplicar').classList.toggle('d-none', !personalizado);
        if (!personalizado) cargarDatos();
    }

    function construirParams() {
        const params = new URLSearchParams();
        const sucursal = document.getElementById('filterSucursal').value;
        const {
            desde,
            hasta
        } = obtenerFechas();
        if (sucursal) params.append('id_empresa', sucursal);
        if (desde && hasta) {
            params.append('fecha_desde', desde);
            params.append('fecha_hasta', hasta);
        }
        return params;
    }

    async function fetchAPI(accion) {
        const params = construirParams();
        params.append('accion', accion);
        try {
            const r = await fetch(`core/dashboard-tickets-stats.php?${params}`);
            const d = await r.json();
            return d.success ? d.data : null;
        } catch (e) {
            console.error(e);
            return null;
        }
    }

    async function cargarSucursales() {
        try {
            const r = await fetch('core/consultar-tickets.php?obtener_sucursales=1');
            const d = await r.json();
            if (d.success && d.sucursales) {
                const sel = document.getElementById('filterSucursal');
                d.sucursales.forEach(s => {
                    sel.innerHTML += `<option value="${s.id}">${s.nombre}</option>`;
                });
            }
        } catch (e) {
            console.error(e);
        }
    }

    function formatMoney(val) {
        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN'
        }).format(val || 0);
    }

    async function cargarResumen() {
        const d = await fetchAPI('resumen_general');
        if (!d) return;

        const total = parseInt(d.total_tickets) || 0;
        const fact = parseInt(d.facturados) || 0;
        const pend = parseInt(d.pendientes) || 0;
        const pctFact = d.porcentaje_facturado || 0;
        const pctPend = d.porcentaje_pendiente || 0;

        document.getElementById('totalTickets').textContent = total;
        document.getElementById('totalFacturados').textContent = fact;
        document.getElementById('totalPendientes').textContent = pend;
        document.getElementById('pctFacturados').textContent = pctFact ? `(${pctFact}%)` : '';
        document.getElementById('pctPendientes').textContent = pctPend ? `(${pctPend}%)` : '';
        document.getElementById('montoTotal').textContent = formatMoney(d.importe_total);

        // Barra de progreso
        document.getElementById('barraFacturados').style.width = pctFact + '%';
        document.getElementById('barraPendientes').style.width = pctPend + '%';
        document.getElementById('badgeProgreso').textContent = pctFact + '% facturado';
    }

    async function cargarDias() {
        const d = await fetchAPI('por_dia');
        const tbody = document.getElementById('tablaDias');
        const badge = document.getElementById('badgeDias');

        if (!d || !d.length) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">Sin datos</td></tr>';
            badge.textContent = '0';
            return;
        }

        badge.textContent = d.length;
        tbody.innerHTML = d.map(dia => `
            <tr>
                <td class="small">${dia.dia_nombre.substring(0, 3)}</td>
                <td class="text-center fw-bold">${dia.total_tickets}</td>
                <td class="text-center text-primary">${dia.facturados}</td>
                <td class="text-center text-info">${dia.pendientes}</td>
            </tr>
        `).join('');
    }

    async function cargarMeses() {
        const d = await fetchAPI('por_mes');
        const tbody = document.getElementById('tablaMeses');
        const badge = document.getElementById('badgeMeses');

        if (!d || !d.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Sin datos</td></tr>';
            badge.textContent = '0';
            return;
        }

        badge.textContent = d.length;
        tbody.innerHTML = d.map(m => {
            const pct = m.total_tickets > 0 ? Math.round((m.facturados / m.total_tickets) * 100) : 0;
            const color = pct >= 75 ? 'success' : pct >= 50 ? 'warning' : 'danger';
            return `
                <tr>
                    <td class="small">${m.mes_nombre.substring(0, 3)} ${m.anio}</td>
                    <td class="text-center fw-bold">${m.total_tickets}</td>
                    <td class="text-center text-primary">${m.facturados}</td>
                    <td class="text-center text-info">${m.pendientes}</td>
                    <td>
                        <div class="progress" style="height: 16px;">
                            <div class="progress-bar bg-${color}" style="width: ${pct}%">${pct}%</div>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    async function cargarDatos() {
        const btn = document.getElementById('btnActualizar');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        await Promise.all([
            cargarResumen(),
            cargarDias(),
            cargarMeses()
        ]);

        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualizar';
    }

    document.addEventListener('DOMContentLoaded', async () => {
        await cargarSucursales();
        await cargarDatos();
    });
</script>