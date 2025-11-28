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
                                        Razón Social
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
                                        <option value="1" selected>Activa</option>
                                        <option value="0">Inactiva</option>
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
                                    <!-- Dirección -->
                                    <div class="col-md-12">
                                        <label for="direccionSucursal" class="form-label fw-semibold">
                                            Dirección
                                        </label>
                                        <input type="text" class="form-control form-control-lg rounded-3"
                                            id="direccionSucursal" placeholder="Av. Reforma #123" required>
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
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1 text-primary">
                            <i class="bi bi-shield-check me-2"></i>Documentos Fiscales
                        </h5>
                        <p class="text-muted small mb-0">
                            Administra los documentos digitales de la sucursal
                        </p>
                    </div>
                </div>
                <div class="row g-4">
                    <!-- Sello Digital (CSD) -->
                    <div class="col-lg-12">
                        <div class="card border-2 border-primary border-dashed rounded-4 h-100">
                            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                                <div class="mb-3">
                                    <i class="bi bi-shield-lock display-1 text-primary opacity-75"></i>
                                </div>
                                <h6 class="text-primary fw-bold mb-2">Sello Digital (CSD)</h6>
                                <p class="text-muted small mb-3">Certificado (.cer) y llave (.key) para facturación</p>
                                <div class="sello-status mb-3" id="selloStatus">
                                    <span class="badge bg-secondary">No configurado</span>
                                </div>
                                <button type="button" class="btn btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#subirSelloModal">
                                    <i class="bi bi-shield-plus me-2"></i>Configurar Sello
                                </button>
                                <small class="text-muted mt-2">Requerido para facturación electrónica</small>
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
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-success rounded-3 fw-semibold" id="btnActualizarSucursal">
                                    Actualizar Sucursal
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
                <form id="formSelloDigital">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="archivoCer" class="form-label fw-semibold">
                                Certificado (.cer)
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3"
                                id="archivoCer" accept=".cer" required>
                            <div class="form-text">Selecciona el archivo de certificado .cer</div>
                        </div>

                        <div class="col-md-6">
                            <label for="archivoKey" class="form-label fw-semibold">
                                Llave Privada (.key)
                            </label>
                            <input type="file" class="form-control form-control-lg rounded-3"
                                id="archivoKey" accept=".key" required>
                            <div class="form-text">Selecciona el archivo de llave privada .key</div>
                        </div>

                        <div class="col-12">
                            <label for="clavePrivadaEdit" class="form-label fw-semibold">
                                <i class="bi bi-key me-2"></i>Llave Privada
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg rounded-start-3"
                                    id="clavePrivadaEdit" placeholder="Ingresa la llave privada" required>
                                <button class="btn btn-outline-secondary rounded-end-3" type="button" id="togglePasswordEdit">
                                    <i class="bi bi-eye" id="toggleIconEdit"></i>
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

        document.getElementById('idSucursal').value = data.id_empresa;

        // Llenar campos del formulario
        document.getElementById('razonSocial').value = data.razon_social || '';
        document.getElementById('codigoSucursal').value = data.codigo_suc || '';
        document.getElementById('rfcSucursal').value = data.rfc || '';
        document.getElementById('estadoSucursal').value = data.estatus == 1 ? '1' : '0';
        document.getElementById('nombreComercial').value = data.nombre || '';
        document.getElementById('regimenFiscal').value = data.reg_fiscal || '';
        document.getElementById('emailSucursal').value = data.correo || '';
        document.getElementById('direccionSucursal').value = data.direccion || '';
        document.getElementById('codigoPostal').value = data.cp || '';

        // Cargar logo si existe
        if (data.logo) {
            mostrarLogoExistente(data.logo);
        }

        // Actualizar estado del sello digital y cargar datos existentes
        if (data.file_cer && data.file_key) {
            actualizarEstadoSello(true);
            cargarDatosSelloExistente(data);
        } else {
            actualizarEstadoSello(false);
        }

        // Si hay código postal, cargar datos automáticos
        if (data.cp) {
            cargarDatosCP(data.cp).then(() => {
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

    // Función para mostrar logo existente
    function mostrarLogoExistente(logoPath) {
        const logoPreview = document.getElementById('logoPreview');
        const btnEliminar = document.getElementById('btnEliminarLogo');

        logoPreview.innerHTML = `<img src="uploads/logos/${logoPath}" alt="Logo de la sucursal" />`;
        btnEliminar.classList.remove('d-none');
    }

    function actualizarEstadoSello(tieneConfigurado) {
        const selloStatus = document.getElementById('selloStatus');
        if (tieneConfigurado) {
            selloStatus.innerHTML = '<span class="badge bg-success">Configurado</span>';
        } else {
            selloStatus.innerHTML = '<span class="badge bg-secondary">No configurado</span>';
        }
    }

    function cargarDatosSelloExistente(data) {
        if (data.file_cer && data.file_key && data.clave) {
            window.selloExistente = {
                certificado: data.file_cer,
                llave: data.file_key,
                tieneClave: true
            };

            console.log('Sello digital existente cargado:', {
                certificado: data.file_cer,
                llave: data.file_key,
                clave_configurada: true
            });
        }
    }

    async function obtenerClaveDescifrada(idEmpresa) {
        try {
            const response = await fetch(`core/obtener-clave-sello.php?id_empresa=${idEmpresa}`);
            const result = await response.json();

            if (result.success && result.clave_descifrada) {
                return result.clave_descifrada;
            } else {
                throw new Error(result.message || 'No se pudo obtener la clave');
            }
        } catch (error) {
            console.error('Error al obtener clave descifrada:', error);
            return null;
        }
    }

    // Función para mostrar datos del sello 
    function mostrarDatosSelloExistente() {
        if (window.selloExistente) {
            let infoSello = document.getElementById('infoSelloExistente');
            if (!infoSello) {
                infoSello = document.createElement('div');
                infoSello.id = 'infoSelloExistente';
                infoSello.className = 'col-12 mb-3';
                const modalBody = document.querySelector('#subirSelloModal .modal-body');
                const form = document.getElementById('formSelloDigital');
                if (modalBody && form) {
                    modalBody.insertBefore(infoSello, form);
                }
            }

            infoSello.innerHTML = `
                <div class="alert alert-info border-0 rounded-3">
                    <div class="d-flex">
                        <i class="bi bi-shield-check-fill me-3 fs-5 text-info"></i>
                        <div>
                            <h6 class="fw-bold mb-2">Sello Digital Configurado</h6>
                            <div class="small">
                                <div class="mb-1">
                                    <strong>Certificado:</strong> ${window.selloExistente.certificado}
                                </div>
                                <div class="mb-2">
                                    <strong>Llave Privada:</strong> ${window.selloExistente.llave}
                                </div>
                                <p class="mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Para actualizar el sello, sube nuevos archivos y proporciona la nueva contraseña.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Cargar la clave descifrada en el campo de contraseña
            const idEmpresa = document.getElementById('idSucursal').value;
            if (idEmpresa) {
                obtenerClaveDescifrada(idEmpresa).then(clave => {
                    if (clave) {
                        const claveInput = document.getElementById('clavePrivadaSelloEdit');
                        if (claveInput) {
                            claveInput.value = clave;
                            claveInput.placeholder = 'Contraseña actual (puede ser modificada)';
                        }
                    }
                });
            }
        }
    }

    function setupLogoHandler() {
        const logoInput = document.getElementById('logoSucursal');
        const btnSeleccionar = document.getElementById('btnSeleccionarLogo');
        const btnEliminar = document.getElementById('btnEliminarLogo');
        const logoPreview = document.getElementById('logoPreview');

        btnSeleccionar.addEventListener('click', function() {
            logoInput.click();
        });

        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo no válido',
                        text: 'Solo se permiten archivos PNG, JPG, JPEG o SVG'
                    });
                    return;
                }

                // Validar tamaño
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo muy grande',
                        text: 'El archivo no puede ser mayor a 2MB'
                    });
                    return;
                }

                // Mostrar vista previa
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.innerHTML = `<img src="${e.target.result}" alt="Logo preview" />`;
                    btnEliminar.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        btnEliminar.addEventListener('click', function() {
            const idEmpresa = document.getElementById('sucursal_id').value;
            
            if (!idEmpresa) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se ha seleccionado una sucursal válida'
                });
                return;
            }
            
            Swal.fire({
                title: '¿Confirmar eliminación?',
                text: 'Esta acción eliminará permanentemente el logo de la sucursal',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar indicador de carga
                    Swal.fire({
                        title: 'Eliminando logo...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Hacer petición al servidor para eliminar el logo
                    fetch('core/eliminar-logo.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `accion=eliminar_logo&id_empresa=${idEmpresa}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Limpiar la vista previa
                            logoInput.value = '';
                            logoPreview.innerHTML = `
                                <div class="logo-placeholder">
                                    <i class="bi bi-image display-4 text-muted"></i>
                                    <p class="text-muted mb-0">Vista previa del logo</p>
                                </div>
                            `;
                            btnEliminar.classList.add('d-none');
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Logo eliminado',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al eliminar',
                                text: data.message || 'No se pudo eliminar el logo'
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
                    });
                }
            });
        });
    }

    let archivosSelloDigital = {
        certificado: null,
        llave: null,
        clave: null
    };

    function setupPasswordToggleEdit() {
        const toggleBtn = document.getElementById('togglePasswordEdit');
        const passwordInput = document.getElementById('clavePrivadaEdit');
        const toggleIcon = document.getElementById('toggleIconEdit');

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

    function setupSelloHandler() {
        document.getElementById('archivoCer').addEventListener('change', function(e) {
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

        document.getElementById('archivoKey').addEventListener('change', function(e) {
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

        document.getElementById('btnGuardarSello').addEventListener('click', function() {
            const claveInput = document.getElementById('clavePrivadaEdit');
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
            formData.append('id_empresa', document.getElementById('idSucursal').value);

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
                            actualizarEstadoSello(true);

                            // Cerrar el modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('subirSelloModal'));
                            modal.hide();

                            // Limpiar formulario
                            document.getElementById('archivoCer').value = '';
                            document.getElementById('archivoKey').value = '';
                            archivosSelloDigital = {
                                certificado: null,
                                llave: null
                            };
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
    }

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

    document.addEventListener('DOMContentLoaded', async function() {
        const sucursalId = obtenerIdSucursal();
        if (!sucursalId) return;

        await cargarRegimenesFiscales();

        const datosCargados = await cargarDatosSucursal(sucursalId);
        if (!datosCargados) return;

        setupEventListeners();
        setupLogoHandler();
        setupSelloHandler();
        setupPasswordToggleEdit();

        // Configurar evento para mostrar datos del sello al abrir el modal
        const modalSello = document.getElementById('subirSelloModal');
        if (modalSello) {
            modalSello.addEventListener('show.bs.modal', function() {
                mostrarDatosSelloExistente();
            });
        }

        document.getElementById('btnActualizarSucursal').addEventListener('click', function(event) {
            event.preventDefault();

            const requiredFields = [
                'razonSocial', 'codigoSucursal', 'rfcSucursal', 'estadoSucursal', 'nombreComercial', 'regimenFiscal', 'emailSucursal',
                'direccionSucursal', 'codigoPostal', 'colonia'
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

            const formData = new FormData();
            formData.append('id_empresa', document.getElementById('idSucursal').value);
            formData.append('razon_social', document.getElementById('razonSocial').value.trim());
            formData.append('codigo_sucursal', document.getElementById('codigoSucursal').value.trim());
            formData.append('rfc_fiscal', document.getElementById('rfcSucursal').value.trim());
            formData.append('estatus', document.getElementById('estadoSucursal').value);
            formData.append('nombre_comercial', document.getElementById('nombreComercial').value.trim());
            formData.append('regimen_fiscal', document.getElementById('regimenFiscal').value);
            formData.append('email', document.getElementById('emailSucursal').value.trim());
            formData.append('codigo_postal', document.getElementById('codigoPostal').value.trim());
            formData.append('direccion', document.getElementById('direccionSucursal').value.trim());
            formData.append('colonia', document.getElementById('colonia').value);

            const logoFile = document.getElementById('logoSucursal').files[0];
            if (logoFile) {
                formData.append('logo', logoFile);
            }

            console.log('Datos a enviar para actualización');

            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Actualizando...';

            fetch('core/actualizar-sucursal.php', {
                    method: 'POST',
                    body: formData
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
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });

        })

    });
</script>