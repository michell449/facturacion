<div class="content-wrapper bg-light min-vh-100">
    <div class="bg-primary text-white py-4">
        <div class="container h-100 d-flex align-items-center">
            <div class="row w-100 align-items-center">
                <div class="col-lg-6">
                    <h1 class=" fw-bold mb-4">Facturar como invitado <i class="bi bi-receipt-cutoff m-2 opacity-75"></i> </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Progress Steps -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="step-item active">
                                    <div class="step-circle bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <h6 class="fw-bold text-primary">Buscar Ticket</h6>
                                    <small class="text-muted">Ingresa los datos de tu compra</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="step-item">
                                    <div class="step-circle bg-light text-muted rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-person-lines-fill"></i>
                                    </div>
                                    <h6 class="fw-bold text-muted">Datos Fiscales</h6>
                                    <small class="text-muted">Completa tu información</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="step-item">
                                    <div class="step-circle bg-light text-muted rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-file-earmark-check"></i>
                                    </div>
                                    <h6 class="fw-bold text-muted">Generar Factura</h6>
                                    <small class="text-muted">Descarga tu CFDI</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Buscar Ticket -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-primary text-white py-4">
                        <div class="d-flex align-items-center">
                            <h4 class="text-center text-white mb-4"> <i class="bi bi-card-heading me-2"></i>Buscar Ticket</h4>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form id="formBuscarTicket">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="numeroTicket" class="form-label fw-semibold">
                                        Número de Venta (Folio)
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="numeroTicket"
                                        placeholder="Ej: 123456789" required>
                                    <div class="form-text">
                                        Encuentra este número en tu ticket de compra
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="montoTotal" class="form-label fw-semibold">
                                        Monto Total
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="montoTotal"
                                            placeholder="0.00" step="0.01" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="fechaCompra" class="form-label fw-semibold">
                                        Fecha de Compra
                                    </label>
                                    <input type="date" class="form-control form-control-lg" id="fechaCompra" required>
                                </div>

                                <div class="col-12">
                                    <label for="lugarCompraInput" class="form-label fw-semibold">
                                        Lugar de Compra
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="lugarCompraInput"
                                        placeholder="Haz clic para seleccionar la sucursal" readonly
                                        data-bs-toggle="modal" data-bs-target="#modalSucursales"
                                        style="cursor:pointer;">
                                    <div class="form-text">
                                        Selecciona la sucursal donde realizaste tu compra
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="button" class="btn btn-primary btn-lg py-3 fw-semibold" id="btnBuscarTicket">
                                    <i class="bi bi-search me-2"></i>
                                    Buscar Ticket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información Fiscal -->
            <div class="col-lg-6 d-none" id="infoRegistroContainer">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-success text-white py-4">
                        <div class="d-flex align-items-center">
                            <div>
                                <h4 class="text-center text-white text-success mb-4">
                                    <i class="bi bi-person-lines-fill me-2"></i> Información Fiscal
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form id="formInfoFiscal">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="nombreFiscal" class="form-label fw-semibold">
                                        Nombre o Razón Social
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="nombreFiscal"
                                        placeholder="Ej. Juan Pérez o Empresa S.A. de C.V." required>
                                </div>

                                <div class="col-md-6">
                                    <label for="rfcFiscal" class="form-label fw-semibold">
                                        RFC
                                    </label>
                                    <input type="text" class="form-control form-control-lg text-uppercase" id="rfcFiscal"
                                        placeholder="PEPJ8001019Q8" maxlength="13" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="cpFiscal" class="form-label fw-semibold">
                                        Código Postal
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="cpFiscal"
                                        placeholder="12345" maxlength="5" required>
                                </div>

                                <div class="col-12">
                                    <label for="correoFiscal" class="form-label fw-semibold">
                                        Correo Electrónico
                                    </label>
                                    <input type="email" class="form-control form-control-lg" id="correoFiscal"
                                        placeholder="juan.perez@email.com" required>
                                    <div class="form-text">
                                        Aquí recibirás tu factura electrónica
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="regimenFiscal" class="form-label fw-semibold">
                                        Régimen Fiscal
                                    </label>
                                    <select class="form-select form-select-lg" id="regimenFiscal" required>
                                        <option value="">Selecciona tu régimen fiscal</option>
                                        <optgroup label="Personas Físicas">
                                            <option value="605">605 - Sueldos y Salarios</option>
                                            <option value="606">606 - Arrendamiento</option>
                                            <option value="608">608 - Demás ingresos</option>
                                            <option value="611">611 - Ingresos por Dividendos</option>
                                            <option value="612">612 - Actividades Empresariales y Profesionales</option>
                                            <option value="614">614 - Ingresos por intereses</option>
                                            <option value="616">616 - Sin obligaciones fiscales</option>
                                            <option value="621">621 - Incorporación Fiscal</option>
                                            <option value="622">622 - Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
                                            <option value="626">626 - Régimen Simplificado de Confianza</option>
                                        </optgroup>
                                        <optgroup label="Personas Morales">
                                            <option value="601">601 - General de Ley Personas Morales</option>
                                            <option value="603">603 - Personas Morales con Fines no Lucrativos</option>
                                            <option value="609">609 - Consolidación</option>
                                            <option value="620">620 - Sociedades Cooperativas de Producción</option>
                                            <option value="623">623 - Opcional para Grupos de Sociedades</option>
                                            <option value="624">624 - Coordinados</option>
                                            <option value="625">625 - Régimen de Plataformas Tecnológicas</option>
                                        </optgroup>
                                        <optgroup label="Otros">
                                            <option value="610">610 - Residentes en el Extranjero</option>
                                            <option value="615">615 - Ingresos por obtención de premios</option>
                                        </optgroup>
                                    </select>
                                </div>


                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-success btn-lg py-3 fw-semibold">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Generar Factura
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Sucursales -->
<div class="modal fade" id="modalSucursales" tabindex="-1" aria-labelledby="modalSucursalesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalSucursalesLabel">Selecciona la Sucursal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <input type="text" class="form-control mb-3" id="busquedaSucursal" placeholder="Buscar sucursal...">
                <ul class="list-group" id="listaSucursales">
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Alaska')">Alaska</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('California')">California</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Delaware')">Delaware</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Tennessee')">Tennessee</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Texas')">Texas</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Washington')">Washington</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Florida')">Florida</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Botones de acción -->
<div class="d-flex justify-content-between mt-5">
    <button type="button" class="btn btn-outline-secondary btn-lg rounded-3" onclick="window.history.back()">
        <i class="bi bi-arrow-left me-2"></i>Regresar
    </button>
</div>
</div>
</div>
</div>
</div>

<script>
    //boton para abrir registro de informacion fiscal
    document.getElementById('btnBuscarTicket').addEventListener('click', function() {
        var infoContainer = document.getElementById('infoRegistroContainer');
        if (infoContainer) {
            infoContainer.classList.remove('d-none');
            infoContainer.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
</script>
<script>
    // Filtrar sucursales
    document.getElementById('busquedaSucursal').addEventListener('input', function() {
        var filtro = this.value.toLowerCase();
        var items = document.querySelectorAll('#listaSucursales li');
        items.forEach(function(item) {
            if (item.textContent.toLowerCase().includes(filtro)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Seleccionar sucursal y cerrar modal
    function seleccionarSucursal(nombre) {
        document.getElementById('lugarCompraInput').value = nombre;
        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalSucursales'));
        modal.hide();
    }
</script>