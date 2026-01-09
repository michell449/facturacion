
<!-- Hero Dashboard Section -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                        <i class="bi bi-speedometer2 display-6"></i>
                    </div>
                    <div>
                        <h1 class="display-5 fw-bold mb-2">¡Bienvenido, <span id="nombreUsuario">Usuario</span>!</h1>
                        <p class="lead mb-0 opacity-90">Sistema de facturación electrónica</p>
                        <small class="opacity-75">
                            <i class="bi bi-calendar3 me-2"></i><?php echo date('d \d\e F \d\e Y'); ?>
                            <i class="bi bi-clock ms-3 me-2"></i><?php echo date('H:i'); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Content -->
<div class="container py-5">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100 stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 mb-3">
                                <i class="bi bi-file-earmark-check text-primary fs-4"></i>
                            </div>
                            <h3 id="facturasGeneradas" class="fw-bold text-primary mb-1">0</h3>
                            <p class="text-muted mb-0">Facturas Generadas</p>
                        </div>
                        <div class="text-end">
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 0%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100 stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 mb-3">
                                <i class="bi bi-currency-dollar text-success fs-4"></i>
                            </div>
                            <h3 id="montoMes" class="fw-bold text-success mb-1">$0.00</h3>
                            <p class="text-muted mb-0">Facturado este Mes</p>
                        </div>
                        <div class="text-end">
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 0%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100 stat-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 mb-3">
                                <i class="bi bi-clock-history text-warning fs-4"></i>
                            </div>
                            <h3 id="pendientesCount" class="fw-bold text-warning mb-1">0</h3>
                            <p class="text-muted mb-0">Pendientes</p>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">
                                <i class="bi bi-dash"></i> 0%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-4">
                Acciones
            </h3>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-lg h-100 action-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="bg-primary bg-opacity-10 rounded-4 p-4">
                                <i class="bi bi-receipt-cutoff text-primary display-6"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="fw-bold mb-2">Generar Nueva Factura</h4>
                            <p class="text-muted mb-3">Convierte tu ticket de compra en factura electrónica</p>
                            <a href="panel?pg=facturar" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i>
                                Crear Factura
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-lg h-100 action-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="bg-success bg-opacity-10 rounded-4 p-4">
                                <i class="bi bi-file-earmark-text text-success display-6"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="fw-bold mb-2">Ver Mi Historial</h4>
                            <p class="text-muted mb-3">Consulta, descarga y administra tus facturas</p>
                            <a href="panel?pg=historial" class="btn btn-success btn-lg">
                                <i class="bi bi-archive me-2"></i>
                                Ver Historial
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- actividad reciente -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        Actividad Reciente
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <div id="listaActividadReciente"></div>
                    <div class="text-center mt-4">
                        <a href="historial" class="btn btn-outline-primary">
                            Ver todo el historial
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        Mi Perfil
                    </h5>
                </div>
                <div class="card-body pt-0 text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill text-primary fs-1"></i>
                    </div>
                    <h5 id="perfilRazon" class="fw-bold mb-1"></h5>
                    <p id="perfilRFC" class="text-muted mb-3"></p>
                    
                    <div class="row text-center mb-4">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 id="perfilTotalFacturas" class="fw-bold text-primary mb-0">0</h6>
                                <small class="text-muted">Facturas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 id="perfilCanceladas" class="fw-bold text-danger mb-0">0</h6>
                            <small class="text-muted">Canceladas</small>
                        </div>
                    </div>
                    <div class="text-start mb-3">
                        <div><small class="text-muted">Régimen Fiscal:</small> <span id="perfilRegimen" class="fw-semibold"></span></div>
                        <div><small class="text-muted">CP:</small> <span id="perfilCP" class="fw-semibold"></span></div>
                    </div>
                    <a href="perfil" class="btn btn-outline-primary w-100">
                        <i class="bi bi-gear me-2"></i>
                        Administrar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Help & Support Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3">¿Necesitas ayuda?</h5>
                    <p class="text-muted mb-4">Nuestro equipo de soporte está disponible para asistirte</p>
                    <div class="row g-3 justify-content-center">
                        <div class="col-auto">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bi bi-question-circle me-2"></i>
                                Centro de Ayuda
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="#" class="btn btn-outline-success">
                                <i class="bi bi-whatsapp me-2"></i>
                                WhatsApp
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="#" class="btn btn-outline-info">
                                <i class="bi bi-envelope me-2"></i>
                                Email Soporte
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await fetch('core/obtener-dashboard-cliente.php', { cache: 'no-store' });
        const data = await res.json();
        if (!data.success) return;

        // Header
        if (data.user && data.user.nombre) {
            const nombreEl = document.getElementById('nombreUsuario');
            if (nombreEl) nombreEl.textContent = data.user.nombre;
        }

        // Stats
        const stats = data.stats || {};
        const setText = (id, value) => { const el = document.getElementById(id); if (el) el.textContent = value; };
        setText('facturasGeneradas', stats.total_facturas ?? 0);
        setText('montoMes', stats.monto_mes ?? '$0.00');
        setText('pendientesCount', stats.pendientes ?? 0);

        // Perfil
        if (data.fiscal) {
            setText('perfilRazon', data.fiscal.razon_social || '');
            setText('perfilRFC', data.fiscal.rfc ? ('RFC: ' + data.fiscal.rfc) : '');
            setText('perfilRegimen', data.fiscal.reg_fiscal || '');
            setText('perfilCP', data.fiscal.cp || '');
        }
        setText('perfilTotalFacturas', stats.total_facturas ?? 0);
        setText('perfilCanceladas', stats.canceladas ?? 0);

        // Actividad reciente (facturas + respuestas cancelación)
        const cont = document.getElementById('listaActividadReciente');
        const facturas = Array.isArray(data.recientes) ? data.recientes : [];
        const respuestas = Array.isArray(data.respuestas) ? data.respuestas : [];

        // Construir items (máximo 6)
        const items = [];
        facturas.forEach(f => {
            items.push({
                tipo: 'factura',
                titulo: 'Factura generada',
                detalle: `${f.folio} - ${f.total_formatted}`,
                fecha: f.fecha,
                color: 'primary'
            });
        });
        respuestas.forEach(r => {
            const aprob = r.estado === 'aprobada';
            items.push({
                tipo: 'respuesta',
                titulo: aprob ? 'Cancelación aprobada' : 'Cancelación rechazada',
                detalle: `${r.folio} - ${r.respuesta_admin || ''}`,
                fecha: r.fecha,
                color: aprob ? 'success' : 'danger'
            });
        });
        items.sort((a,b) => new Date(b.fecha.split('/').reverse().join(' ')) - new Date(a.fecha.split('/').reverse().join(' ')));
        const top = items.slice(0,6);

        if (cont) {
            if (top.length === 0) {
                cont.innerHTML = '<div class="text-muted">Sin actividad reciente</div>';
            } else {
                cont.innerHTML = top.map(it => `
                    <div class="timeline-item">
                        <div class="timeline-marker bg-${it.color}"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">${it.titulo}</h6>
                            <p class="text-muted mb-1">${it.detalle}</p>
                            <small class="text-muted">${it.fecha}</small>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (e) {
        console.error('Error cargando dashboard:', e);
    }
});
</script>