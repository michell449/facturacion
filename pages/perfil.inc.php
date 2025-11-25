<!-- Página de Perfil -->
<meta charset="UTF-8">
<div class="content-wrapper bg-light">
    <div class="container py-2">
        <!-- Header del perfil simplificado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-primary rounded-circle p-3">
                                            <i class="bi bi-person-fill text-white fs-2"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 fw-bold" id="displayName"></h4>
                                        <p class="text-muted mb-0" id="displayRFC"></p>
                                        <small class="text-muted" id="displayRegimen"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos Personales -->
        <div class="container bg-white p-4 rounded-4 border shadow-sm mb-4" id="personal" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-person-badge me-2"></i>
                    Información Fiscal
                </h5>
                <span class="status-badge status-complete" id="statusBadge">
                    <i class="bi bi-check"></i>
                </span>
            </div>
            <div class="container" id="personal" role="tabpanel">
                <form id="formDatosPersonales">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="nombreFiscal" class="form-label fw-semibold">Nombre o Razón Social
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3" id="nombreFiscal"
                                placeholder="Nombre Fiscal" readonly required>
                            <div class="form-text">Nombre completo según constancia fiscal</div>
                        </div>
                        <div class="col-md-6">
                            <label for="rfcFiscal" class="form-label fw-semibold">RFC
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3 text-uppercase" id="rfcFiscal"
                                placeholder="RFC" maxlength="13" readonly required>
                            <div class="form-text">13 caracteres para personas físicas, 12 para morales</div>
                        </div>
                        <div class="col-md-6">
                            <label for="regimenFiscal" class="form-label fw-semibold">Régimen Fiscal
                            </label>
                            <select class="form-select form-select-lg rounded-3" id="regimenFiscal" disabled required>
                                <option value="">Cargando regímenes...</option>
                            </select>
                            <div class="form-text">Selecciona el régimen que corresponde a tu situación fiscal</div>
                        </div>
                        <div class="col-md-6">
                            <label for="cpFiscal" class="form-label fw-semibold"> Código Postal
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-3" id="cpFiscal"
                                placeholder="Código Postal" maxlength="5" readonly required list="codigosPostales"
                                autocomplete="postal-code">
                            <datalist id="codigosPostales">
                            </datalist>
                            <div class="form-text" id="cpFiscalStatus">Código postal del domicilio fiscal</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dirección Fiscal -->
        <div class="container bg-white p-4 rounded-4 border shadow-sm mb-4" id="direccionFiscal" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-geo-alt-fill me-2"></i>
                    Dirección Fiscal
                </h5>
            </div>

            <form id="formDireccion">
                <div class="row g-4">
                    <div class="col-12">
                        <label for="calle" class="form-label fw-semibold">Calle
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="calle"
                            placeholder="Nombre de la calle" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label for="numeroExterior" class="form-label fw-semibold">No. Exterior
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="numeroExterior"
                            placeholder="123" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label for="numeroInterior" class="form-label fw-semibold">No. Interior
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="numeroInterior"
                            placeholder="Opcional" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="colonia" class="form-label fw-semibold">Colonia
                        </label>
                        <select class="form-select form-select-lg rounded-3" id="colonia" disabled required>
                            <option value="">Seleccionar colonia</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="municipio" class="form-label fw-semibold">Municipio/Alcaldía
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="municipio"
                            placeholder="Se llenará automáticamente" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="estado" class="form-label fw-semibold">Estado
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="estado"
                            placeholder="Se llenará automáticamente" readonly>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Botones de acción -->
    <div class="container mb-4">
        <div class="d-flex justify-content-end gap-2" id="editButtons">
            <button class="btn btn-outline-secondary btn-lg rounded-3 fw-semibold" id="btnCancel">
                <i class="bi bi-x-circle me-2"></i>
                Cancelar
            </button>
            <button class="btn btn-primary btn-lg rounded-3 fw-semibold" id="btnSave">
                <i class="bi bi-save me-2"></i>
                Guardar Cambios
            </button>
        </div>
        <div class="d-flex justify-content-start mt-3">
            <a href="panel?pg=inicio" class="btn btn-outline-primary btn-lg rounded-3 fw-semibold">
                <i class="bi bi-arrow-left-circle me-2"></i>
                Regresar
            </a>
        </div>
    </div>
</div>


<script>
    // Variables globales
    let datosOriginales = {};
    let modoEdicion = false;
    const camposDeSoloLectura = ['municipio', 'estado'];

    document.addEventListener('DOMContentLoaded', function() {
        cargarRegimenesFiscales().then(() => {
            cargarDatosUsuario();
        });

        const mainTitleContainer = document.querySelector('#personal .d-flex.justify-content-between.align-items-center.mb-4');
        if (mainTitleContainer && !document.getElementById('btnEdit')) {
            const editButtonHtml = '<button class="btn btn-outline-primary btn-lg rounded-3 fw-semibold" id="btnEdit"><i class="bi bi-pencil me-2"></i>Editar</button>';
            mainTitleContainer.insertAdjacentHTML('beforeend', editButtonHtml);
        }

        setupEventListeners();
        toggleModoEdicion();
    });

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

    async function cargarDatosUsuario() {

        try {
            const response = await fetch('core/consultar-informacion-fiscal.php');
            const result = await response.json();

            if (result.success && result.data && result.data.rfc) {
                datosOriginales = result.data;
                llenarFormulario(result.data);
                actualizarHeader(result.data);
            } else {
                document.getElementById('displayName').textContent = "Complete su información fiscal";
                document.getElementById('displayRFC').textContent = "RFC: No registrado";
                document.getElementById('displayRegimen').textContent = "Régimen fiscal: Pendiente";
                Swal.close();
            }
        } catch (error) {
            console.error('Error al cargar datos:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error al cargar datos',
                text: 'No se pudieron obtener tus datos fiscales',
                confirmButtonText: 'Entendido'
            });
        }
    }

    function llenarFormulario(data) {
        const nombreFiscal = document.getElementById('nombreFiscal');
        const rfcFiscal = document.getElementById('rfcFiscal');
        const regimenFiscal = document.getElementById('regimenFiscal');
        const cpFiscal = document.getElementById('cpFiscal');
        const calle = document.getElementById('calle');
        const numeroExterior = document.getElementById('numeroExterior');
        const numeroInterior = document.getElementById('numeroInterior');
        const municipio = document.getElementById('municipio');
        const estado = document.getElementById('estado');

        if (data.razon_social && nombreFiscal) nombreFiscal.value = data.razon_social;
        if (data.rfc && rfcFiscal) rfcFiscal.value = data.rfc;
        if (data.reg_fiscal && regimenFiscal) regimenFiscal.value = data.reg_fiscal;

        if (data.calle && calle) calle.value = data.calle;
        if (data.num_ext && numeroExterior) numeroExterior.value = data.num_ext;
        if (data.num_int && numeroInterior) numeroInterior.value = data.num_int;

        if (data.municipio && municipio) {
            municipio.value = data.municipio;
            console.log('Municipio llenado desde datos iniciales:', data.municipio);
        }
        if (data.estado && estado) {
            estado.value = data.estado;
            console.log('Estado llenado desde datos iniciales:', data.estado);
        }

        if (data.cp && cpFiscal) {
            const cpFormateado = String(data.cp).padStart(5, '0');
            cpFiscal.value = cpFormateado;
            console.log('CP original:', data.cp, 'CP formateado:', cpFormateado);

            cargarColoniasPorCP(cpFormateado, data.col).then(() => {
                console.log('Colonias cargadas y colonia seleccionada:', data.col);
            }).catch(error => {
                console.error('Error al cargar colonias:', error);
            });
        }

        console.log('Datos fiscales cargados:', data);
    }

    function actualizarHeader(data) {
        if (data.razon_social) {
            document.getElementById('displayName').textContent = data.razon_social;
        }
        if (data.rfc) {
            document.getElementById('displayRFC').textContent = `RFC: ${data.rfc}`;
        }
        if (data.reg_fiscal) {
            setTimeout(() => {
                const select = document.getElementById('regimenFiscal');
                const option = Array.from(select.options).find(opt => opt.value === data.reg_fiscal);
                if (option) {
                    document.getElementById('displayRegimen').textContent = `Régimen: ${option.text}`;
                }
            }, 100);
        }
    }

    async function cargarColoniasPorCP(codigoPostal, coloniaSeleccionada = null) {
        const selectColonias = document.getElementById('colonia');
        const statusDiv = document.getElementById('cpFiscalStatus');
        const infoUbicacion = document.getElementById('infoUbicacion');

        console.log('Cargando colonias para CP:', codigoPostal, 'Tipo:', typeof codigoPostal, 'Colonia a seleccionar:', coloniaSeleccionada);

        if (!selectColonias) {
            console.warn('Elemento colonia no encontrado');
            return;
        }

        const cpString = String(codigoPostal).trim();
        if (cpString.length !== 5) {
            console.warn('Código postal inválido, longitud:', cpString.length, 'CP:', cpString);
            selectColonias.innerHTML = '<option value="">Ingresa un código postal válido</option>';
            if (statusDiv) {
                statusDiv.textContent = 'Código postal de su domicilio fiscal';
                statusDiv.className = 'form-text';
            }
            if (infoUbicacion) {
                infoUbicacion.style.display = 'none';
            }
            return;
        }

        try {
            selectColonias.innerHTML = '<option value="">Cargando colonias...</option>';

            const response = await fetch('core/obtener-colonias-cp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    codigo_postal: cpString
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success && result.data.colonias) {
                if (selectColonias) {
                    selectColonias.innerHTML = '<option value="">Selecciona tu colonia</option>';

                    result.data.colonias.forEach(colonia => {
                        const option = document.createElement('option');
                        option.value = colonia.d_asenta;
                        option.textContent = `${colonia.d_asenta} (${colonia.tipo_asenta})`;

                        if (coloniaSeleccionada && colonia.d_asenta === coloniaSeleccionada) {
                            option.selected = true;
                            console.log('Colonia seleccionada automáticamente:', coloniaSeleccionada);
                        }

                        selectColonias.appendChild(option);
                    });
                }

                if (statusDiv) {
                    statusDiv.textContent = 'Código postal válido';
                    statusDiv.className = 'form-text text-success';
                }

                const ubicacionTexto = document.getElementById('ubicacionTexto');
                if (ubicacionTexto && infoUbicacion) {
                    ubicacionTexto.textContent = `${result.data.municipio}, ${result.data.estado}`;
                    infoUbicacion.style.display = 'block';
                }

                llenarDireccionFiscal(result.data);

                console.log('Datos de ubicación cargados:', result.data);

            } else {
                if (selectColonias) {
                    selectColonias.innerHTML = '<option value="">No se encontraron colonias</option>';
                }
                if (statusDiv) {
                    statusDiv.textContent = result.message || 'Código postal no encontrado';
                    statusDiv.className = 'form-text text-danger';
                }
                if (infoUbicacion) {
                    infoUbicacion.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Error al obtener colonias para CP:', cpString, error);
            if (selectColonias) {
                selectColonias.innerHTML = '<option value="">Error al cargar colonias</option>';
            }
            if (statusDiv) {
                statusDiv.textContent = 'Error al buscar colonias';
                statusDiv.className = 'form-text text-danger';
            }
            if (infoUbicacion) {
                infoUbicacion.style.display = 'none';
            }
        }
    }

    function llenarDireccionFiscal(data) {
        console.log('Llenando dirección fiscal con:', data);

        const municipioInput = document.getElementById('municipio');
        const estadoInput = document.getElementById('estado');

        if (data.municipio && municipioInput) {
            municipioInput.value = data.municipio;
            console.log('Municipio llenado:', data.municipio);
        } else if (!municipioInput) {
            console.warn('Campo municipio no encontrado');
        }

        if (data.estado && estadoInput) {
            estadoInput.value = data.estado;
            console.log('Estado llenado:', data.estado);
        } else if (!estadoInput) {
            console.warn('Campo estado no encontrado');
        }
    }

    async function cargarCodigosPostales(termino) {
        const datalist = document.getElementById('codigosPostales');

        if (!datalist) {
            console.warn('Datalist de códigos postales no encontrado');
            return;
        }

        if (termino.length < 2) {
            datalist.innerHTML = '';
            return;
        }

        try {
            const response = await fetch('core/obtener-codigos-postales.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    termino: termino
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success && result.data) {
                datalist.innerHTML = '';

                result.data.forEach(cp => {
                    const option = document.createElement('option');
                    option.value = cp.d_codigo;
                    option.textContent = `${cp.d_codigo} - ${cp.d_mnpio}, ${cp.d_estado}`;
                    datalist.appendChild(option);
                });
            } else {
                datalist.innerHTML = '';
            }
        } catch (error) {
            console.error('Error al cargar códigos postales:', error);
            datalist.innerHTML = '';
        }
    }



    // Función para limpiar dirección
    function limpiarDireccion() {
        const selectColonias = document.getElementById('colonia');
        const statusDiv = document.getElementById('cpFiscalStatus');
        const infoUbicacion = document.getElementById('infoUbicacion');
        const municipio = document.getElementById('municipio');
        const estado = document.getElementById('estado');

        if (selectColonias) {
            selectColonias.innerHTML = '<option value="">Ingresa primero el código postal</option>';
        }
        if (statusDiv) {
            statusDiv.textContent = 'Código postal del domicilio fiscal';
            statusDiv.className = 'form-text';
        }
        if (infoUbicacion) {
            infoUbicacion.style.display = 'none';
        }
        if (municipio) {
            municipio.value = '';
        }
        if (estado) {
            estado.value = '';
        }
    }

    function toggleModoEdicion() {
        const selects = ['regimenFiscal', 'colonia'];
        const fields = ['nombreFiscal', 'rfcFiscal', 'cpFiscal', 'calle', 'numeroExterior', 'numeroInterior'];

        const btnEdit = document.getElementById('btnEdit');
        const btnCancel = document.getElementById('btnCancel');
        const btnSave = document.getElementById('btnSave');

        if (!btnEdit) return;

        if (modoEdicion) {
            fields.forEach(id => {
                const campo = document.getElementById(id);
                if (campo) campo.removeAttribute('readonly');
            });
            selects.forEach(id => {
                const select = document.getElementById(id);
                if (select) select.removeAttribute('disabled');
            });
            camposDeSoloLectura.forEach(id => {
                const elemento = document.getElementById(id);
                if (elemento) {
                    elemento.setAttribute('readonly', true);
                }
            });


            btnEdit.style.display = 'none';
            btnCancel.style.display = 'inline-flex';
            btnSave.style.display = 'inline-flex';

        } else {
            fields.forEach(id => {
                const campo = document.getElementById(id);
                if (campo) campo.setAttribute('readonly', true);
            });
            selects.forEach(id => {
                const select = document.getElementById(id);
                if (select) select.setAttribute('disabled', true);
            });

            btnEdit.style.display = 'inline-flex';
            btnCancel.style.display = 'none';
            btnSave.style.display = 'none';
        }
    }


    function setupEventListeners() {
        const btnEdit = document.getElementById('btnEdit');
        const btnSave = document.getElementById('btnSave');
        const btnCancel = document.getElementById('btnCancel');
        const rfcFiscal = document.getElementById('rfcFiscal');
        const cpFiscal = document.getElementById('cpFiscal');

        if (btnEdit) {
            btnEdit.addEventListener('click', function() {
                modoEdicion = true;
                toggleModoEdicion();
            });
        }

        if (btnSave) {
            btnSave.addEventListener('click', function() {
                guardarCambios();
            });
        }

        if (rfcFiscal) {
            rfcFiscal.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }

        if (cpFiscal) {
            cpFiscal.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').substring(0, 5);

                if (this.value.length >= 2) {
                    cargarCodigosPostales(this.value);
                }

                if (this.value.length === 5) {
                    cargarColoniasPorCP(this.value);
                } else {
                    limpiarDireccion();
                }
            });

            if (cpFiscal.value && cpFiscal.value.length === 5) {
                console.log('Validando CP inicial:', cpFiscal.value);
                setTimeout(() => {
                    const selectColonias = document.getElementById('colonia');
                    if (selectColonias && selectColonias.options.length <= 1) {
                        console.log('Cargando colonias para CP inicial:', cpFiscal.value);
                        cargarColoniasPorCP(cpFiscal.value);
                    }
                }, 100);
            }
        }

        if (btnCancel) {
            btnCancel.addEventListener('click', function() {
                Swal.fire({
                    title: '¿Cancelar cambios?',
                    text: 'Se perderán los cambios no guardados',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Cancelar',
                    cancelButtonText: 'Continuar editando'
                }).then((result) => {
                    if (result.isConfirmed) {
                        modoEdicion = false;
                        toggleModoEdicion();
                        llenarFormulario(datosOriginales);
                    }
                });
            });
        }
    }

    // Función para validar y guardar cambios
    async function guardarCambios() {
        const requiredFields = ['nombreFiscal', 'rfcFiscal', 'cpFiscal', 'regimenFiscal', 'calle', 'numeroExterior', 'colonia'];
        let isValid = true;

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field || !field.value.trim()) {
                if (field) field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Completa todos los campos requeridos',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Confirmación antes de guardar
        Swal.fire({
            title: '¿Guardar cambios?',
            text: 'Se actualizará tu información fiscal',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                enviarDatosActualizados();
            }
        });
    }

    // Función para enviar datos actualizados
    async function enviarDatosActualizados() {
        const data = {
            nombre_fiscal: document.getElementById('nombreFiscal').value.trim(),
            rfc_fiscal: document.getElementById('rfcFiscal').value.trim(),
            cp_fiscal: document.getElementById('cpFiscal').value.trim(),
            regimen_fiscal: document.getElementById('regimenFiscal').value.trim(),
            calle: document.getElementById('calle').value.trim(),
            numero_exterior: document.getElementById('numeroExterior').value.trim(),
            numero_interior: document.getElementById('numeroInterior').value.trim(),
            colonia: document.getElementById('colonia').value.trim(),
            municipio: document.getElementById('municipio').value.trim(),
            estado: document.getElementById('estado').value.trim()
        };

        // Mostrar loading
        Swal.fire({
            title: 'Guardando cambios...',
            text: 'Por favor espera',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch('core/actualizar-info-fiscal.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                // Actualizar datos originales
                datosOriginales = {
                    razon_social: data.nombre_fiscal,
                    rfc: data.rfc_fiscal,
                    reg_fiscal: data.regimen_fiscal,
                    cp: data.cp_fiscal,
                    calle: data.calle,
                    num_ext: data.numero_exterior,
                    num_int: data.numero_interior,
                    col: data.colonia,
                    municipio: data.municipio,
                    estado: data.estado
                };

                modoEdicion = false;
                toggleModoEdicion();
                actualizarHeader(datosOriginales);

                Swal.fire({
                    icon: 'success',
                    title: '¡Información actualizada!',
                    text: 'Los cambios se guardaron correctamente',
                    timer: 1000,
                    timerProgressBar: true
                });

                // Actualizar estado
                const statusAlert = document.getElementById('statusAlert');
                if (statusAlert) {
                    statusAlert.className = 'alert alert-success border-0 mb-0';
                    statusAlert.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i><strong>Información Actualizada</strong>';
                }
            } else {
                throw new Error(result.message || 'Error al actualizar');
            }
        } catch (error) {
            console.error('Error al guardar:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                text: error.message || 'No se pudieron guardar los cambios',
                confirmButtonText: 'Entendido'
            });
        }
    }
</script>