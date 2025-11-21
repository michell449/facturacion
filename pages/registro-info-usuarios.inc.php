<!-- Contenedor principal -->
<div class="content-wrapper bg-light">
    <div class="container py-5">
        <!-- Título de la página -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="bi bi-person-lines-fill me-2"></i>
                    Registro de Información Fiscal
                </h2>
                <p class="text-muted">Complete su información fiscal para generar facturas electrónicas</p>
            </div>
        </div>

        <div class="row justify-content-between">
            <!-- Formulario principal -->
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header bg-primary text-white text-center rounded-top-4 py-4">
                        <h3 class="mb-0 fw-bold">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Datos Fiscales
                        </h3>
                        <p class="mb-0 mt-2 opacity-75">Información requerida para la emisión de facturas electrónicas</p>
                    </div>
                    <div class="card-body p-5">
                        <!-- Sección de carga de constancia -->
                        <div class="alert alert-info border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                                <div>
                                    <h6 class="mb-1">¿Tienes tu Constancia de Situación Fiscal?</h6>
                                    <small>Puedes cargar tu constancia del SAT para llenar automáticamente algunos campos</small>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-light border-0 rounded-3 mb-4">
                            <div class="card-body text-center py-4">
                                <i class="bi bi-cloud-upload text-primary mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-primary mb-3">Cargar Constancia de Situación Fiscal</h5>
                                <p class="text-muted mb-3">Haz clic para seleccionar</p>
                                <input type="file" class="form-control d-none" id="constanciaFiscal" accept="application/pdf">
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('constanciaFiscal').click()">
                                    <i class="bi bi-file-earmark-plus me-2"></i>Seleccionar Archivo
                                </button>
                                <div class="mt-2">
                                    <small class="text-muted">Solo archivos PDF</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Formulario de información fiscal -->
                        <form id="formInfoFiscal">
                            <div class="row g-4">
                                <!-- Información básica -->
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Información fiscal
                                    </h5>
                                </div>

                                <div class="col-md-6">
                                    <label for="nombreFiscal" class="form-label fw-semibold">
                                        Nombre o Razón Social
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" id="nombreFiscal"
                                        placeholder="Ejemplo: Juan Pérez García" required>
                                    <div class="form-text">Nombre completo</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="rfcFiscal" class="form-label fw-semibold">
                                        RFC
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3 text-uppercase" id="rfcFiscal"
                                        placeholder="Ejemplo: PEPJ800101HQ8" maxlength="13" required>
                                    <div class="form-text">13 caracteres para personas físicas, 12 para morales</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="cpFiscal" class="form-label fw-semibold">
                                        Código Postal
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" id="cpFiscal"
                                        placeholder="12345" maxlength="5" pattern="[0-9]{5}" required>
                                    <div class="form-text" id="cpFiscalStatus">Código postal de su domicilio fiscal</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="regimenFiscal" class="form-label fw-semibold">
                                        Régimen Fiscal
                                    </label>
                                    <select class="form-select form-select-lg rounded-3" id="regimenFiscal" required>
                                        <option value="">Cargando regímenes fiscales...</option>
                                    </select>
                                    <div class="form-text">Selecciona el régimen que corresponde a tu situación fiscal</div>
                                </div>


                                <div class="col-12" id="infoUbicacion" style="display: none;">
                                    <div class="alert alert-info">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt-fill me-2"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dirección fiscal obligatoria -->
                                <div class="col-12 mt-4">
                                    <h5 class="text-primary mb-3">Datos de dirección Fiscal
                                    </h5>
                                </div>

                                <div class="col-12" id="direccionFiscalSection">

                                    <div class="card bg-light border-0 rounded-3">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label for="calle" class="form-label">Calle</label>
                                                    <input type="text" class="form-control rounded-3" id="calle" placeholder="Nombre de la calle">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="numeroExterior" class="form-label">No. Exterior</label>
                                                    <input type="text" class="form-control rounded-3" id="numeroExterior" placeholder="123">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="numeroInterior" class="form-label">No. Interior</label>
                                                    <input type="text" class="form-control rounded-3" id="numeroInterior" placeholder="Opcional">

                                                </div>
                                                <div class="col-md-6">
                                                    <label for="colonia" class="form-label">Colonia</label>
                                                    <select class="form-select rounded-3" id="colonia">
                                                        <option value="">Selecciona una colonia</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="municipio" class="form-label">Municipio/Alcaldía</label>
                                                    <input type="text" class="form-control rounded-3" id="municipio" placeholder="Se llenará automáticamente">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="estado" class="form-label">Estado</label>
                                                    <input type="text" class="form-control rounded-3" id="estado" placeholder="Se llenará automáticamente">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-flex justify-content-between mt-5">
                                <button type="button" class="btn btn-outline-secondary btn-lg rounded-3" onclick="window.history.back()">
                                    <i class="bi bi-arrow-left me-2"></i>Regresar
                                </button>
                                <button type="submit" id="btnGuardarInformacion" class="btn btn-primary btn-lg rounded-3 px-5">
                                    <i class="bi bi-check-circle me-2"></i>Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        // Función para cargar regímenes fiscales
        async function cargarRegimenesFiscales() {
            try {
                const response = await fetch('core/listar-regimen-fiscal.php');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
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
                    const select = document.getElementById('regimenFiscal');
                    select.innerHTML = '<option value="">Error al cargar regímenes</option>';
                }
            } catch (error) {
                console.error('Error al obtener regímenes fiscales:', error);
                const select = document.getElementById('regimenFiscal');
                select.innerHTML = '<option value="">Error al cargar regímenes</option>';
            }
        }

        function verificarElementosFormulario() {
            const elementos = [
                'nombreFiscal', 'rfcFiscal', 'cpFiscal', 'regimenFiscal',
                'colonia', 'municipio', 'estado', 'calle', 'numeroExterior'
            ];

            elementos.forEach(id => {
                const elemento = document.getElementById(id);
                if (elemento) {
                    console.log(`Elemento '${id}' disponible`);
                } else {
                    console.warn(`Elemento '${id}' NO encontrado`);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            cargarRegimenesFiscales();
        });

        // funcion para llenar los campos de dirección 
        function llenarDireccionFiscal(data) {
            if (data.colonias && data.colonias.length > 0) {
                const coloniaSelect = document.getElementById('colonia');
                if (coloniaSelect) {
                    coloniaSelect.innerHTML = '<option value="">Selecciona una colonia</option>';

                    data.colonias.forEach(colonia => {
                        const option = document.createElement('option');
                        option.value = colonia.d_asenta;
                        option.textContent = `${colonia.d_asenta} (${colonia.tipo_asenta})`;
                        coloniaSelect.appendChild(option);
                    });
                }
            }

            if (data.municipio) {
                const municipioInput = document.getElementById('municipio');
                if (municipioInput) {
                    municipioInput.value = data.municipio;
                }
            }

            if (data.estado) {
                const estadoInput = document.getElementById('estado');
                if (estadoInput) {
                    estadoInput.value = data.estado;
                }
            }
        }

        document.getElementById('rfcFiscal').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        async function cargarColoniasPorCP(codigoPostal) {
            const selectColonias = document.getElementById('colonia');
            const statusDiv = document.getElementById('cpFiscalStatus');
            const infoUbicacion = document.getElementById('infoUbicacion');

            if (!selectColonias) {
                console.warn('Colonia no disponible para cargar colonias');
                return;
            }

            if (!infoUbicacion) {
                console.warn('InfoUbicacion no disponible');
            }

            if (codigoPostal.length !== 5) {
                selectColonias.innerHTML = '<option value="">Ingresa un código postal válido</option>';
                statusDiv.textContent = 'Código postal de su domicilio fiscal';
                statusDiv.className = 'form-text';
                infoUbicacion.style.display = 'none';
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
                        codigo_postal: codigoPostal
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
                            selectColonias.appendChild(option);
                        });
                    }
                }
                if (statusDiv) {


                    const ubicacionTexto = document.getElementById('ubicacionTexto');
                    if (ubicacionTexto && infoUbicacion) {
                        ubicacionTexto.textContent = `${result.data.municipio}, ${result.data.estado}`;
                        infoUbicacion.style.display = 'block';
                    }

                    llenarDireccionFiscal(result.data);

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
                console.error('Error al obtener colonias:', error);
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

        document.getElementById('cpFiscal').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 5);

            // cargart las colonias 
            if (this.value.length === 5) {
                cargarColoniasPorCP(this.value);
            } else {
                const selectColonias = document.getElementById('colonia');
                const statusDiv = document.getElementById('cpFiscalStatus');
                const infoUbicacion = document.getElementById('infoUbicacion');

                if (selectColonias) {
                    selectColonias.innerHTML = '<option value="">Ingresa primero el código postal</option>';
                }
                if (statusDiv) {
                    statusDiv.textContent = 'Código postal de su domicilio fiscal';
                    statusDiv.className = 'form-text';
                }
                if (infoUbicacion) {
                    infoUbicacion.style.display = 'none';
                }
            }
        });

        document.getElementById('formInfoFiscal').addEventListener('submit', function(e) {
            e.preventDefault();

            const requiredFields = ['nombreFiscal', 'rfcFiscal', 'cpFiscal', 'regimenFiscal', 'calle', 'numeroExterior', 'colonia', 'municipio', 'estado'];
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

            if (isValid) {
                alert('Información fiscal guardada correctamente.');
            } else {
                alert('Por favor completa todos los campos requeridos.');
            }
        });

        document.getElementById('constanciaFiscal').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) {
                return;
            }

            if (file.type !== "application/pdf") {
                alert('Por favor, selecciona un archivo PDF.');
                this.value = '';
                return;
            }

            alert(`Procesando archivo: ${file.name}...`);

            const formData = new FormData();
            formData.append('constanciaFile', file);

            fetch('core/procesar_csf.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.debug_raw_text) {
                        console.groupCollapsed('DEBUG: Texto Completo Extraído del PDF');
                        console.log(result.debug_raw_text);
                        console.groupEnd();
                    }

                    if (result.success) {
                        rellenarFormulario(result.data);
                        console.log('Datos Rellenados:', result.data);
                    } else {
                        console.error('Error del servidor:', result.message);
                        alert(result.message);
                    }
                })
                .catch(error => {
                    console.error('Error de red o JSON inválido:', error);
                    alert('Ocurrió un error de conexión o el servidor devolvió un error inesperado.');
                });

            this.value = '';
        });

        function rellenarFormulario(data) {
            if (data.nombreFiscal) {
                document.getElementById('nombreFiscal').value = data.nombreFiscal;
            }
            if (data.rfcFiscal) {
                document.getElementById('rfcFiscal').value = data.rfcFiscal;
            }
            if (data.cpFiscal) {
                document.getElementById('cpFiscal').value = data.cpFiscal;

                if (data.ciudadFiscal || data.estadoFiscal || data.municipioFiscal) {
                    llenarDireccionDirecta(data);
                }

                setTimeout(() => {
                    cargarColoniasPorCP(data.cpFiscal);
                }, 100);
            }

            const regimenSelect = document.getElementById('regimenFiscal');
            if (regimenSelect && data.regimenFiscalCodigo) {
                regimenSelect.value = data.regimenFiscalCodigo;
                console.log('Régimen Fiscal rellenado con código:', data.regimenFiscalCodigo);
            } else if (data.regimenFiscal) {
                console.warn('Régimen Fiscal extraído: ' + data.regimenFiscal + '. No se encontró código de régimen en la BD para auto-selección.');
            }
        }

        function llenarDireccionDirecta(data) {
            if (data.municipioFiscal) {
                const municipioInput = document.getElementById('municipio');
                if (municipioInput) {
                    municipioInput.value = data.municipioFiscal;
                }
            }

            if (data.estadoFiscal) {
                const estadoInput = document.getElementById('estado');
                if (estadoInput) {
                    estadoInput.value = data.estadoFiscal;
                }
            }
        }

        function llenarColoniasDirecta(colonias) {
            const coloniaSelect = document.getElementById('colonia');
            if (coloniaSelect && colonias && colonias.length > 0) {
                coloniaSelect.innerHTML = '<option value="">Selecciona una colonia</option>';

                colonias.forEach(colonia => {
                    const option = document.createElement('option');
                    option.value = colonia.d_asenta;
                    option.textContent = `${colonia.d_asenta} (${colonia.tipo_asenta})`;
                    coloniaSelect.appendChild(option);
                });

                console.log(`Colonias de dirección cargadas: ${colonias.length}`);
            }
        }

        //registrar los campos del formulario, conectarlo con el php de registro-info-usuarios-fiscales.php
        document.getElementById('btnGuardarInformacion').addEventListener('click', function(event) {
            event.preventDefault();

            const requiredFields = ['nombreFiscal', 'rfcFiscal', 'cpFiscal', 'regimenFiscal', 'calle', 'numeroExterior', 'colonia', 'municipio', 'estado'];
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
                alert('Por favor completa todos los campos requeridos.');
                return; 
            }

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

            fetch('core/registro-info-fiscal.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Información fiscal registrada correctamente.');
                    } else {
                        alert('Error al registrar información fiscal: ' + result.message);
                    }
                })
                .catch(error => {
                    console.error('Error de red o JSON inválido:', error);
                    alert('Ocurrió un error de conexión o el servidor devolvió un error inesperado.');
                });
        });
    </script>