<!-- SECCIÓN DE ENCABEZADO -->
<div class="container py-4">
    <!-- Fila para el título y el botón de regresar -->
    <div class="row mb-4 align-items-center">
        <div class="col-8">
            <h2 class="text-primary fw-bold mb-0">
                <i class="bi bi-building display-6 text-primary me-2"></i>
                Sucursales
            </h2>
            <p class="text-muted mb-0">Aquí puedes gestionar las sucursales de tu empresa.</p>
        </div>
        <div class="col-4 text-end">
            <button type="button" class="btn btn-outline-primary btn-lg rounded-3" onclick="window.history.back()">
                <i class="bi bi-arrow-left me-2"></i>Regresar
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-info bg-opacity-10">
                <div class="card-body p-3 text-center">
                    <h5 class="text-info mb-1">3 Sucursales</h5>
                    <small class="text-muted">2 Activas • 1 Mantenimiento</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECCIÓN DE LISTA DE SUCURSALES-->
<div class="container py-2">
    <div class="row g-4">

        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="col-12">
                                <h6 class="fw-bold mb-0">Sucursal Centro</h6>
                                <small class="text-muted">SUC-001</small>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill">Activa</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-geo-alt me-2"></i>Ubicación
                        </h6>
                        <p class="mb-0">Av. Reforma 123, Col. Centro</p>
                        <small class="text-muted">Puebla, Puebla, 06000</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded-3">
                                <h6 class="text-primary mb-0">45</h6>
                                <small class="text-muted">Tickets facturados</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded-3">
                                <h6 class="text-success mb-0">$12,500</h6>
                                <small class="text-muted">Facturado</small>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos Fiscales Detallados -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-shield-check me-2"></i>Documentos Fiscales
                        </h6>

                        <!-- Constancia de Situación Fiscal -->
                        <div class="card bg-light border-0 rounded-3 mb-2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0 small fw-bold">Constancia Fiscal</h6>
                                            <small class="text-muted">constancia_centro_2024.pdf</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="btn-group btn-group-sm mt-1" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="abrirModalConstancia('centro')">
                                                <i class="bi bi-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2 text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Emisión</small>
                                        <strong class="small">15/01/2024</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Vigencia</small>
                                        <strong class="small text-primary">22/11/2024</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Estado</small>
                                        <strong class="small text-warning">Por Vencer</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sello Digital -->
                        <div class="card bg-light border-0 rounded-3 mb-2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0 small fw-bold">Sello Digital (CSD)</h6>
                                            <small class="text-muted">30001000000400002345</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="btn-group btn-group-sm mt-1" role="group">
                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                onclick="abrirModalSello('centro')">
                                                <i class="bi bi-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2 text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Vigencia</small>
                                        <strong class="small text-info">31/05/2026</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">Sucursal Norte</h6>
                                <small class="text-muted">SUC-002</small>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill">Activa</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-geo-alt me-2"></i>Ubicación
                        </h6>
                        <p class="mb-0">Blvd. Norte 456, Col. Lindavista</p>
                        <small class="text-muted">Puebla, Puebla, 07300</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded-3">
                                <h6 class="text-primary mb-0">32</h6>
                                <small class="text-muted">Tickets facturados</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 bg-light rounded-3">
                                <h6 class="text-success mb-0">$8,750</h6>
                                <small class="text-muted">Facturado</small>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos Fiscales Detallados -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-shield-check me-2"></i>Documentos Fiscales
                        </h6>

                        <!-- Constancia de Situación Fiscal -->
                        <div class="card bg-light border-0 rounded-3 mb-2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0 small fw-bold">Constancia Fiscal</h6>
                                            <small class="text-muted">constancia_norte_2024.pdf</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="btn-group btn-group-sm mt-1" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="abrirModalConstancia('norte')">
                                                <i class="bi bi-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2 text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Emisión</small>
                                        <strong class="small">10/02/2024</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Vigencia</small>
                                        <strong class="small text-primary">31/12/2025</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Estado</small>
                                        <strong class="small text-success">Vigente</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sello Digital -->
                        <div class="card bg-light border-0 rounded-3 mb-2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-shield-check text-success me-2 fs-5"></i>
                                        <div>
                                            <h6 class="mb-0 small fw-bold">Sello Digital (CSD)</h6>
                                            <small class="text-muted">30001000000400002346</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="btn-group btn-group-sm mt-1" role="group">
                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                onclick="abrirModalSello('norte')">
                                                <i class="bi bi-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2 text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Vigencia</small>
                                        <strong class="small text-info">15/08/2026</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">Sucursal Sur</h6>
                                <small class="text-muted">SUC-003</small>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill">Mantenimiento</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">
                            <i class="bi bi-geo-alt me-2"></i>Ubicación
                        </h6>
                        <p class="mb-0">Av. Sur 789, Col. Del Valle</p>
                        <small class="text-muted">Puebla, Puebla, 03100</small>
                    </div>

                    <div class="alert alert-warning border-0 rounded-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <small>Documentos pendientes</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-shield-exclamation me-2"></i>Documentos Fiscales
                        </h6>

                        <!-- Constancia de Situación Fiscal Vencida -->
                        <div class="card bg-light border-0 rounded-3 mb-2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0 small fw-bold">Constancia Fiscal</h6>
                                            <small class="text-muted">constancia_sur_2023.pdf</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="btn-group btn-group-sm mt-1" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="abrirModalConstancia('sur')">
                                                <i class="bi bi-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2 text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Emisión</small>
                                        <strong class="small text-primary">05/01/2023</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Vigencia</small>
                                        <strong class="small text-primary">31/10/2024</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Estado</small>
                                        <strong class="small text-danger">Vencida</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sello Digital Faltante -->
                        <div class="card  bg-light border-0 rounded-3 mb-2">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0 small fw-bold">Sello Digital (CSD)</h6>
                                            <small class="text-muted">No configurado</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="btn-group btn-group-sm mt-1" role="group">
                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                onclick="abrirModalSello('sur')">
                                                <i class="bi bi-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-warning border-0 p-2 mt-2 mb-0">
                                    <small class="d-flex align-items-center">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Esta sucursal no puede facturar sin sello digital válido
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones de Mantenimiento -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 h-100 border-3 border-primary border-dashed">
                <div
                    class="card-body p-5 text-center d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-plus-circle display-1 text-primary mb-3 opacity-50"></i>
                    <h5 class="text-primary fw-bold mb-2">Agregar Sucursal</h5>
                    <p class="text-muted mb-4">Expande tu negocio con una nueva ubicación</p>
                    <a href="panel?pg=nueva-sucursal-admin" class="btn btn-primary">
                        Crear Sucursal
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Modal para Subir Sello Digital -->
<div class="modal fade" id="modalSello" tabindex="-1" aria-labelledby="modalSelloLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="modalSelloLabel">
                    <i class="bi bi-shield-check me-2"></i>Subir Sello Digital (CSD)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formSello">
                    <input type="hidden" id="sucursalSelloId">

                    <div class="alert alert-warning border-0 rounded-3 mb-4">
                        <div class="d-flex">
                            <i class="bi bi-shield-exclamation me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Información de Seguridad</h6>
                                <ul class="small mb-0">
                                    <li>Los archivos se almacenan de forma encriptada</li>
                                    <li>La contraseña NO se guarda en el sistema</li>
                                    <li>Se validará la correspondencia entre certificado y llave</li>
                                    <li>El número de serie debe coincidir con el certificado</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="sucursalNombreSello" class="form-label fw-semibold">
                                <i class="bi bi-building me-2 text-primary"></i>Sucursal
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3"
                                id="sucursalNombreSello" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="numeroSerieSello" class="form-label fw-semibold">
                                <i class="bi bi-hash me-2 text-primary"></i>Número de Serie
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3"
                                id="numeroSerieSello" placeholder="30001000000400002345"
                                maxlength="20" required>
                        </div>

                        <div class="col-md-6">
                            <label for="fechaVigenciaSello" class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-2 text-primary"></i>Fecha de Vigencia
                            </label>
                            <input type="date" class="form-control form-control-lg rounded-3"
                                id="fechaVigenciaSello" required>
                        </div>

                        <div class="col-12">
                            <label for="passwordSelloModal" class="form-label fw-semibold">
                                <i class="bi bi-key me-2 text-primary"></i>Contraseña de Llave Privada
                            </label>
                            <input type="password" class="form-control form-control-lg rounded-3"
                                id="passwordSelloModal" placeholder="Contraseña del archivo .key" required>
                        </div>

                        <div class="col-md-6">
                            <label for="archivoCerModal" class="form-label fw-semibold">
                                <i class="bi bi-file-earmark-lock me-2 text-primary"></i>Certificado (.cer)
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3"
                                id="archivoCerModal" accept=".cer" required>
                            <div class="form-text">Archivo de certificado .cer</div>
                        </div>

                        <div class="col-md-6">
                            <label for="archivoKeyModal" class="form-label fw-semibold">
                                <i class="bi bi-key me-2 text-primary"></i>Llave Privada (.key)
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3"
                                id="archivoKeyModal" accept=".key" required>
                            <div class="form-text">Archivo de llave privada .key</div>
                        </div>

                        <!-- Vista Previa de Validación -->
                        <div class="col-12" id="validacionSelloPanel" style="display: none;">
                            <div class="card bg-light border-0 rounded-3">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-check-circle text-success me-2"></i>Validación de Archivos
                                    </h6>
                                    <div id="validacionSelloResultados">
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
                <button type="button" class="btn btn-warning me-2" id="btnValidarSelloModal">
                    <i class="bi bi-check-circle me-2"></i>Validar Archivos
                </button>
                <button type="button" class="btn btn-dark btn-lg fw-semibold" id="btnSubirSelloModal" disabled>
                    <i class="bi bi-shield-plus me-2"></i>Subir Sello
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let validacionSelloCompleta = false;

        // Datos de sucursales para los modales
        const sucursales = {
            centro: {
                nombre: 'Sucursal Centro',
                codigo: 'SUC-001',
                rfc: 'XAXX010101000'
            },
            norte: {
                nombre: 'Sucursal Norte',
                codigo: 'SUC-002',
                rfc: 'XAXX010101000'
            },
            sur: {
                nombre: 'Sucursal Sur',
                codigo: 'SUC-003',
                rfc: 'XAXX010101000'
            },
            oriente: {
                nombre: 'Sucursal Oriente',
                codigo: 'SUC-004',
                rfc: 'XAXX010101000'
            }
        }; // Funciones para abrir modales desde botones
        window.abrirModalConstancia = function(sucursalId) {
            const sucursal = sucursales[sucursalId];
            document.getElementById('sucursalConstanciaId').value = sucursalId;
            document.getElementById('sucursalNombreConstancia').value = sucursal.nombre;
            document.getElementById('rfcConstanciaModal').value = sucursal.rfc;

            const modal = new bootstrap.Modal(document.getElementById('modalConstancia'));
            modal.show();
        };

        window.abrirModalSello = function(sucursalId) {
            const sucursal = sucursales[sucursalId];
            document.getElementById('sucursalSelloId').value = sucursalId;
            document.getElementById('sucursalNombreSello').value = sucursal.nombre;

            const modal = new bootstrap.Modal(document.getElementById('modalSello'));
            modal.show();
        };

        // Validación RFC en constancia
        document.getElementById('rfcConstanciaModal').addEventListener('input', function() {
            const rfc = this.value.toUpperCase();
            this.value = rfc;

            const rfcPattern = /^[A-ZÑ&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z0-9]{2}[0-9A]$/;
            if (rfc.length === 13 && rfcPattern.test(rfc)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (rfc.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });

        // Preview de constancia al cargar archivo
        document.getElementById('archivoConstanciaModal').addEventListener('change', function() {
            const archivo = this.files[0];
            if (archivo) {
                const fechaEmision = document.getElementById('fechaEmisionConstancia').value;
                const fechaVigencia = document.getElementById('fechaVigenciaConstancia').value;

                if (fechaEmision && fechaVigencia) {
                    mostrarPreviewConstancia(archivo, fechaEmision, fechaVigencia);
                }
            }
        });

        function mostrarPreviewConstancia(archivo, fechaEmision, fechaVigencia) {
            const preview = document.getElementById('previewConstancia');
            const info = document.getElementById('infoConstancia');

            const diasVigencia = Math.ceil((new Date(fechaVigencia) - new Date()) / (1000 * 60 * 60 * 24));
            const estado = diasVigencia > 30 ? 'success' : diasVigencia > 0 ? 'warning' : 'danger';
            const estadoTexto = diasVigencia > 30 ? 'Vigente' : diasVigencia > 0 ? 'Por Vencer' : 'Vencida';

            info.innerHTML = `
                <div class="row g-3">
                    <div class="col-6">
                        <small class="text-muted d-block">Archivo</small>
                        <strong>${archivo.name}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Tamaño</small>
                        <strong>${(archivo.size / 1024 / 1024).toFixed(2)} MB</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Estado</small>
                        <span class="badge bg-${estado}">${estadoTexto}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Vigencia</small>
                        <strong>${diasVigencia} días restantes</strong>
                    </div>
                </div>
            `;

            preview.style.display = 'block';
        }

        // Subir constancia
        document.getElementById('btnSubirConstanciaModal').addEventListener('click', function() {
            const form = document.getElementById('formConstancia');
            const archivo = document.getElementById('archivoConstanciaModal').files[0];

            if (!archivo) {
                alert('Por favor selecciona un archivo PDF');
                return;
            }

            if (archivo.size > 5 * 1024 * 1024) {
                alert('El archivo es demasiado grande. Máximo 5 MB');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Subiendo...';

            setTimeout(() => {
                alert('¡Constancia subida exitosamente!');
                bootstrap.Modal.getInstance(document.getElementById('modalConstancia')).hide();
                form.reset();
                document.getElementById('previewConstancia').style.display = 'none';
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-cloud-upload me-2"></i>Subir Constancia';
                location.reload();
            }, 2000);
        });

        // Validar número de serie
        document.getElementById('numeroSerieSello').addEventListener('input', function() {
            const valor = this.value.replace(/\D/g, '');
            this.value = valor;

            if (valor.length === 20) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (valor.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });

        // Validar sello digital
        document.getElementById('btnValidarSelloModal').addEventListener('click', function() {
            const certificado = document.getElementById('archivoCerModal').files[0];
            const llave = document.getElementById('archivoKeyModal').files[0];
            const password = document.getElementById('passwordSelloModal').value;
            const numeroSerie = document.getElementById('numeroSerieSello').value;

            if (!certificado || !llave || !password || !numeroSerie) {
                alert('Por favor completa todos los campos requeridos');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Validando...';

            setTimeout(() => {
                const panel = document.getElementById('validacionSelloPanel');
                const resultados = document.getElementById('validacionSelloResultados');

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
                validacionSelloCompleta = true;
                document.getElementById('btnSubirSelloModal').disabled = false;

                this.disabled = false;
                this.innerHTML = '<i class="bi bi-check-circle me-2"></i>Validado';
                this.classList.remove('btn-warning');
                this.classList.add('btn-success');
            }, 2000);
        });

        // Subir sello digital
        document.getElementById('btnSubirSelloModal').addEventListener('click', function() {
            if (!validacionSelloCompleta) {
                alert('Por favor valida los archivos primero');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Subiendo...';

            setTimeout(() => {
                alert('¡Sello digital cargado exitosamente!');
                bootstrap.Modal.getInstance(document.getElementById('modalSello')).hide();
                document.getElementById('formSello').reset();
                document.getElementById('validacionSelloPanel').style.display = 'none';
                validacionSelloCompleta = false;

                this.disabled = true;
                this.innerHTML = '<i class="bi bi-shield-plus me-2"></i>Subir Sello';
                document.getElementById('btnValidarSelloModal').classList.remove('btn-success');
                document.getElementById('btnValidarSelloModal').classList.add('btn-warning');
                document.getElementById('btnValidarSelloModal').innerHTML = '<i class="bi bi-check-circle me-2"></i>Validar Archivos';

                location.reload();
            }, 3000);
        });

        // Calcular días restantes automáticamente en constancia
        document.getElementById('fechaVigenciaConstancia').addEventListener('change', function() {
            const fechaVigencia = new Date(this.value);
            const hoy = new Date();
            const diasRestantes = Math.ceil((fechaVigencia - hoy) / (1000 * 60 * 60 * 24));

            const archivo = document.getElementById('archivoConstanciaModal').files[0];
            const fechaEmision = document.getElementById('fechaEmisionConstancia').value;

            if (archivo && fechaEmision) {
                mostrarPreviewConstancia(archivo, fechaEmision, this.value);
            }
        });
    });

    // Funciones adicionales para gestión de certificados
    function descargarConstancia(sucursalId) {
        const sucursalNombres = {
            centro: 'Centro',
            norte: 'Norte',
            sur: 'Sur',
            oriente: 'Oriente'
        };

        alert('Descargando constancia fiscal de Sucursal ' + sucursalNombres[sucursalId] + '...');
        // Aquí iría la lógica real de descarga
    }

    function verDetallesSello(sucursalId) {
        const datos = {
            centro: {
                serie: '30001000000400002345',
                vigencia: '31/05/2026',
                facturas: '1,234',
                esteMes: '156'
            },
            norte: {
                serie: '30001000000400002346',
                vigencia: '15/08/2026',
                facturas: '987',
                esteMes: '98'
            },
            sur: {
                serie: 'No configurado',
                vigencia: 'N/A',
                facturas: '0',
                esteMes: '0'
            }
        };

        const info = datos[sucursalId];
        const sucursalNombre = sucursalId.charAt(0).toUpperCase() + sucursalId.slice(1);

        alert(`Detalles del Sello Digital - Sucursal ${sucursalNombre}
Serie: ${info.serie}
Vigencia: ${info.vigencia}
Facturas emitidas: ${info.facturas}
Este mes: ${info.esteMes}`);
    }

    function generarReporte(sucursalId) {
        const sucursalNombres = {
            centro: 'Centro',
            norte: 'Norte',
            sur: 'Sur',
            oriente: 'Oriente'
        };

        alert('Generando reporte completo de Sucursal ' + sucursalNombres[sucursalId] + '...\n\nIncluye:\n• Estado de documentos fiscales\n• Certificados digitales\n• Estadísticas de facturación\n• Actividad reciente');
    }

    function completarDocumentacion(sucursalId) {
        if (confirm('¿Deseas completar la documentación de esta sucursal?\n\nEsto te permitirá:\n• Subir constancia fiscal vigente\n• Configurar sello digital\n• Activar la sucursal')) {

            // Simular proceso de completar documentación
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-4">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title fw-bold">
                                    <i class="bi bi-list-check me-2"></i>Completar Documentación
                                </h5>
                            </div>
                            <div class="modal-body p-4">
                                <div class="text-center">
                                    <i class="bi bi-hourglass-split display-4 text-warning mb-3"></i>
                                    <h6>Preparando asistente de documentación...</h6>
                                    <div class="progress mt-3">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            setTimeout(() => {
                document.body.removeChild(modal);
                alert('¡Proceso iniciado!\n\nSe abrirá el asistente para:\n1. Subir constancia fiscal\n2. Configurar sello digital\n3. Activar sucursal');

                // Abrir modal de constancia primero
                abrirModalConstancia(sucursalId);
            }, 2000);
        }
    }

    function verEstadoRevision(sucursalId, tipoDocumento) {
        const documentoNombre = tipoDocumento === 'constancia' ? 'Constancia Fiscal' : 'Sello Digital';
        const sucursalNombre = sucursalId.charAt(0).toUpperCase() + sucursalId.slice(1);

        const estados = {
            constancia: {
                progreso: 75,
                etapas: [{
                        nombre: 'Carga de archivo',
                        completada: true
                    },
                    {
                        nombre: 'Validación de formato',
                        completada: true
                    },
                    {
                        nombre: 'Verificación RFC',
                        completada: true
                    },
                    {
                        nombre: 'Validación vigencia',
                        completada: false
                    },
                    {
                        nombre: 'Aprobación final',
                        completada: false
                    }
                ]
            },
            sello: {
                progreso: 50,
                etapas: [{
                        nombre: 'Carga de archivos',
                        completada: true
                    },
                    {
                        nombre: 'Validación certificado',
                        completada: true
                    },
                    {
                        nombre: 'Verificación llave',
                        completada: false
                    },
                    {
                        nombre: 'Prueba de firma',
                        completada: false
                    },
                    {
                        nombre: 'Activación',
                        completada: false
                    }
                ]
            }
        };

        const estado = estados[tipoDocumento];
        let etapasHTML = '';

        estado.etapas.forEach(etapa => {
            const icono = etapa.completada ? 'bi-check-circle text-success' : 'bi-clock text-warning';
            etapasHTML += `
                <div class="d-flex align-items-center mb-2">
                    <i class="bi ${icono} me-2"></i>
                    <span class="${etapa.completada ? 'text-success' : 'text-muted'}">${etapa.nombre}</span>
                </div>
            `;
        });

        alert(`Estado de Revisión - ${documentoNombre}
Sucursal ${sucursalNombre}

Progreso: ${estado.progreso}%

Etapas:
${estado.etapas.map(e => `${e.completada ? '✓' : '○'} ${e.nombre}`).join('\n')}

Tiempo estimado: ${tipoDocumento === 'constancia' ? '2-4 horas' : '4-8 horas'}`);
    }

    function verProgresoValidacion(sucursalId) {
        alert(`Progreso de Validación - Sucursal ${sucursalId.charAt(0).toUpperCase() + sucursalId.slice(1)}

Constancia Fiscal: 75% ✓
• Formato: Validado
• RFC: Validado  
• Vigencia: En proceso...

Sello Digital: 50% ⏳
• Certificado: Validado
• Llave privada: Verificando...
• Correspondencia: Pendiente

Tiempo estimado restante: 2-4 horas

Recibirás una notificación cuando la validación esté completa.`);
    }
</script>