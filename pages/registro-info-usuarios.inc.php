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
                                <p class="text-muted mb-3">Arrastra tu archivo aquí o haz clic para seleccionar</p>
                                <input type="file" class="form-control d-none" id="constanciaFiscal" accept="application/pdf,image/*">
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('constanciaFiscal').click()">
                                    <i class="bi bi-file-earmark-plus me-2"></i>Seleccionar Archivo
                                </button>
                                <div class="mt-2">
                                    <small class="text-muted">Formatos permitidos: PDF, JPG, PNG (Máx. 5MB)</small>
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
                                    <label for="correoFiscal" class="form-label fw-semibold">
                                        Correo Electrónico
                                    </label>
                                    <input type="email" class="form-control form-control-lg rounded-3" id="correoFiscal"
                                        placeholder="ejemplo@correo.com" required>
                                    <div class="form-text">Aquí recibirás tus facturas electrónicas</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="cpFiscal" class="form-label fw-semibold">
                                        Código Postal
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3" id="cpFiscal"
                                        placeholder="12345" maxlength="5" pattern="[0-9]{5}" required>
                                    <div class="form-text">Código postal de su domicilio fiscal</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="regimenFiscal" class="form-label fw-semibold">
                                        Régimen Fiscal
                                    </label>
                                    <select class="form-select form-select-lg rounded-3" id="regimenFiscal" required>
                                        <option value="">Selecciona tu régimen fiscal</option>
                                        <optgroup label="Personas Físicas">
                                            <option value="605">Sueldos y Salarios</option>
                                            <option value="612">Actividades Empresariales y Profesionales</option>
                                            <option value="606">Arrendamiento</option>
                                            <option value="608">Demás ingresos</option>
                                            <option value="611">Ingresos por Dividendos</option>
                                            <option value="614">Ingresos por intereses</option>
                                            <option value="622">Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
                                            <option value="626">Régimen Simplificado de Confianza</option>
                                        </optgroup>
                                        <optgroup label="Personas Morales">
                                            <option value="601">General de Ley Personas Morales</option>
                                            <option value="603">Personas Morales con Fines no Lucrativos</option>
                                            <option value="620">Sociedades Cooperativas de Producción</option>
                                            <option value="623">Opcional para Grupos de Sociedades</option>
                                        </optgroup>
                                        <optgroup label="Otros">
                                            <option value="616">Sin obligaciones fiscales</option>
                                            <option value="625">Régimen de Plataformas Tecnológicas</option>
                                        </optgroup>
                                    </select>
                                    <div class="form-text">Selecciona el régimen que corresponde a tu situación fiscal</div>
                                </div>

                                <!-- Dirección fiscal (opcional) -->
                                <div class="col-12 mt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agregarDireccion">
                                        <label class="form-check-label fw-semibold" for="agregarDireccion">
                                            Agregar dirección fiscal completa
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12" id="direccionFiscalSection" style="display: none;">
                                    <div class="card bg-light border-0 rounded-3">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <label for="calle" class="form-label">Calle</label>
                                                    <input type="text" class="form-control rounded-3" id="calle" placeholder="Nombre de la calle">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="numeroExterior" class="form-label">No. Exterior</label>
                                                    <input type="text" class="form-control rounded-3" id="numeroExterior" placeholder="123">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="colonia" class="form-label">Colonia</label>
                                                    <input type="text" class="form-control rounded-3" id="colonia" placeholder="Nombre de la colonia">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="municipio" class="form-label">Municipio/Alcaldía</label>
                                                    <input type="text" class="form-control rounded-3" id="municipio" placeholder="Nombre del municipio">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="estado" class="form-label">Estado</label>
                                                    <select class="form-select rounded-3" id="estado">
                                                        <option value="">Selecciona un estado</option>
                                                        <option value="Ciudad de México">Ciudad de México</option>
                                                        <option value="Estado de México">Estado de México</option>
                                                        <option value="Jalisco">Jalisco</option>
                                                        <option value="Nuevo León">Nuevo León</option>
                                                        <!-- Agregar más estados según sea necesario -->
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="pais" class="form-label">País</label>
                                                    <input type="text" class="form-control rounded-3" id="pais" value="México" readonly>
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
                                <button type="submit" class="btn btn-primary btn-lg rounded-3 px-5">
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
        // Toggle dirección fiscal
        document.getElementById('agregarDireccion').addEventListener('change', function() {
            const direccionSection = document.getElementById('direccionFiscalSection');
            direccionSection.style.display = this.checked ? 'block' : 'none';
        });

        // Formatear RFC en mayúsculas
        document.getElementById('rfcFiscal').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Validación del código postal
        document.getElementById('cpFiscal').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 5);
        });

        // Manejo del formulario
        document.getElementById('formInfoFiscal').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validar campos requeridos
            const requiredFields = ['nombreFiscal', 'rfcFiscal', 'correoFiscal', 'cpFiscal', 'regimenFiscal', 'usoCfdi'];
            let isValid = true;

            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (isValid) {
                // Aquí iría la lógica para enviar los datos
                alert('Información fiscal guardada correctamente. Redirigiendo...');
                // window.location.href = 'dashboard'; // O la siguiente página
            } else {
                alert('Por favor completa todos los campos requeridos.');
            }
        });

        // Manejo de carga de archivos
        document.getElementById('constanciaFiscal').addEventListener('change', function() {
            const fileName = this.files[0]?.name;
            if (fileName) {
                alert(`Archivo seleccionado: ${fileName}`);
                // Aquí podrías agregar lógica para procesar el archivo
            }
        });
    </script>