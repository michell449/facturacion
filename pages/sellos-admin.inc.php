<!-- Gestión de Sellos Digitales -->
<div class="content-wrapper bg-light loaded">
    <!-- Header -->
    <div class="bg-dark text-white py-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-20 rounded-circle p-3 me-4">
                            <i class="bi bi-shield-check display-6 text-warning"></i>
                        </div>
                        <div>
                            <h1 class="fw-bold mb-2">Sellos Digitales (CSD)</h1>
                            <p class="lead mb-0 opacity-90">Administra los certificados de sello digital por sucursal</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <button class="btn btn-warning btn-lg fw-semibold" data-bs-toggle="modal" data-bs-target="#subirSelloModal">
                        <i class="bi bi-shield-plus me-2"></i>Subir Sello
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <!-- Panel de Alertas -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="alert alert-warning border-0 rounded-4 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle fs-4 me-3"></i>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">¡Atención! Sellos por renovar</h6>
                            <small class="opacity-75">Hay 2 sellos digitales que vencen en los próximos 30 días. 
                            <a href="#vencimientos" class="text-decoration-none">Ver detalles</a></small>
                        </div>
                        <button class="btn btn-sm btn-outline-warning rounded-pill">
                            <i class="bi bi-bell-fill me-1"></i>Configurar Alertas
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen de Estados -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="bg-success bg-opacity-20 rounded-circle p-3 mx-auto mb-3 d-inline-flex">
                            <i class="bi bi-shield-check text-success fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-1">6</h3>
                        <h6 class="text-muted">Sellos Activos</h6>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: 75%"></div>
                        </div>
                        <small class="text-success mt-1 d-block">75% del total</small>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="bg-warning bg-opacity-20 rounded-circle p-3 mx-auto mb-3 d-inline-flex">
                            <i class="bi bi-clock text-warning fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-1">2</h3>
                        <h6 class="text-muted">Por Vencer</h6>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-warning" style="width: 25%"></div>
                        </div>
                        <small class="text-warning mt-1 d-block">En 30 días</small>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="bg-danger bg-opacity-20 rounded-circle p-3 mx-auto mb-3 d-inline-flex">
                            <i class="bi bi-shield-x text-danger fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-danger mb-1">0</h3>
                        <h6 class="text-muted">Vencidos</h6>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-danger" style="width: 0%"></div>
                        </div>
                        <small class="text-success mt-1 d-block">¡Excelente!</small>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="bg-primary bg-opacity-20 rounded-circle p-3 mx-auto mb-3 d-inline-flex">
                            <i class="bi bi-calendar-check text-primary fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-primary mb-1">456</h3>
                        <h6 class="text-muted">Días Promedio</h6>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: 62%"></div>
                        </div>
                        <small class="text-primary mt-1 d-block">Vigencia restante</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Sellos por Sucursal -->
        <div class="row g-4">
            <!-- Sucursal Centro -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-success bg-opacity-10 border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="bi bi-building text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Sucursal Centro</h6>
                                    <small class="text-muted">SUC-001 | XAXX010101000</small>
                                </div>
                            </div>
                            <span class="badge bg-success rounded-pill">Vigente</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Certificado CSD -->
                        <div class="bg-light rounded-3 p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-lock text-primary me-2 fs-5"></i>
                                    <div>
                                        <h6 class="mb-0">Certificado (.cer)</h6>
                                        <small class="text-muted">30001000000400002345.cer</small>
                                    </div>
                                </div>
                                <span class="badge bg-primary">1.2 KB</span>
                            </div>
                            
                            <!-- Llave Privada -->
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-key text-warning me-2 fs-5"></i>
                                    <div>
                                        <h6 class="mb-0">Llave Privada (.key)</h6>
                                        <small class="text-muted">30001000000400002345.key</small>
                                    </div>
                                </div>
                                <span class="badge bg-warning text-dark">2.1 KB</span>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="row g-3 text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block">No. Serie</small>
                                    <strong class="small">30001000000400002345</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Vigencia</small>
                                    <strong class="text-success small">31/05/2026</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Estado</small>
                                    <span class="badge bg-success">Activo</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="descargarSello('centro')">
                                    <i class="bi bi-download me-1"></i>Descargar
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="verDetallesSello('centro')">
                                    <i class="bi bi-info-circle me-1"></i>Detalles
                                </button>
                                <button type="button" class="btn btn-outline-warning" onclick="renovarSello('centro')">
                                    <i class="bi bi-arrow-repeat me-1"></i>Renovar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sucursal Norte -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-warning bg-opacity-10 border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle p-2 me-3">
                                    <i class="bi bi-building text-dark"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Sucursal Norte</h6>
                                    <small class="text-muted">SUC-002 | XAXX010101000</small>
                                </div>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill">Por Vencer</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-warning border-0 rounded-3 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <small><strong>¡Atención!</strong> Sello vence en 25 días</small>
                            </div>
                        </div>
                        
                        <!-- Certificado CSD -->
                        <div class="bg-light rounded-3 p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-lock text-primary me-2 fs-5"></i>
                                    <div>
                                        <h6 class="mb-0">Certificado (.cer)</h6>
                                        <small class="text-muted">30001000000400002346.cer</small>
                                    </div>
                                </div>
                                <span class="badge bg-primary">1.3 KB</span>
                            </div>
                            
                            <!-- Llave Privada -->
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-key text-warning me-2 fs-5"></i>
                                    <div>
                                        <h6 class="mb-0">Llave Privada (.key)</h6>
                                        <small class="text-muted">30001000000400002346.key</small>
                                    </div>
                                </div>
                                <span class="badge bg-warning text-dark">2.0 KB</span>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="row g-3 text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block">No. Serie</small>
                                    <strong class="small">30001000000400002346</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Vigencia</small>
                                    <strong class="text-warning small">02/12/2024</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Estado</small>
                                    <span class="badge bg-warning text-dark">Por Vencer</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-warning btn-lg" onclick="renovarSello('norte')">
                                <i class="bi bi-exclamation-triangle me-2"></i>Renovar Urgente
                            </button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="descargarSello('norte')">
                                    <i class="bi bi-download me-1"></i>Descargar
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="verDetallesSello('norte')">
                                    <i class="bi bi-info-circle me-1"></i>Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sucursal Sin Sello -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 border-3 border-warning border-dashed">
                    <div class="card-body p-5 text-center">
                        <i class="bi bi-shield-exclamation display-1 text-warning mb-3 opacity-50"></i>
                        <h5 class="text-warning fw-bold mb-2">Sucursal Oriente</h5>
                        <p class="text-muted mb-3">SUC-004 | Sin sello digital</p>
                        <div class="alert alert-warning border-0 rounded-3 mb-4">
                            <small><strong>¡Importante!</strong> Esta sucursal no puede facturar sin sello digital</small>
                        </div>
                        <button class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#subirSelloModal">
                            <i class="bi bi-shield-plus me-2"></i>Agregar Sello
                        </button>
                    </div>
                </div>
            </div>

            <!-- Panel de Nueva Carga -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 border-3 border-primary border-dashed">
                    <div class="card-body p-5 text-center">
                        <i class="bi bi-cloud-upload display-1 text-primary mb-3 opacity-50"></i>
                        <h5 class="text-primary fw-bold mb-2">Subir Nuevo Sello</h5>
                        <p class="text-muted mb-4">Agrega certificados CSD para cualquier sucursal</p>
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#subirSelloModal">
                            <i class="bi bi-shield-plus me-2"></i>Seleccionar Archivos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Subir Sello Digital -->
<div class="modal fade" id="subirSelloModal" tabindex="-1" aria-labelledby="subirSelloModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="subirSelloModalLabel">
                    <i class="bi bi-shield-plus me-2"></i>Subir Sello Digital (CSD)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formSubirSello">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sucursalSello" class="form-label fw-semibold">
                                <i class="bi bi-building me-2 text-primary"></i>Sucursal
                            </label>
                            <select class="form-select form-select-lg rounded-3" id="sucursalSello" required>
                                <option value="">Seleccionar sucursal</option>
                                <option value="centro">Sucursal Centro</option>
                                <option value="norte">Sucursal Norte</option>
                                <option value="sur">Sucursal Sur</option>
                                <option value="oriente">Sucursal Oriente</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="numeroSerie" class="form-label fw-semibold">
                                <i class="bi bi-hash me-2 text-primary"></i>Número de Serie
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3" 
                                   id="numeroSerie" placeholder="30001000000400002345" 
                                   maxlength="20" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="fechaVigenciaSello" class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-2 text-primary"></i>Fecha de Vigencia
                            </label>
                            <input type="date" class="form-control form-control-lg rounded-3" 
                                   id="fechaVigenciaSello" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="passwordSello" class="form-label fw-semibold">
                                <i class="bi bi-key me-2 text-primary"></i>Contraseña de Llave
                            </label>
                            <input type="password" class="form-control form-control-lg rounded-3" 
                                   id="passwordSello" placeholder="Contraseña del archivo .key" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="archivoCer" class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-lock me-2 text-primary"></i>Certificado (.cer)
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3" 
                                   id="archivoCer" accept=".cer" required>
                            <div class="form-text">Archivo de certificado .cer</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="archivoKey" class="form-label fw-semibold">
                                <i class="bi bi-key me-2 text-primary"></i>Llave Privada (.key)
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3" 
                                   id="archivoKey" accept=".key" required>
                            <div class="form-text">Archivo de llave privada .key</div>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info border-0 rounded-3">
                                <div class="d-flex">
                                    <i class="bi bi-shield-check me-3 mt-1 fs-5"></i>
                                    <div>
                                        <h6 class="fw-bold mb-2">Información de Seguridad</h6>
                                        <ul class="small mb-0">
                                            <li>Los archivos se almacenan de forma encriptada</li>
                                            <li>La contraseña NO se guarda en el sistema</li>
                                            <li>Se validará la correspondencia entre certificado y llave</li>
                                            <li>El número de serie debe coincidir con el certificado</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vista Previa de Validación -->
                        <div class="col-12" id="validacionPanel" style="display: none;">
                            <div class="card bg-light border-0 rounded-3">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-check-circle text-success me-2"></i>Validación de Archivos
                                    </h6>
                                    <div id="validacionResultados">
                                        <!-- Resultados de validación se llenan dinámicamente -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-warning me-2" id="btnValidarSello">
                    <i class="bi bi-check-circle me-2"></i>Validar Archivos
                </button>
                <button type="button" class="btn btn-dark btn-lg fw-semibold" id="btnSubirSello" disabled>
                    <i class="bi bi-shield-plus me-2"></i>Subir Sello
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles del Sello -->
<div class="modal fade" id="detallesSelloModal" tabindex="-1" aria-labelledby="detallesSelloModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold" id="detallesSelloModalLabel">
                    <i class="bi bi-info-circle me-2"></i>Detalles del Sello Digital
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="contenidoDetallesSello">
                    <!-- Contenido se llena dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let validacionCompleta = false;

        // Validar archivos del sello
        document.getElementById('btnValidarSello').addEventListener('click', function() {
            const certificado = document.getElementById('archivoCer').files[0];
            const llave = document.getElementById('archivoKey').files[0];
            const password = document.getElementById('passwordSello').value;
            const numeroSerie = document.getElementById('numeroSerie').value;

            if (!certificado || !llave || !password || !numeroSerie) {
                alert('Por favor completa todos los campos requeridos');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Validando...';

            // Simular validación
            setTimeout(() => {
                const panel = document.getElementById('validacionPanel');
                const resultados = document.getElementById('validacionResultados');
                
                resultados.innerHTML = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span class="small">Certificado válido</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span class="small">Llave privada válida</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span class="small">Contraseña correcta</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <span class="small">Número de serie coincide</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3 mt-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-shield-check text-success me-2"></i>
                                    <span class="small fw-semibold">Sello digital válido y listo para cargar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                panel.style.display = 'block';
                validacionCompleta = true;
                document.getElementById('btnSubirSello').disabled = false;
                
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-check-circle me-2"></i>Validado';
                this.classList.remove('btn-warning');
                this.classList.add('btn-success');
            }, 2000);
        });

        // Subir sello
        document.getElementById('btnSubirSello').addEventListener('click', function() {
            if (!validacionCompleta) {
                alert('Por favor valida los archivos primero');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Subiendo...';

            setTimeout(() => {
                alert('¡Sello digital cargado exitosamente!');
                bootstrap.Modal.getInstance(document.getElementById('subirSelloModal')).hide();
                document.getElementById('formSubirSello').reset();
                document.getElementById('validacionPanel').style.display = 'none';
                validacionCompleta = false;
                
                this.disabled = true;
                this.innerHTML = '<i class="bi bi-shield-plus me-2"></i>Subir Sello';
                document.getElementById('btnValidarSello').classList.remove('btn-success');
                document.getElementById('btnValidarSello').classList.add('btn-warning');
                document.getElementById('btnValidarSello').innerHTML = '<i class="bi bi-check-circle me-2"></i>Validar Archivos';
                
                location.reload();
            }, 3000);
        });

        // Validar número de serie formato
        document.getElementById('numeroSerie').addEventListener('input', function() {
            const valor = this.value.replace(/\D/g, ''); // Solo números
            this.value = valor;
            
            if (valor.length === 20) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (valor.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });

    // Funciones para acciones de sellos
    function descargarSello(sucursal) {
        alert('Descargando sello de ' + sucursal + '...');
    }

    function verDetallesSello(sucursal) {
        const contenido = document.getElementById('contenidoDetallesSello');
        
        // Ejemplo de datos según la sucursal
        const datos = {
            centro: {
                serie: '30001000000400002345',
                vigencia: '31/05/2026',
                rfc: 'XAXX010101000',
                razonSocial: 'Empresa SA de CV',
                estado: 'Activo'
            },
            norte: {
                serie: '30001000000400002346', 
                vigencia: '02/12/2024',
                rfc: 'XAXX010101000',
                razonSocial: 'Empresa SA de CV',
                estado: 'Por Vencer'
            }
        };

        const info = datos[sucursal];
        
        contenido.innerHTML = `
            <div class="row g-4">
                <div class="col-12">
                    <h6 class="fw-bold">Información del Certificado</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted">Número de Serie:</td>
                                <td class="fw-semibold">${info.serie}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">RFC:</td>
                                <td class="fw-semibold">${info.rfc}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Razón Social:</td>
                                <td class="fw-semibold">${info.razonSocial}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Fecha de Vigencia:</td>
                                <td class="fw-semibold">${info.vigencia}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Estado:</td>
                                <td><span class="badge bg-${info.estado === 'Activo' ? 'success' : 'warning'}">${info.estado}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-12">
                    <h6 class="fw-bold">Uso del Certificado</h6>
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-3">
                                <i class="bi bi-receipt text-primary fs-4 d-block mb-2"></i>
                                <strong class="d-block">1,234</strong>
                                <small class="text-muted">Facturas</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-3">
                                <i class="bi bi-calendar-check text-success fs-4 d-block mb-2"></i>
                                <strong class="d-block">456</strong>
                                <small class="text-muted">Este mes</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-3">
                                <i class="bi bi-clock text-info fs-4 d-block mb-2"></i>
                                <strong class="d-block">45</strong>
                                <small class="text-muted">Última semana</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const modal = new bootstrap.Modal(document.getElementById('detallesSelloModal'));
        modal.show();
    }

    function renovarSello(sucursal) {
        if (confirm('¿Deseas renovar el sello de la sucursal ' + sucursal + '?\nEsto iniciará el proceso de renovación.')) {
            alert('Proceso de renovación iniciado. Se te notificará cuando esté listo el nuevo sello.');
        }
    }
</script>