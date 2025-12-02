<!-- Crear Nueva Sucursal -->
<meta charset="UTF-8">
<style>
    .logo-upload-container {
        display: flex;
        gap: 20px;
        align-items: center;
        padding: 20px;
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .logo-upload-container:hover {
        border-color: #0d6efd;
        background: #f0f8ff;
    }

    .logo-preview {
        flex-shrink: 0;
        width: 120px;
        height: 120px;
        border-radius: 12px;
        border: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        overflow: hidden;
        position: relative;
    }

    .logo-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 10px;
    }

    .logo-placeholder {
        text-align: center;
        color: #6c757d;
    }

    .logo-controls {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .logo-controls .btn {
        align-self: flex-start;
    }

    @media (max-width: 768px) {
        .logo-upload-container {
            flex-direction: column;
            text-align: center;
        }

        .logo-controls .btn {
            align-self: center;
        }
    }
</style>
<div class="content-wrapper bg-light">
    <div class="container py-4">
        <!-- Título de la página -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="mb-1 fw-bold text-primary"><i class="bi bi-building-fill text-primary fs-2"></i>Nueva Sucursal</h2>
                                <p class="text-muted mb-0">Registra una nueva sucursal para tu empresa</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-outline-primary btn-lg rounded-3 fw-semibold" onclick="window.history.back()">
                            <i class="bi bi-arrow-left me-2"></i>Regresar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Básica -->
        <div class="row mb-4">
            <div class="col-12">
                <form id="formNuevaSucursal">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0 text-primary">
                                    <i class="bi bi-info-circle me-2"></i>Información de la Sucursal
                                </h5>
                            </div>
                            <div class="row g-3">
                                <!-- Razon social de la Sucursal -->
                                <div class="col-md-6">
                                    <label for="nombreSucursal" class="form-label fw-semibold">
                                        Razon Social
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3"
                                        id="razonSocial" placeholder="Ej: Empresa XYZ" required>
                                    <div class="form-text">Nombre de la empresa</div>
                                </div>

                                <!-- Código de Sucursal -->
                                <div class="col-md-3">
                                    <label for="codigoSucursal" class="form-label fw-semibold">
                                        Código de Sucursal
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3"
                                        id="codigoSucursal" placeholder="SUC-004" required>
                                    <div class="form-text">Código único para identificar la sucursal</div>
                                </div>
                                <!-- Estado/Estatus -->
                                <div class="col-md-3">
                                    <label for="estadoSucursal" class="form-label fw-semibold">
                                        Estado
                                    </label>
                                    <select class="form-select form-select-lg rounded-3" id="estadoSucursal">
                                        <option value="activa" selected>Activa</option>
                                        <option value="inactiva">Inactiva</option>
                                    </select>
                                </div>
                                <!-- nombre comercial de la sucursal -->
                                <div class="col-md-6">
                                    <label for="nombreComercial" class="form-label fw-semibold">
                                        Nombre Comercial
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3"
                                        id="nombreComercial" placeholder="Ej: Sucursal Centro" required>
                                    <div class="form-text">Nombre comercial de la sucursal</div>
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

                                <!-- Regimen Fiscal -->
                                <div class="col-md-6">
                                    <label for="regimenFiscal" class="form-label fw-semibold">
                                        Régimen Fiscal
                                    </label>
                                    <select class="form-select form-select-lg rounded-3" id="regimenFiscal" required>
                                        <option value="" selected>Selecciona un régimen fiscal</option>
                                        <!-- Las opciones se cargarán dinámicamente -->
                                    </select>
                                </div>
                                <!-- correo electrónico de la sucursal -->
                                <div class="col-md-6">
                                    <label for="emailSucursal" class="form-label fw-semibold">
                                        Correo Electrónico
                                    </label>
                                    <input type="email" class="form-control form-control-lg rounded-3"
                                        id="emailSucursal" placeholder="correo@sucursal.com" required>
                                    <div class="form-text">Correo electrónico</div>
                                </div>

                            </div>
                        </div>
                        
                        <!-- Logo de la Sucursal -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="fw-bold mb-0 text-primary">
                                        <i class="bi bi-image me-2"></i>Logotipo de la Sucursal
                                    </h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-6">
                                        <label for="logoSucursal" class="form-label fw-semibold">
                                            Logotipo
                                        </label>
                                        <div class="logo-upload-container">
                                            <div class="logo-preview" id="logoPreview">
                                                <div class="logo-placeholder">
                                                    <i class="bi bi-image display-5 text-muted"></i>
                                                    <p class="text-muted mb-0">Vista previa del logo</p>
                                                </div>
                                            </div>
                                            <div class="logo-controls">
                                                <input type="file" class="form-control d-none" id="logoSucursal" accept=".png,.jpg,.jpeg,.svg" />
                                                <button type="button" class="btn btn-outline-primary rounded-3 fw-semibold" id="btnSeleccionarLogo">
                                                    <i class="bi bi-cloud-upload me-2"></i>Seleccionar Logo
                                                </button>
                                                <button type="button" class="btn btn-outline-danger rounded-3 fw-semibold d-none" id="btnEliminarLogo">
                                                    <i class="bi bi-trash me-2"></i>Eliminar
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-text">
                                            <small class="text-muted">
                                                Formatos: PNG, JPG, JPEG, SVG • Tamaño máximo: 2MB • Recomendado: 200x200px
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                <!-- Dirección Fiscal -->
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="fw-bold mb-0 text-primary">
                                                <i class="bi bi-geo-alt-fill me-2"></i>Dirección Fiscal
                                            </h5>
                                        </div>
                                        <div class="row g-3">
                                            <!-- Calle -->
                                            <div class="col-md-12">
                                                <label for="calleSucursal" class="form-label fw-semibold">
                                                    Direccion
                                                </label>
                                                <input type="text" class="form-control form-control-lg rounded-3"
                                                    id="direccionSucursal" placeholder="Av. Reforma 0123" required>
                                            </div>

                                            <!-- Código Postal -->
                                            <div class="col-md-4">
                                                <label for="codigoPostal" class="form-label fw-semibold">
                                                    Código Postal
                                                </label>
                                                <input type="text" class="form-control form-control-lg rounded-3"
                                                    id="codigoPostal" placeholder="72400" maxlength="5" required>
                                                <div class="form-text" id="cpStatus">Ingresa el código postal de 5 dígitos</div>
                                            </div>

                                            <!-- Colonia -->
                                            <div class="col-md-8">
                                                <label for="colonia" class="form-label fw-semibold">Colonia</label>
                                                <select class="form-select form-select-lg rounded-3" id="colonia" required>
                                                    <option value="">Selecciona una colonia</option>
                                                </select>
                                            </div>

                                            <!-- Municipio/Delegación -->
                                            <div class="col-md-6">
                                                <label for="municipio" class="form-label fw-semibold">
                                                    Municipio/Delegación
                                                </label>
                                                <input type="text" class="form-control form-control-lg rounded-3"
                                                    id="municipio" placeholder="Se llenará automáticamente" readonly required>
                                                <div class="form-text">Se llena automáticamente con el código postal</div>
                                            </div>

                                            <!-- Estado -->
                                            <div class="col-md-6">
                                                <label for="estado" class="form-label fw-semibold">
                                                    Estado
                                                </label>
                                                <input type="text" class="form-control form-control-lg rounded-3"
                                                    id="estado" placeholder="Se llenará automáticamente" readonly required>
                                                <div class="form-text">Se llena automáticamente con el código postal</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                </form>
            </div>
        </div>

        <!-- Documentos Fiscales -->
        <div class="row mb-4 py-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1 text-primary">
                                    <i class="bi bi-shield-check me-2"></i>Documentos Fiscales
                                </h5>
                                <p class="text-muted small mb-0">
                                    Puedes subir estos documentos ahora o después desde la gestión de sucursales
                                </p>
                            </div>
                        </div>
                        <div class="row g-4">
                            <!-- Constancia de Situación Fiscal -->
                            <!-- <div class="col-lg-6">
                                <div class="card border-2 border-primary border-dashed rounded-4 h-100">
                                    <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                                        <div class="mb-3">
                                            <i class="bi bi-file-earmark-pdf display-1 text-primary opacity-75"></i>
                                        </div>
                                        <h6 class="text-primary fw-bold mb-2">Constancia de Situación Fiscal</h6>
                                        <p class="text-muted small mb-3">Archivo PDF de la constancia fiscal del SAT</p>
                                        <button type="button" class="btn btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#subirConstanciaModal">
                                            <i class="bi bi-cloud-upload me-2"></i>Seleccionar Archivo
                                        </button>
                                        <small class="text-muted mt-2">Opcional - Máx. 5MB</small>
                                    </div>
                                </div>
                            </div> -->
                            <!-- Sello Digital (CSD) -->
                            <div class="col-lg-12">
                                <div class="card border-2 border-primary border-dashed rounded-4 h-100">
                                    <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                                        <div class="mb-3">
                                            <i class="bi bi-shield-lock display-1 text-primary opacity-75"></i>
                                        </div>
                                        <h6 class="text-primary fw-bold mb-2">Sello Digital (CSD)</h6>
                                        <p class="text-muted small mb-3">Certificado (.cer) y llave (.key) para facturación</p>
                                        <button type="button" class="btn btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#subirSelloModal">
                                            <i class="bi bi-shield-plus me-2"></i>Configurar Sello
                                        </button>
                                        <small class="text-muted mt-2">Requerido para facturar</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Botones de Acción -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-1 fw-bold">Verifica que toda la información sea correcta antes de continuar</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 justify-content-md-end mt-3 mt-md-0">
                                    <button type="button" class="btn btn-outline-secondary rounded-3 fw-semibold"
                                        onclick="window.history.back()">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary rounded-3 fw-semibold" id="btnCrearSucursal">
                                        Crear Sucursal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal para Subir Constancia -->
<!-- <div class="modal fade" id="subirConstanciaModal" tabindex="-1" aria-labelledby="subirConstanciaModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="subirConstanciaModalLabel">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Subir Constancia de Situación Fiscal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info border-0 rounded-3 mb-4">
                    <div class="d-flex">
                        <i class="bi bi-info-circle-fill me-3 fs-5 text-info"></i>
                        <div>
                            <h6 class="mb-2 fw-bold">Requisitos del archivo</h6>
                            <ul class="small mb-0 ps-3">
                                <li>La constancia debe estar vigente</li>
                                <li>El RFC debe coincidir con el registrado</li>
                                <li>Solo archivos PDF de máximo 5 MB</li>
                                <li>Los campos se llenarán automáticamente</li>
                            </ul>
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
            <div class="modal-footer border-0 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary rounded-3 fw-semibold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary rounded-3 fw-semibold" id="btnSubirConstancia">
                    <i class="bi bi-cloud-upload me-2"></i>Subir Constancia
                </button>
            </div>
        </div>
    </div>
</div> -->

<!-- Modal para subir Sello Digital -->
<div class="modal fade" id="subirSelloModal" tabindex="-1" aria-labelledby="subirSelloModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="subirSelloModalLabel">
                    <i class="bi bi-shield-lock me-2"></i>Configurar Sello Digital (CSD)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formSelloNuevo">
                    <div class="alert alert-info border-0 rounded-3 mb-4">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill me-3 fs-5 text-info"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Sello Digital (CSD)</h6>
                                <p class="small mb-0">
                                    Sube los archivos del certificado digital (.cer) y la llave privada (.key) 
                                    necesarios para la facturación electrónica. Los archivos se almacenarán de forma segura.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="archivoCerNuevo" class="form-label fw-semibold">
                                Certificado (.cer)
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3"
                                id="archivoCerNuevo" accept=".cer" required>
                            <div class="form-text">Selecciona el archivo de certificado .cer</div>
                        </div>

                        <div class="col-md-6">
                            <label for="archivoKeyNuevo" class="form-label fw-semibold">
                                Llave Privada (.key)
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3"
                                id="archivoKeyNuevo" accept=".key" required>
                            <div class="form-text">Selecciona el archivo de llave privada .key</div>
                        </div>

                        <div class="col-12">
                            <label for="clavePrivada" class="form-label fw-semibold">
                                <i class="bi bi-key me-2"></i>Clave Privada
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg rounded-start-3"
                                    id="clavePrivada" placeholder="Ingresa la clave privada" required>
                                <button class="btn btn-outline-secondary rounded-end-3" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-shield-check text-success me-1"></i>
                                Esta contraseña se almacenará de forma cifrada y segura
                            </div>
                        </div>

                        <!-- Estado de los archivos seleccionados -->
                        <div class="col-12" id="archivosSeleccionados" style="display: none;">
                            <div class="card bg-light border-0 rounded-3">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bi bi-check-circle text-success me-2"></i>Archivos Seleccionados
                                    </h6>
                                    <div id="listaArchivos">
                                        <!-- Lista de archivos seleccionados -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary rounded-3 fw-semibold" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary text-white rounded-3 fw-semibold" id="btnGuardarSello">
                    Guardar Sello Digital
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Cargar regímenes fiscales
    async function cargarRegimenesFiscales() {
        try {
            const response = await fetch('core/listar-regimen-fiscal.php');
            const result = await response.json();
            if (result.success && result.data) {
                const select = document.getElementById('regimenFiscal');
                select.innerHTML = '<option value="">Selecciona tu régimen fiscal</option>';
                result.data.forEach(regimen => {
                    const option = document.createElement('option');
                    option.value = regimen.codigo;
                    option.textContent = `${regimen.codigo} - ${regimen.descripcion}`;
                    select.appendChild(option);
                });
            } else {
                console.error('Error al cargar regímenes fiscales:', result.message || 'Respuesta inválida');
                document.getElementById('regimenFiscal').innerHTML = '<option value="">Error al cargar regímenes</option>';
            }
        } catch (error) {
            console.error('Error al obtener regímenes fiscales:', error);
            document.getElementById('regimenFiscal').innerHTML = '<option value="">Error de conexión</option>';
        }
    }

    // Función para cargar datos del código postal
    async function cargarDatosCP(codigoPostal) {
        const selectColonias = document.getElementById('colonia');
        const statusDiv = document.getElementById('cpStatus');
        const infoUbicacion = document.getElementById('infoUbicacion');
        const ubicacionTexto = document.getElementById('ubicacionTexto');
        const municipioInput = document.getElementById('municipio');
        const estadoInput = document.getElementById('estado');

        console.log('Validando código postal:', codigoPostal);

        if (!selectColonias) {
            console.warn('Elemento colonia no encontrado');
            return;
        }

        selectColonias.innerHTML = '<option value="">Cargando colonias...</option>';
        statusDiv.textContent = 'Validando código postal...';
        statusDiv.className = 'form-text text-info';

        try {
            const response = await fetch('core/obtener-colonias-cp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    codigo_postal: codigoPostal
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success && result.data && result.data.colonias) {
                selectColonias.innerHTML = '<option value="">Selecciona una colonia</option>';

                result.data.colonias.forEach(colonia => {
                    const option = document.createElement('option');
                    option.value = colonia.d_asenta;
                    option.textContent = `${colonia.d_asenta} (${colonia.tipo_asenta})`;
                    selectColonias.appendChild(option);
                });

                if (municipioInput && result.data.municipio) {
                    municipioInput.value = result.data.municipio;
                }
                if (estadoInput && result.data.estado) {
                    estadoInput.value = result.data.estado;
                }

                statusDiv.textContent = 'Código postal válido';
                statusDiv.className = 'form-text text-success';

                console.log('Datos de ubicación cargados:', result.data);

            } else {
                limpiarDatosCP();
                statusDiv.textContent = result.message || 'Código postal no encontrado';
                statusDiv.className = 'form-text text-danger';

                Swal.fire({
                    icon: 'warning',
                    title: 'Código postal no encontrado',
                    text: 'Verifica que el código postal sea correcto',
                    timer: 1000,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            console.error('Error al obtener colonias para CP:', codigoPostal, error);
            limpiarDatosCP();
            statusDiv.textContent = 'Error al validar código postal';
            statusDiv.className = 'form-text text-danger';

            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo validar el código postal',
                timer: 3000,
                showConfirmButton: false
            });
        }
    }

    function limpiarDatosCP() {
        const selectColonias = document.getElementById('colonia');
        const municipioInput = document.getElementById('municipio');
        const estadoInput = document.getElementById('estado');
        const infoUbicacion = document.getElementById('infoUbicacion');

        if (selectColonias) {
            selectColonias.innerHTML = '<option value="">Ingresa primero el código postal</option>';
        }
        if (municipioInput) {
            municipioInput.value = '';
        }
        if (estadoInput) {
            estadoInput.value = '';
        }
        if (infoUbicacion) {
            infoUbicacion.style.display = 'none';
        }
    }

    function setupEventListeners() {
        const codigoPostalInput = document.getElementById('codigoPostal');
        if (codigoPostalInput) {
            codigoPostalInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').substring(0, 5);

                if (this.value.length === 5) {
                    cargarDatosCP(this.value);
                } else {
                    limpiarDatosCP();
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        cargarRegimenesFiscales();

        setupEventListeners();
        setupLogoHandler();
        setupPasswordToggle();

        let archivosSelloDigital = {
            certificado: null,
            llave: null,
            clave: null
        };

        // Función para mostrar/ocultar contraseña
        function setupPasswordToggle() {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('clavePrivada');
            const toggleIcon = document.getElementById('toggleIcon');

            if (toggleBtn && passwordInput && toggleIcon) {
                toggleBtn.addEventListener('click', function() {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        toggleIcon.className = 'bi bi-eye-slash';
                    } else {
                        passwordInput.type = 'password';
                        toggleIcon.className = 'bi bi-eye';
                    }
                });
            }
        }

        document.getElementById('archivoCerNuevo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.name.toLowerCase().endsWith('.cer')) {
                    archivosSelloDigital.certificado = file;
                    actualizarListaArchivos();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo no válido',
                        text: 'Solo se permiten archivos .cer para el certificado'
                    });
                    e.target.value = '';
                }
            }
        });

        document.getElementById('archivoKeyNuevo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.name.toLowerCase().endsWith('.key')) {
                    archivosSelloDigital.llave = file;
                    actualizarListaArchivos();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo no válido',
                        text: 'Solo se permiten archivos .key para la llave privada'
                    });
                    e.target.value = '';
                }
            }
        });

        function actualizarListaArchivos() {
            const panel = document.getElementById('archivosSeleccionados');
            const lista = document.getElementById('listaArchivos');
            
            let html = '';
            
            if (archivosSelloDigital.certificado) {
                html += `
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-file-earmark-text text-primary me-3"></i>
                        <div>
                            <strong>Certificado:</strong> ${archivosSelloDigital.certificado.name}
                            <small class="text-muted d-block">${(archivosSelloDigital.certificado.size / 1024).toFixed(2)} KB</small>
                        </div>
                    </div>
                `;
            }
            
            if (archivosSelloDigital.llave) {
                html += `
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-key text-warning me-3"></i>
                        <div>
                            <strong>Llave Privada:</strong> ${archivosSelloDigital.llave.name}
                            <small class="text-muted d-block">${(archivosSelloDigital.llave.size / 1024).toFixed(2)} KB</small>
                        </div>
                    </div>
                `;
            }
            
            if (html) {
                lista.innerHTML = html;
                panel.style.display = 'block';
            } else {
                panel.style.display = 'none';
            }
        }

        document.getElementById('btnGuardarSello').addEventListener('click', function() {
            const claveInput = document.getElementById('clavePrivada');
            const clave = claveInput.value.trim();

            if (!archivosSelloDigital.certificado || !archivosSelloDigital.llave) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Archivos faltantes',
                    text: 'Debes seleccionar tanto el certificado (.cer) como la llave privada (.key)'
                });
                return;
            }

            if (!clave) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Contraseña requerida',
                    text: 'Debes ingresar la contraseña de la llave privada'
                });
                claveInput.focus();
                return;
            }

            archivosSelloDigital.clave = clave;

            const formData = new FormData();
            formData.append('certificado', archivosSelloDigital.certificado);
            formData.append('llave_privada', archivosSelloDigital.llave);
            formData.append('clave_privada', archivosSelloDigital.clave);
            
            const idSucursal = document.getElementById('idSucursal');
            if (idSucursal && idSucursal.value) {
                formData.append('id_empresa', idSucursal.value);
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Sello Digital Configurado',
                    text: 'Los archivos del sello digital se guardarán después de crear la sucursal'
                });
                
                window.archivosSelloTemporal = archivosSelloDigital;
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('subirSelloModal'));
                modal.hide();
                return;
            }

            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Guardando...';

            fetch('core/subir-sello-digital.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sello Digital Configurado',
                        text: result.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Cerrar el modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('subirSelloModal'));
                        modal.hide();
                        
                        // Limpiar formulario
                        document.getElementById('archivoCerNuevo').value = '';
                        document.getElementById('archivoKeyNuevo').value = '';
                        document.getElementById('clavePrivadaSello').value = '';
                        archivosSelloDigital = { certificado: null, llave: null, clave: null };
                        actualizarListaArchivos();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar',
                        text: result.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor'
                });
            })
            .finally(() => {
                // Restaurar botón
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });

        document.getElementById('btnCrearSucursal').addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            try {
                console.log('Iniciando validación de campos...');
                
                const requiredFields = [
                    'razonSocial', 'codigoSucursal', 'rfcSucursal', 'estadoSucursal', 'regimenFiscal',
                    'direccionSucursal', 'codigoPostal', 'colonia', 'nombreComercial', 'emailSucursal'
                ];

                let allValid = true;
                requiredFields.forEach(fieldId => {
                    try {
                        const field = document.getElementById(fieldId);
                        if (!field) {
                            console.warn(`Campo ${fieldId} no encontrado`);
                            allValid = false;
                            return;
                        }
                        
                        if (!field.value || field.value.trim() === '') {
                            field.classList.add('is-invalid');
                            allValid = false;
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    } catch (error) {
                        console.error(`Error validando campo ${fieldId}:`, error);
                        allValid = false;
                    }
                });

                if (allValid) {
                    console.log('Todos los campos requeridos están completos.');
                } else {
                    console.log('Faltan campos requeridos.');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, completa todos los campos requeridos antes de continuar.',
                        showConfirmButton: true
                    });
                    return;
                }
                
            } catch (validationError) {
                console.error('Error en validación de campos:', validationError);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: 'Hubo un error al validar los campos del formulario'
                });
                return;
            }

            if (!document.getElementById('municipio').value.trim() || !document.getElementById('estado').value.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Código postal incompleto',
                    text: 'Por favor, ingresa un código postal válido para llenar automáticamente municipio y estado',
                    timer: 1000,
                    showConfirmButton: false
                });
                return;
            }

            let formData;
            try {
                formData = new FormData();
                console.log('FormData creado exitosamente');
            } catch (formError) {
                console.error('Error creando FormData:', formError);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al preparar el formulario'
                });
                return;
            }
            
            const campos = [
                { id: 'razonSocial', name: 'razon_social' },
                { id: 'codigoSucursal', name: 'codigo_sucursal' },
                { id: 'rfcSucursal', name: 'rfc_fiscal' },
                { id: 'estadoSucursal', name: 'estatus' },
                { id: 'nombreComercial', name: 'nombre_comercial' },
                { id: 'regimenFiscal', name: 'regimen_fiscal' },
                { id: 'codigoPostal', name: 'codigo_postal' },
                { id: 'direccionSucursal', name: 'direccion' },
                { id: 'colonia', name: 'colonia' },
                { id: 'emailSucursal', name: 'email' },
                { id: 'clavePrivadaSello', name: 'clave_privada' }
            ];
            
            for (const campo of campos) {
                try {
                    const elemento = document.getElementById(campo.id);
                    if (elemento && elemento.value !== undefined) {
                        formData.append(campo.name, elemento.value.trim());
                        console.log(`Campo ${campo.name} agregado:`, elemento.value.trim());
                    } else {
                        console.warn(`Elemento ${campo.id} no encontrado o sin valor`);
                        formData.append(campo.name, '');
                    }
                } catch (error) {
                    console.error(`Error procesando campo ${campo.id}:`, error);
                    formData.append(campo.name, '');
                }
            }

            // agregar logo 
            const logoElement = document.getElementById('logoSucursal');
            if (logoElement && logoElement.files && logoElement.files[0]) {
                formData.append('logoSucursal', logoElement.files[0]);
            }

            fetch('core/registro-sucursal.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(result => {
                    if (result.success) {
                        if (window.archivosSelloTemporal && window.archivosSelloTemporal.certificado && window.archivosSelloTemporal.llave && window.archivosSelloTemporal.clave) {
                            subirSelloTemporal(result.id_empresa);
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucursal creada',
                                text: result.message || 'La sucursal se ha creado correctamente.',
                                timer: 1000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = 'panel?pg=gestion-sucursales';
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al crear sucursal',
                            text: result.message || 'Ocurrió un error al crear la sucursal.',
                            showConfirmButton: true
                        });
                    }
                }).catch(error => {
                    console.error('Error en la solicitud:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor.',
                        showConfirmButton: true
                    });
                });

        // función para subir sello digital
        function subirSelloTemporal(idEmpresa) {
            const formData = new FormData();
            formData.append('certificado', window.archivosSelloTemporal.certificado);
            formData.append('llave_privada', window.archivosSelloTemporal.llave);
            formData.append('clave_privada', window.archivosSelloTemporal.clave);
            formData.append('id_empresa', idEmpresa);

            fetch('core/subir-sello-digital.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                // Limpiar archivos temporales
                delete window.archivosSelloTemporal;
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucursal y Sello Digital creados',
                        text: 'La sucursal se ha creado correctamente con su sello digital.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'panel?pg=gestion-sucursales';
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sucursal creada, error en sello digital',
                        text: 'La sucursal se creó correctamente, pero hubo un error al subir el sello digital: ' + result.message,
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = 'panel?pg=gestion-sucursales';
                    });
                }
            })
            .catch(error => {
                console.error('Error al subir sello:', error);
                Swal.fire({
                    icon: 'warning',
                    title: 'Sucursal creada, error en sello digital',
                    text: 'La sucursal se creó correctamente, pero hubo un error de conexión al subir el sello digital.',
                    showConfirmButton: true
                }).then(() => {
                    window.location.href = 'panel?pg=gestion-sucursales';
                });
            });
        }

        })

        function setupLogoHandler() {
            const logoInput = document.getElementById('logoSucursal');
            const btnSeleccionar = document.getElementById('btnSeleccionarLogo');
            const btnEliminar = document.getElementById('btnEliminarLogo');
            const logoPreview = document.getElementById('logoPreview');

            if (!logoInput || !btnSeleccionar || !btnEliminar || !logoPreview) {
                return;
            }

            btnSeleccionar.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                logoInput.click();
            });

            logoInput.addEventListener('change', function(e) {
                e.stopPropagation();
                const file = e.target.files[0];
                if (file) {
                    // validar tipo de archivo
                    const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml'];
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Archivo no válido',
                            text: 'Solo se permiten archivos PNG, JPG, JPEG o SVG'
                        });
                        e.target.value = '';
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Archivo muy grande',
                            text: 'El archivo no puede ser mayor a 2MB'
                        });
                        e.target.value = '';
                        return;
                    }

                    // Mostrar vista previa
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        logoPreview.innerHTML = `<img src="${event.target.result}" alt="Logo preview" />`;
                        btnEliminar.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });

            btnEliminar.addEventListener('click', function() {
                Swal.fire({
                    title: '¿Remover imagen?',
                    text: 'Se quitará la imagen seleccionada',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, remover',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        logoInput.value = '';
                        logoPreview.innerHTML = `
                            <div class="logo-placeholder">
                                <i class="bi bi-image display-5 text-muted"></i>
                                <p class="text-muted mb-0">Vista previa del logo</p>
                            </div>
                        `;
                        btnEliminar.classList.add('d-none');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Imagen removida',
                            text: 'La imagen ha sido removida de la vista previa',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });
        }
        setupLogoHandler();

    });
</script>