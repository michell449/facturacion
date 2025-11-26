<!-- Crear Nueva Sucursal -->
<meta charset="UTF-8">
<div class="content-wrapper bg-light">
    <div class="container py-4">
        <!-- Título de la página -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="mb-1 fw-bold text-primary"><i class="bi bi-pencil-square text-primary fs-2"></i>Editar Sucursal</h2>
                                <p class="text-muted mb-0">Modifica la información de la sucursal seleccionada</p>
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
                <form id="formEditarSucursal">
                    <input type="hidden" id="idSucursal" value="">
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
                                        id="razonSocial" placeholder="Ej: Sucursal Centro" required>
                                    <div class="form-text">Nombre de la sucursal</div>
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
                                            id="codigoPostal" placeholder="72400" maxlength="5" required>
                                        <div class="form-text" id="cpStatus">Ingresa el código postal de 5 dígitos</div>
                                    </div>

                                    <!-- Información de ubicación -->
                                    <div class="col-12" id="infoUbicacion" style="display: none;">
                                        <div class="alert alert-info border-0 rounded-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-geo-alt-fill me-3 fs-5 text-info"></i>
                                                <div>
                                                    <strong>Ubicación:</strong>
                                                    <span id="ubicacionTexto" class="ms-2"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Colonia -->
                                    <div class="col-md-6">
                                        <label for="colonia" class="form-label fw-semibold">Colonia</label>
                                        <select class="form-select form-select-lg rounded-3" id="colonia" required>
                                            <option value="">Selecciona una colonia</option>
                                        </select>
                                    </div>

                                    <!-- Municipio/Delegación -->
                                    <div class="col-md-6">
                                        <label for="municipio" class="form-label fw-semibold">
                                            <i class="bi bi-geo-alt me-2 text-info"></i>Municipio/Delegación
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
                            <div class="col-lg-6">
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
                            </div>
                            <!-- Sello Digital (CSD) -->
                            <div class="col-lg-6">
                                <div class="card border-2 border-info border-dashed rounded-4 h-100">
                                    <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                                        <div class="mb-3">
                                            <i class="bi bi-shield-lock display-1 text-info opacity-75"></i>
                                        </div>
                                        <h6 class="text-info fw-bold mb-2">Sello Digital (CSD)</h6>
                                        <p class="text-muted small mb-3">Certificado (.cer) y llave (.key) para facturación</p>
                                        <button type="button" class="btn btn-outline-info rounded-3" data-bs-toggle="modal" data-bs-target="#subirSelloModal">
                                            <i class="bi bi-shield-plus me-2"></i>Configurar Sello
                                        </button>
                                        <small class="text-muted mt-2">Opcional - Requerido para facturar</small>
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
                                <h6 class="mb-1 fw-bold">¿Listo para actualizar la sucursal?</h6>
                                <small class="text-muted">Verifica que todos los cambios sean correctos antes de guardar</small>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 justify-content-md-end mt-3 mt-md-0">
                                    <button type="button" class="btn btn-outline-secondary rounded-3 fw-semibold"
                                        onclick="window.history.back()">
                                        <i class="bi bi-x-circle me-2"></i>Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-success rounded-3 fw-semibold" id="btnActualizarSucursal">
                                        <i class="bi bi-check-circle me-2"></i>Actualizar Sucursal
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
<div class="modal fade" id="subirConstanciaModal" tabindex="-1" aria-labelledby="subirConstanciaModalLabel">
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
</div>

<!-- Modal para subir Sello Digital -->
<div class="modal fade" id="subirSelloModal" tabindex="-1" aria-labelledby="subirSelloModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 bg-info text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="subirSelloModalLabel">
                    <i class="bi bi-shield-lock me-2"></i>Configurar Sello Digital (CSD)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formSelloNuevo">
                    <div class="alert alert-warning border-0 rounded-3 mb-4">
                        <div class="d-flex">
                            <i class="bi bi-shield-exclamation-fill me-3 fs-5 text-warning"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Información de Seguridad</h6>
                                <ul class="small mb-0 ps-3">
                                    <li>Los archivos se almacenan encriptados</li>
                                    <li>La contraseña se valida pero no se guarda</li>
                                    <li>Se verifica la correspondencia certificado-llave</li>
                                    <li>Solo se aceptan archivos .cer y .key válidos</li>
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
            <div class="modal-footer border-0 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary rounded-3 fw-semibold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-info text-white rounded-3 fw-semibold" id="btnValidarNuevo">
                    <i class="bi bi-shield-check me-2"></i>Validar Sello
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let sucursalData = null;

    function obtenerIdSucursal() {
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        if (!id) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se proporcionó el ID de la sucursal',
                showConfirmButton: true
            }).then(() => {
                window.location.href = 'panel?pg=gestion-sucursales';
            });
            return null;
        }
        return id;
    }

    // Cargar datos de la sucursal
    async function cargarDatosSucursal(id) {
        try {
            const response = await fetch(`core/obtener-sucursal.php?id=${id}`);
            const result = await response.json();

            if (result.success && result.data) {
                sucursalData = result.data;
                llenarFormulario(result.data);
                return true;
            } else {
                throw new Error(result.message || 'Error al cargar datos de la sucursal');
            }
        } catch (error) {
            console.error('Error al cargar sucursal:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error al cargar sucursal',
                text: error.message,
                showConfirmButton: true
            }).then(() => {
                window.location.href = 'panel?pg=gestion-sucursales';
            });
            return false;
        }
    }

    // Llenar el formulario con los datos de la sucursal
    function llenarFormulario(data) {
        console.log('Datos recibidos para llenar formulario:', data);
        
        // Agregar campo oculto con ID de empresa
        document.getElementById('idSucursal').value = data.id_empresa;
        
        // Llenar campos del formulario
        document.getElementById('razonSocial').value = data.razon_social || '';
        document.getElementById('codigoSucursal').value = data.codigo_suc || '';
        document.getElementById('rfcSucursal').value = data.rfc || '';
        document.getElementById('estadoSucursal').value = data.estatus == 1 ? 'activa' : 'inactiva';
        document.getElementById('regimenFiscal').value = data.reg_fiscal || '';
        document.getElementById('calleSucursal').value = data.calle || '';
        document.getElementById('codigoPostal').value = data.cp || '';
        document.getElementById('numExterior').value = data.num_ext || '';
        document.getElementById('numInterior').value = data.num_int || '';
        
        // Si hay código postal, cargar datos automáticos
        if (data.cp) {
            cargarDatosCP(data.cp).then(() => {
                // Después de cargar las colonias, seleccionar la correcta
                setTimeout(() => {
                    if (data.colonia) {
                        document.getElementById('colonia').value = data.colonia;
                    }
                }, 500);
            });
        }
    }

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

                // Si ya tenemos datos de sucursal, seleccionar el régimen correcto
                if (sucursalData && sucursalData.reg_fiscal) {
                    select.value = sucursalData.reg_fiscal;
                }
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

    document.addEventListener('DOMContentLoaded', async function() {
        // Obtener ID de la sucursal de la URL
        const sucursalId = obtenerIdSucursal();
        if (!sucursalId) return;

        // Cargar regímenes fiscales primero
        await cargarRegimenesFiscales();

        // Luego cargar los datos de la sucursal
        const datosCargados = await cargarDatosSucursal(sucursalId);
        if (!datosCargados) return;

        // Configurar event listeners
        setupEventListeners();

        let selloValidado = false;

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

        document.getElementById('btnActualizarSucursal').addEventListener('click', function(event) {
            event.preventDefault();

            const requiredFields = [
                'razonSocial', 'codigoSucursal', 'rfcSucursal', 'estadoSucursal', 'regimenFiscal',
                'calleSucursal', 'codigoPostal', 'colonia', 'numExterior'
            ];

            let allValid = true;
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field && (!field.value || field.value.trim() === '')) {
                    field.classList.add('is-invalid');
                    allValid = false;
                } else if (field) {
                    field.classList.remove('is-invalid');
                }
            });

            if (!allValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor, completa todos los campos requeridos antes de continuar.',
                    showConfirmButton: true
                });
                return;
            }

            // Validar que los campos automáticos estén llenos
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

            const data = {
                id_empresa: document.getElementById('idSucursal').value,
                razon_social: document.getElementById('razonSocial').value.trim(),
                codigo_sucursal: document.getElementById('codigoSucursal').value.trim(),
                rfc_fiscal: document.getElementById('rfcSucursal').value.trim(),
                estatus: document.getElementById('estadoSucursal').value,
                regimen_fiscal: document.getElementById('regimenFiscal').value,
                codigo_postal: document.getElementById('codigoPostal').value.trim(),
                calle: document.getElementById('calleSucursal').value.trim(),
                numero_exterior: document.getElementById('numExterior').value.trim(),
                numero_interior: document.getElementById('numInterior').value.trim() || null,
                colonia: document.getElementById('colonia').value
            };

            console.log('Datos a enviar para actualización:', data);

            // Mostrar loading
            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Actualizando...';

            fetch('core/actualizar-sucursal.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                }).then(response => response.json())
                .then(result => {
                    console.log('Respuesta del servidor:', result);
                    
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucursal actualizada',
                            text: result.message || 'La sucursal se ha actualizado correctamente.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'panel?pg=gestion-sucursales';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al actualizar sucursal',
                            text: result.message || 'Ocurrió un error al actualizar la sucursal.',
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
                }).finally(() => {
                    // Restaurar botón
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });

        })

    });
</script>