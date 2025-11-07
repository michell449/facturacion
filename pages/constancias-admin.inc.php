<!-- Gestión de Constancias Fiscales -->
<div class="content-wrapper bg-light loaded">
    <!-- Header -->
    <div class="bg-info text-white py-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                            <i class="bi bi-file-earmark-pdf display-6 text-white"></i>
                        </div>
                        <div>
                            <h1 class="fw-bold mb-2">Constancias de Situación Fiscal</h1>
                            <p class="lead mb-0 opacity-90">Administra los certificados fiscales de todas las sucursales</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <button class="btn btn-warning btn-lg fw-semibold" data-bs-toggle="modal" data-bs-target="#subirConstanciaModal">
                        <i class="bi bi-cloud-upload me-2"></i>Subir Constancia
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <!-- Resumen de Estados -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="bg-success bg-opacity-20 rounded-circle p-3 mx-auto mb-3 d-inline-flex">
                            <i class="bi bi-check-circle text-success fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-1">5</h3>
                        <h6 class="text-muted">Vigentes</h6>
                        <small class="text-success">Hasta 2026</small>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="bg-warning bg-opacity-20 rounded-circle p-3 mx-auto mb-3 d-inline-flex">
                            <i class="bi bi-exclamation-triangle text-warning fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-1">2</h3>
                        <h6 class="text-muted">Por Vencer</h6>
                        <small class="text-warning">En 30 días</small>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="bg-danger bg-opacity-20 rounded-circle p-3 mx-auto mb-3 d-inline-flex">
                            <i class="bi bi-x-circle text-danger fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-danger mb-1">1</h3>
                        <h6 class="text-muted">Vencidas</h6>
                        <small class="text-danger">Requiere renovación</small>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="bg-primary bg-opacity-20 rounded-circle p-3 mx-auto mb-3 d-inline-flex">
                            <i class="bi bi-building text-primary fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-primary mb-1">8</h3>
                        <h6 class="text-muted">Total Sucursales</h6>
                        <small class="text-primary">Certificadas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Constancias por Sucursal -->
        <div class="row g-4">
            <!-- Sucursal Centro - Vigente -->
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
                                    <small class="text-muted">SUC-001 | Av. Reforma 123</small>
                                </div>
                            </div>
                            <span class="badge bg-success rounded-pill">Vigente</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">RFC</small>
                                <strong>XAXX010101000</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Razón Social</small>
                                <strong>Empresa SA de CV</strong>
                            </div>
                        </div>
                        
                        <div class="bg-light rounded-3 p-3 mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-file-earmark-pdf text-danger me-2 fs-5"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">constancia_centro_2024.pdf</h6>
                                    <small class="text-muted">Subido: 15/01/2024</small>
                                </div>
                                <span class="badge bg-success">2.1 MB</span>
                            </div>
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block">Emisión</small>
                                    <strong>10/01/2024</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Vigencia</small>
                                    <strong class="text-success">31/12/2024</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Días Restantes</small>
                                    <strong class="text-success">54</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Descargar
                                </button>
                                <button type="button" class="btn btn-outline-success">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </button>
                                <button type="button" class="btn btn-outline-warning">
                                    <i class="bi bi-arrow-repeat me-1"></i>Renovar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sucursal Norte - Por Vencer -->
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
                                    <small class="text-muted">SUC-002 | Blvd. Norte 456</small>
                                </div>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill">Por Vencer</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-warning border-0 rounded-3 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <small><strong>¡Atención!</strong> Constancia vence en 15 días</small>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">RFC</small>
                                <strong>XAXX010101000</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Razón Social</small>
                                <strong>Empresa SA de CV</strong>
                            </div>
                        </div>
                        
                        <div class="bg-light rounded-3 p-3 mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-file-earmark-pdf text-danger me-2 fs-5"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">constancia_norte_2024.pdf</h6>
                                    <small class="text-muted">Subido: 20/01/2024</small>
                                </div>
                                <span class="badge bg-warning text-dark">1.8 MB</span>
                            </div>
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block">Emisión</small>
                                    <strong>15/01/2024</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Vigencia</small>
                                    <strong class="text-warning">22/11/2024</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Días Restantes</small>
                                    <strong class="text-danger">15</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Descargar
                                </button>
                                <button type="button" class="btn btn-outline-success">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </button>
                                <button type="button" class="btn btn-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Renovar Urgente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sucursal Sur - Vencida -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-danger bg-opacity-10 border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger rounded-circle p-2 me-3">
                                    <i class="bi bi-building text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Sucursal Sur</h6>
                                    <small class="text-muted">SUC-003 | Av. Sur 789</small>
                                </div>
                            </div>
                            <span class="badge bg-danger rounded-pill">Vencida</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-danger border-0 rounded-3 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-x-circle me-2"></i>
                                <small><strong>¡Crítico!</strong> Constancia vencida - Facturación bloqueada</small>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">RFC</small>
                                <strong>XAXX010101000</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Razón Social</small>
                                <strong>Empresa SA de CV</strong>
                            </div>
                        </div>
                        
                        <div class="bg-light rounded-3 p-3 mb-3 opacity-75">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-file-earmark-pdf text-danger me-2 fs-5"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">constancia_sur_2023.pdf</h6>
                                    <small class="text-muted">Subido: 10/01/2023</small>
                                </div>
                                <span class="badge bg-danger">2.0 MB</span>
                            </div>
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block">Emisión</small>
                                    <strong>05/01/2023</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Vigencia</small>
                                    <strong class="text-danger">31/10/2024</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Días Vencida</small>
                                    <strong class="text-danger">7</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-danger btn-lg" 
                                    data-bs-toggle="modal" data-bs-target="#subirConstanciaModal">
                                <i class="bi bi-cloud-upload me-2"></i>Subir Nueva Constancia
                            </button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" disabled>
                                    <i class="bi bi-download me-1"></i>Descargar
                                </button>
                                <button type="button" class="btn btn-outline-success" disabled>
                                    <i class="bi bi-eye me-1"></i>Ver
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nueva Sucursal -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 border-3 border-primary border-dashed">
                    <div class="card-body p-5 text-center">
                        <i class="bi bi-cloud-upload display-1 text-primary mb-3 opacity-50"></i>
                        <h5 class="text-primary fw-bold mb-2">Subir Nueva Constancia</h5>
                        <p class="text-muted mb-4">Agrega la constancia fiscal para una sucursal</p>
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#subirConstanciaModal">
                            <i class="bi bi-file-earmark-plus me-2"></i>Seleccionar Archivo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Subir Constancia -->
<div class="modal fade" id="subirConstanciaModal" tabindex="-1" aria-labelledby="subirConstanciaModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold" id="subirConstanciaModalLabel">
                    <i class="bi bi-cloud-upload me-2"></i>Subir Constancia de Situación Fiscal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formSubirConstancia">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sucursalConstancia" class="form-label fw-semibold">
                                <i class="bi bi-building me-2 text-info"></i>Sucursal
                            </label>
                            <select class="form-select form-select-lg rounded-3" id="sucursalConstancia" required>
                                <option value="">Seleccionar sucursal</option>
                                <option value="centro">Sucursal Centro</option>
                                <option value="norte">Sucursal Norte</option>
                                <option value="sur">Sucursal Sur</option>
                                <option value="oriente">Sucursal Oriente</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="fechaEmision" class="form-label fw-semibold">
                                <i class="bi bi-calendar me-2 text-info"></i>Fecha de Emisión
                            </label>
                            <input type="date" class="form-control form-control-lg rounded-3" 
                                   id="fechaEmision" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="fechaVigencia" class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-2 text-info"></i>Fecha de Vigencia
                            </label>
                            <input type="date" class="form-control form-control-lg rounded-3" 
                                   id="fechaVigencia" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="rfcConstancia" class="form-label fw-semibold">
                                <i class="bi bi-card-text me-2 text-info"></i>RFC
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3" 
                                   id="rfcConstancia" placeholder="XAXX010101000" required>
                        </div>
                        
                        <div class="col-12">
                            <label for="archivoConstancia" class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-pdf me-2 text-info"></i>Archivo PDF
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3" 
                                   id="archivoConstancia" accept=".pdf" required>
                            <div class="form-text">Solo archivos PDF, máximo 5 MB</div>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info border-0 rounded-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-2 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1">Información Importante</h6>
                                        <small>
                                            • La constancia debe estar vigente al momento de la carga<br>
                                            • El RFC debe coincidir con el registrado en la sucursal<br>
                                            • El archivo se encriptará automáticamente por seguridad
                                        </small>
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
                <button type="button" class="btn btn-info btn-lg fw-semibold" id="btnSubirConstancia">
                    <i class="bi bi-cloud-upload me-2"></i>Subir Constancia
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Subir constancia
        document.getElementById('btnSubirConstancia').addEventListener('click', function() {
            const form = document.getElementById('formSubirConstancia');
            const formData = new FormData(form);
            
            // Validar archivo
            const archivo = document.getElementById('archivoConstancia').files[0];
            if (!archivo) {
                alert('Por favor selecciona un archivo PDF');
                return;
            }
            
            if (archivo.size > 5 * 1024 * 1024) {
                alert('El archivo es demasiado grande. Máximo 5 MB');
                return;
            }
            
            // Simular carga
            this.disabled = true;
            this.innerHTML = '<i class="bi bi-cloud-upload me-2"></i>Subiendo...';
            
            setTimeout(() => {
                alert('¡Constancia subida exitosamente!');
                bootstrap.Modal.getInstance(document.getElementById('subirConstanciaModal')).hide();
                form.reset();
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-cloud-upload me-2"></i>Subir Constancia';
                location.reload();
            }, 2000);
        });

        // Calcular días restantes automáticamente
        document.getElementById('fechaVigencia').addEventListener('change', function() {
            const fechaVigencia = new Date(this.value);
            const hoy = new Date();
            const diasRestantes = Math.ceil((fechaVigencia - hoy) / (1000 * 60 * 60 * 24));
            
            if (diasRestantes < 0) {
                console.log('Constancia vencida');
            } else if (diasRestantes < 30) {
                console.log('Por vencer en', diasRestantes, 'días');
            } else {
                console.log('Vigente por', diasRestantes, 'días');
            }
        });

        // Validar RFC
        document.getElementById('rfcConstancia').addEventListener('input', function() {
            const rfc = this.value.toUpperCase();
            this.value = rfc;
            
            // Validación básica de RFC
            const rfcPattern = /^[A-ZÑ&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z0-9]{2}[0-9A]$/;
            if (rfc.length === 13 && rfcPattern.test(rfc)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (rfc.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
</script>