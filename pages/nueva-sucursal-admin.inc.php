<!-- Crear Nueva Sucursal -->
<div class="container py-4">
    <!-- Título de la página -->
    <div class="row mb-4">
        <div class="col-8">
            <h2 class="text-primary fw-bold mb-0">
                <i class="bi bi-building display-6 text-primary"></i>
                Nueva Sucursal
            </h2>
            <p class="text-muted mb-0">Registra una nueva sucursal para tu empresa.</p>
        </div>
        <div class="col-4 text-end">
            <button type="button" class="btn btn-outline-primary btn-lg rounded-3" onclick="window.history.back()">
                <i class="bi bi-arrow-left me-2"></i>Regresar
            </button>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <form id="formNuevaSucursal">
        <div class="row g-4">
            <!-- Información Básica -->
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="bi bi-info-circle me-2"></i>Información Sucursal
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Nombre de la Sucursal -->
                            <div class="col-md-6">
                                <label for="nombreSucursal" class="form-label fw-semibold">
                                    Nombre de la Sucursal
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                    id="nombreSucursal" placeholder="Ej: Sucursal Centro" required>
                                <div class="form-text">Nombre identificativo de la sucursal</div>
                            </div>

                            <!-- Código de Sucursal -->
                            <div class="col-md-6">
                                <label for="codigoSucursal" class="form-label fw-semibold">
                                    Código de Sucursal
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                    id="codigoSucursal" placeholder="SUC-004" required>
                                <div class="form-text">Código único para identificar la sucursal</div>
                            </div>

                            <!-- Razón Social -->
                            <div class="col-12">
                                <label for="razonSocial" class="form-label fw-semibold">
                                    Razón Social
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                    id="razonSocial" placeholder="Nombre de la empresa" required>
                            </div>

                            <!-- RFC -->
                            <div class="col-md-6">
                                <label for="rfcSucursal" class="form-label fw-semibold">
                                    RFC
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                    id="rfcSucursal" placeholder="XAXX010101000" required>
                                <div class="form-text">Registro Federal de Contribuyentes</div>
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <label for="telefonoSucursal" class="form-label fw-semibold">Teléfono
                                </label>
                                <input type="tel" class="form-control form-control-lg rounded-3"
                                    id="telefonoSucursal" placeholder="55-1234-5678">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="emailSucursal" class="form-label fw-semibold">Email
                                </label>
                                <input type="email" class="form-control form-control-lg rounded-3"
                                    id="emailSucursal" placeholder="sucursal@empresa.com">
                            </div>

                            <!-- Estado/Estatus -->
                            <div class="col-md-6">
                                <label for="estadoSucursal" class="form-label fw-semibold">
                                    Estado
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
                            <!-- Calle -->
                            <div class="col-md-8">
                                <label for="calleSucursal" class="form-label fw-semibold">
                                    Calle
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                    id="calleSucursal" placeholder="Av. Reforma" required>
                            </div>

                            <!-- Código Postal -->
                            <div class="col-md-4">
                                <label for="codigoPostal" class="form-label fw-semibold">
                                    Código Postal
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                    id="codigoPostal" placeholder="06000" required>
                            </div>

                            <!-- Colonia -->
                            <div class="col-md-6">
                                <label for="colonia" class="form-label fw-semibold">
                                    Colonia
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                    id="colonia" placeholder="Centro" required>
                            </div>

                            <!-- Municipio/Delegación -->
                            <div class="col-md-6">
                                <label for="municipio" class="form-label fw-semibold">
                                    Municipio/Delegación
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                    id="municipio" placeholder="Cuauhtémoc" required>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <label for="estado" class="form-label fw-semibold">
                                    Estado
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

                            <!-- numero exterior e interior ocpional -->
                            <div class="col-md-3">
                                <label for="numExterior" class="form-label fw-semibold">
                                    Número Exterior
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                    id="numExterior" placeholder="123" required>
                            </div>
                            <div class="col-md-3">
                                <label for="numInterior" class="form-label fw-semibold">
                                    Número Interior
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3"
                                    id="numInterior" placeholder="(opcional)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentos Fiscales -->
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold text-warning mb-3">
                            <i class="bi bi-shield-check me-2"></i>Documentos Fiscales
                        </h5>
                        <p class="text-muted small mb-0">
                            Puedes subir estos documentos ahora o después desde la gestión de sucursales
                        </p>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Constancia de Situación Fiscal -->
                            <div class="col-lg-6">
                                <div class="card shadow-lg border-0 rounded-4 border-3 border-primary border-dashed">
                                    <div class="card-body p-5 text-center">
                                        <i class="bi bi-cloud-upload display-1 text-primary mb-3 opacity-50"></i>
                                        <h5 class="text-primary fw-bold mb-2">Subir Constancia de Situación Fiscal</h5>
                                        <p class="text-muted mb-4">Agrega la constancia fiscal para la sucursal</p>
                                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#subirConstanciaModal">
                                            Seleccionar Archivo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Sello Digital (CSD) -->
                            <div class="col-lg-6">
                                <div class="card shadow-lg border-0 rounded-4 border-3 border-primary border-dashed">
                                    <div class="card-body p-5 text-center">
                                        <i class="bi bi-cloud-upload display-1 text-info mb-3 opacity-50"></i>
                                        <h5 class="text-info fw-bold mb-2">Subir Sello Digital</h5>
                                        <p class="text-muted mb-4">Certificado (.cer) y llave privada (.key) para facturación</p>
                                        <button class="btn btn-info btn-lg" data-bs-toggle="modal" data-bs-target="#subirSelloModal">
                                            Seleccionar Archivo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 rounded-3 mt-4">
                            <div class="d-flex">
                                <i class="bi bi-lightbulb me-2 mt-1"></i>
                                <div>
                                    <small>
                                        <strong>¿No tienes los documentos ahora?</strong> No te preocupes,
                                        puedes crearlos después y subirlos desde la gestión de sucursales.
                                        La sucursal funcionará normalmente, pero necesitarás estos documentos para facturar.
                                    </small>
                                </div>
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


<!-- Modal para Subir Constancia -->
<div class="modal fade" id="subirConstanciaModal" tabindex="-1" aria-labelledby="subirConstanciaModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="subirConstanciaModalLabel">
                    <i class="bi bi-cloud-upload me-2"></i>Subir Constancia de Situación Fiscal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- los campos del formulario se llenaran automaticamente con la imformacion de la constancia de situación fiscal -->
                <div class="alert alert-info border-0 rounded-3">
                    <div class="d-flex">
                        <i class="bi bi-info-circle me-2 mt-1"></i>
                        <div>
                            <h6 class="mb-1">Información Importante</h6>
                            <small>
                                • La constancia debe estar vigente al momento de la carga<br>
                                • El RFC debe coincidir con el registrado en la sucursal<br>
                                • Solo se aceptan archivos en formato PDF de máximo 5 MB<br>
                                • Los campos se llenarán automáticamente al seleccionar el archivo<br>
                            </small>
                        </div>
                    </div>
                </div>
                <form id="formSubirConstancia">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sucursalConstancia" class="form-label fw-semibold">
                                Sucursal
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3"
                                id="sucursalConstancia" placeholder="Sucursal Centro" readonly>
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
                                Fecha de Vigencia
                            </label>
                            <input type="date" class="form-control form-control-lg rounded-3"
                                id="fechaVigencia" required>
                        </div>

                        <div class="col-md-6">
                            <label for="rfcConstancia" class="form-label fw-semibold">
                                RFC
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3"
                                id="rfcConstancia" placeholder="XAXX010101000" required>
                        </div>

                        <div class="col-12">
                            <label for="archivoConstancia" class="form-label fw-semibold">
                                Archivo PDF
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3"
                                id="archivoConstancia" accept=".pdf" required>
                            <div class="form-text">Solo archivos PDF, máximo 5 MB</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary btn-lg" id="btnSubirConstancia">
                    Subir Constancia
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para subir Sello Digital -->
<div class="modal fade" id="subirSelloModal" tabindex="-1" aria-labelledby="subirSelloModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold" id="subirSelloModalLabel">
                    <i class="bi bi-shield-check me-2"></i>Configurar Sello Digital (CSD)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formSelloNuevo">
                    <div class="alert alert-info border-0 rounded-3 mb-4">
                        <div class="d-flex">
                            <i class="bi bi-shield-exclamation me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Información de Seguridad</h6>
                                <ul class="small mb-0">
                                    <li>Los archivos se almacenan de forma encriptada</li>
                                    <li>La contraseña se validará pero no se guardará</li>
                                    <li>Se verificará la correspondencia entre certificado y llave</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="numeroSerieNuevo" class="form-label fw-semibold">
                                Número de Serie
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3"
                                id="numeroSerieNuevo" placeholder="30001000000400002345"
                                maxlength="20" required>
                        </div>

                        <div class="col-md-6">
                            <label for="fechaVigenciaNuevo" class="form-label fw-semibold">
                                Fecha de Vigencia
                            </label>
                            <input type="date" class="form-control form-control-lg rounded-3"
                                id="fechaVigenciaNuevo" required>
                        </div>

                        <div class="col-12">
                            <label for="passwordNuevo" class="form-label fw-semibold">
                                Contraseña de Llave Privada
                            </label>
                            <input type="password" class="form-control form-control-lg rounded-3"
                                id="passwordNuevo" placeholder="Contraseña del archivo .key" required>
                        </div>

                        <div class="col-md-6">
                            <label for="archivoCerNuevo" class="form-label fw-semibold">
                                Certificado (.cer)
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3"
                                id="archivoCerNuevo" accept=".cer" required>
                            <div class="form-text">Archivo de certificado .cer</div>
                        </div>

                        <div class="col-md-6">
                            <label for="archivoKeyNuevo" class="form-label fw-semibold">
                                Llave Privada (.key)
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3"
                                id="archivoKeyNuevo" accept=".key" required>
                            <div class="form-text">Archivo de llave privada .key</div>
                        </div>

                        <!-- Estado de validación -->
                        <div class="col-12" id="validacionNuevoPanel" style="display: none;">
                            <div class="card bg-light border-0 rounded-3">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">
                                        Estado de Validación
                                    </h6>
                                    <div id="validacionNuevoResultados">
                                        <!-- Resultados dinámicos -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-info me-2 text-white btn-lg" id="btnValidarNuevo">
                    <i class="bi bi-check-circle me-2"></i>Validar
                </button>
            </div>
        </div>
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

        // Manejar carga de constancia fiscal
        document.getElementById('constanciaFile').addEventListener('change', function() {
            const archivo = this.files[0];
            if (archivo) {
                if (archivo.type !== 'application/pdf') {
                    alert('Solo se aceptan archivos PDF');
                    this.value = '';
                    return;
                }

                if (archivo.size > 5 * 1024 * 1024) {
                    alert('El archivo es demasiado grande. Máximo 5 MB');
                    this.value = '';
                    return;
                }

                document.getElementById('constanciaName').textContent = archivo.name + ' (' + (archivo.size / 1024 / 1024).toFixed(2) + ' MB)';
                document.getElementById('constanciaInfo').style.display = 'block';
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selloValidado = false;

        // Validar número de serie
        document.getElementById('numeroSerieNuevo').addEventListener('input', function() {
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

        // Validar sello
        document.getElementById('btnValidarNuevo').addEventListener('click', function() {
            const certificado = document.getElementById('archivoCerNuevo').files[0];
            const llave = document.getElementById('archivoKeyNuevo').files[0];
            const password = document.getElementById('passwordNuevo').value;
            const numeroSerie = document.getElementById('numeroSerieNuevo').value;

            if (!certificado || !llave || !password || numeroSerie.length !== 20) {
                alert('Por favor completa todos los campos correctamente');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Validando...';

            setTimeout(() => {
                const panel = document.getElementById('validacionNuevoPanel');
                const resultados = document.getElementById('validacionNuevoResultados');

                resultados.innerHTML = `
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small>Certificado válido</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small>Llave válida</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small>Contraseña correcta</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small>Serie coincide</small>
                            </div>
                        </div>
                    </div>
                `;

                panel.style.display = 'block';
                selloValidado = true;
                document.getElementById('btnConfigurearSello').disabled = false;

                this.disabled = false;
                this.innerHTML = '<i class="bi bi-check-circle me-2"></i>Validado';
                this.classList.remove('btn-info');
                this.classList.add('btn-success');
            }, 2000);
        });

        // Configurar sello
        document.getElementById('btnConfigurearSello').addEventListener('click', function() {
            if (!selloValidado) {
                alert('Por favor valida el sello primero');
                return;
            }

            const numeroSerie = document.getElementById('numeroSerieNuevo').value;
            const fechaVigencia = document.getElementById('fechaVigenciaNuevo').value;

            document.getElementById('selloDetails').textContent =
                `Serie: ${numeroSerie} | Vigencia: ${fechaVigencia}`;
            document.getElementById('selloInfo').style.display = 'block';

            bootstrap.Modal.getInstance(document.getElementById('modalSelloNuevo')).hide();

            // Reset modal
            document.getElementById('formSelloNuevo').reset();
            document.getElementById('validacionNuevoPanel').style.display = 'none';
            document.getElementById('btnValidarNuevo').classList.remove('btn-success');
            document.getElementById('btnValidarNuevo').classList.add('btn-info');
            document.getElementById('btnValidarNuevo').innerHTML = '<i class="bi bi-check-circle me-2"></i>Validar';
            document.getElementById('btnConfigurearSello').disabled = true;
            selloValidado = false;

            alert('¡Sello digital configurado correctamente!');
        });
    });
</script>