<!-- Crear Nueva Sucursal -->
<div class="content-wrapper bg-light loaded">
    <!-- Header -->
    <div class="bg-primary text-white py-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                            <i class="bi bi-plus-circle display-6 text-white"></i>
                        </div>
                        <div>
                            <h1 class="fw-bold mb-2">Nueva Sucursal</h1>
                            <p class="lead mb-0 opacity-90">Registra una nueva ubicación para tu empresa</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="panel?pg=sucursales-admin" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <form id="formNuevaSucursal">
            <div class="row g-4">
                <!-- Información Básica -->
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="bi bi-info-circle me-2"></i>Información Básica
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <!-- Nombre de la Sucursal -->
                                <div class="col-md-6">
                                    <label for="nombreSucursal" class="form-label fw-semibold">
                                        <i class="bi bi-building me-2 text-primary"></i>Nombre de la Sucursal
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" 
                                           id="nombreSucursal" placeholder="Ej: Sucursal Centro" required>
                                    <div class="form-text">Nombre identificativo de la sucursal</div>
                                </div>

                                <!-- Código de Sucursal -->
                                <div class="col-md-6">
                                    <label for="codigoSucursal" class="form-label fw-semibold">
                                        <i class="bi bi-hash me-2 text-primary"></i>Código de Sucursal
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" 
                                           id="codigoSucursal" placeholder="SUC-004" required>
                                    <div class="form-text">Código único para identificar la sucursal</div>
                                </div>

                                <!-- Razón Social -->
                                <div class="col-12">
                                    <label for="razonSocial" class="form-label fw-semibold">
                                        <i class="bi bi-briefcase me-2 text-primary"></i>Razón Social
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" 
                                           id="razonSocial" placeholder="Nombre legal de la empresa" required>
                                </div>

                                <!-- RFC -->
                                <div class="col-md-6">
                                    <label for="rfcSucursal" class="form-label fw-semibold">
                                        <i class="bi bi-card-text me-2 text-primary"></i>RFC
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" 
                                           id="rfcSucursal" placeholder="XAXX010101000" required>
                                    <div class="form-text">Registro Federal de Contribuyentes</div>
                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-6">
                                    <label for="telefonoSucursal" class="form-label fw-semibold">
                                        <i class="bi bi-telephone me-2 text-primary"></i>Teléfono
                                    </label>
                                    <input type="tel" class="form-control form-control-lg rounded-3" 
                                           id="telefonoSucursal" placeholder="55-1234-5678">
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="emailSucursal" class="form-label fw-semibold">
                                        <i class="bi bi-envelope me-2 text-primary"></i>Email
                                    </label>
                                    <input type="email" class="form-control form-control-lg rounded-3" 
                                           id="emailSucursal" placeholder="sucursal@empresa.com">
                                </div>

                                <!-- Estado/Estatus -->
                                <div class="col-md-6">
                                    <label for="estadoSucursal" class="form-label fw-semibold">
                                        <i class="bi bi-toggle-on me-2 text-primary"></i>Estado Inicial
                                    </label>
                                    <select class="form-select form-select-lg rounded-3" id="estadoSucursal">
                                        <option value="activa" selected>Activa</option>
                                        <option value="inactiva">Inactiva</option>
                                        <option value="mantenimiento">En Mantenimiento</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="card shadow-lg border-0 rounded-4 mt-4">
                        <div class="card-header bg-white border-0 pb-0">
                            <h5 class="fw-bold text-success mb-3">
                                <i class="bi bi-geo-alt me-2"></i>Dirección Fiscal
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <!-- Calle y Número -->
                                <div class="col-md-8">
                                    <label for="calleSucursal" class="form-label fw-semibold">
                                        <i class="bi bi-signpost me-2 text-success"></i>Calle y Número
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" 
                                           id="calleSucursal" placeholder="Av. Reforma 123" required>
                                </div>

                                <!-- Código Postal -->
                                <div class="col-md-4">
                                    <label for="codigoPostal" class="form-label fw-semibold">
                                        <i class="bi bi-mailbox me-2 text-success"></i>Código Postal
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" 
                                           id="codigoPostal" placeholder="06000" required>
                                </div>

                                <!-- Colonia -->
                                <div class="col-md-6">
                                    <label for="colonia" class="form-label fw-semibold">
                                        <i class="bi bi-house me-2 text-success"></i>Colonia
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" 
                                           id="colonia" placeholder="Centro" required>
                                </div>

                                <!-- Municipio/Delegación -->
                                <div class="col-md-6">
                                    <label for="municipio" class="form-label fw-semibold">
                                        <i class="bi bi-map me-2 text-success"></i>Municipio/Delegación
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" 
                                           id="municipio" placeholder="Cuauhtémoc" required>
                                </div>

                                <!-- Estado -->
                                <div class="col-md-6">
                                    <label for="estado" class="form-label fw-semibold">
                                        <i class="bi bi-globe me-2 text-success"></i>Estado
                                    </label>
                                    <select class="form-select form-select-lg rounded-3" id="estado" required>
                                        <option value="">Selecciona un estado</option>
                                        <option value="CDMX">Ciudad de México</option>
                                        <option value="JAL">Jalisco</option>
                                        <option value="NL">Nuevo León</option>
                                        <option value="BCN">Baja California Norte</option>
                                        <!-- Más estados... -->
                                    </select>
                                </div>

                                <!-- País -->
                                <div class="col-md-6">
                                    <label for="pais" class="form-label fw-semibold">
                                        <i class="bi bi-flag me-2 text-success"></i>País
                                    </label>
                                    <select class="form-select form-select-lg rounded-3" id="pais" required>
                                        <option value="MX" selected>México</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Lateral -->
                <div class="col-lg-4">
                    <!-- Resumen -->
                    <div class="card shadow-lg border-0 rounded-4 mb-4">
                        <div class="card-header bg-primary text-white rounded-top-4">
                            <h6 class="fw-bold mb-0">
                                <i class="bi bi-clipboard-check me-2"></i>Resumen de Registro
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info border-0 rounded-3 mb-3">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-2 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1">Siguiente Paso</h6>
                                        <small>Después de crear la sucursal, podrás subir los certificados digitales</small>
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-muted mb-3">Documentos Requeridos:</h6>
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-center mb-2">
                                    <i class="bi bi-file-earmark-pdf text-warning me-2"></i>
                                    <small>Constancia de Situación Fiscal</small>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <i class="bi bi-award text-info me-2"></i>
                                    <small>Certificado de Sello Digital</small>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <i class="bi bi-key text-danger me-2"></i>
                                    <small>Clave Privada (.key)</small>
                                </li>
                            </ul>

                            <div class="alert alert-success border-0 rounded-3 mt-3">
                                <div class="d-flex">
                                    <i class="bi bi-shield-check me-2 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1">Seguridad</h6>
                                        <small>Los documentos se almacenan de forma segura y encriptada</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración Inicial -->
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-warning text-dark rounded-top-4">
                            <h6 class="fw-bold mb-0">
                                <i class="bi bi-gear me-2"></i>Configuración Inicial
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="activarFacturacion" checked>
                                <label class="form-check-label fw-semibold" for="activarFacturacion">
                                    Activar Facturación
                                </label>
                                <div class="form-text">Permitir generar facturas desde esta sucursal</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="notificaciones" checked>
                                <label class="form-check-label fw-semibold" for="notificaciones">
                                    Notificaciones
                                </label>
                                <div class="form-text">Recibir alertas sobre certificados y tickets</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="reportesAutomaticos">
                                <label class="form-check-label fw-semibold" for="reportesAutomaticos">
                                    Reportes Automáticos
                                </label>
                                <div class="form-text">Generar reportes diarios automáticamente</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">¿Listo para crear la sucursal?</h6>
                                    <small class="text-muted">Verifica que toda la información sea correcta</small>
                                </div>
                                <div class="d-flex gap-3">
                                    <button type="button" class="btn btn-outline-secondary btn-lg" 
                                            onclick="window.history.back()">
                                        <i class="bi bi-x-circle me-2"></i>Cancelar
                                    </button>
                                    <button type="button" class="btn btn-warning btn-lg" id="btnGuardarBorrador">
                                        <i class="bi bi-save me-2"></i>Guardar Borrador
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-plus-circle me-2"></i>Crear Sucursal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formNuevaSucursal');
        
        // Generar código automático
        document.getElementById('nombreSucursal').addEventListener('input', function() {
            const nombre = this.value;
            if (nombre) {
                // Generar código basado en el nombre
                const codigo = 'SUC-' + nombre.substring(0, 3).toUpperCase() + '-' + 
                              Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                document.getElementById('codigoSucursal').value = codigo;
            }
        });

        // Validación de CP y carga automática de datos
        document.getElementById('codigoPostal').addEventListener('blur', function() {
            const cp = this.value;
            if (cp.length === 5) {
                // Aquí iría una consulta a API de códigos postales
                console.log('Validando código postal:', cp);
                // Simular datos automáticos
                if (cp === '06000') {
                    document.getElementById('colonia').value = 'Centro';
                    document.getElementById('municipio').value = 'Cuauhtémoc';
                    document.getElementById('estado').value = 'CDMX';
                }
            }
        });

        // Submit del formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar campos requeridos
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (isValid) {
                // Simular creación exitosa
                alert('¡Sucursal creada exitosamente!\nAhora puedes subir los certificados digitales.');
                window.location.href = 'panel?pg=sucursales-admin';
            } else {
                alert('Por favor, completa todos los campos requeridos.');
            }
        });

        // Guardar borrador
        document.getElementById('btnGuardarBorrador').addEventListener('click', function() {
            const formData = new FormData(form);
            // Aquí iría la lógica para guardar como borrador
            alert('Borrador guardado exitosamente.');
        });
    });
</script>