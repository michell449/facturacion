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
                                <div class="step-item active" id="step1">
                                    <div class="step-circle bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <h6 class="fw-bold text-primary">Buscar Ticket</h6>
                                    <small class="text-muted">Ingresa los datos de tu compra</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="step-item" id="step2">
                                    <div class="step-circle bg-light text-muted rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                        <i class="bi bi-person-lines-fill"></i>
                                    </div>
                                    <h6 class="fw-bold text-muted">Datos Fiscales</h6>
                                    <small class="text-muted">Completa tu información</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="step-item" id="step3">
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
                                    <label for="lugarCompraInput" class="form-label fw-semibold">
                                        Nombre del Negocio / Empresa
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="lugarCompraInput"
                                        placeholder="Ej: Tienda ABC, Restaurante XYZ" required>
                                    <div class="form-text">
                                        Ingresa el nombre exacto del lugar donde hiciste tu compra
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="numeroTicket" class="form-label fw-semibold">
                                        Número de Folio / Ticket
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="numeroTicket"
                                        placeholder="Ej: 00001234" required>
                                    <div class="form-text">
                                        Encuentra este número en tu ticket de compra
                                    </div>
                                </div>

                                <!-- Monto Total -->
                                <div class="col-12">
                                    <label for="montoTicket" class="form-label fw-semibold">
                                        <i class="bi bi-currency-dollar me-1"></i>Monto Total (Importe)
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control"
                                            id="montoTicket"
                                            placeholder="0.00"
                                            step="0.01"
                                            min="0"
                                            required>
                                    </div>
                                    <div class="form-text">
                                        Ingresa el importe total de tu compra
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="button" class="btn btn-primary btn-lg py-3 fw-semibold w-100" id="btnBuscarTicket">
                                        <i class="bi bi-search me-2"></i>
                                        Buscar Ticket
                                    </button>
                                </div>

                                <div class="col-12" id="resultadoBusqueda" style="display: none;">
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <div id="resultadoContent"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información Fiscal -->
            <div class="col-lg-6 d-none" id="infoRegistroContainer">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-info text-white py-4">
                        <div class="d-flex align-items-center">
                            <div>
                                <h4 class="text-center text-white mb-4">
                                    <i class="bi bi-person-lines-fill me-2"></i> Información Fiscal
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Resumen del Ticket -->
                        <div class="alert alert-light border-1 border-primary mb-4" id="resumenTicket">
                            <h6 class="fw-bold text-primary">
                                <i class="bi bi-receipt me-2"></i>Resumen del Ticket
                            </h6>
                            <div class="row small">
                                <div class="col-6">
                                    <strong>Folio:</strong> <span id="resumenFolio">-</span>
                                </div>
                                <div class="col-6">
                                    <strong>Fecha:</strong> <span id="resumenFecha">-</span>
                                </div>
                                <div class="col-6">
                                    <strong>Subtotal:</strong> <span id="resumenSubtotal">-</span>
                                </div>
                                <div class="col-6">
                                    <strong>Impuesto:</strong> <span id="resumenImpuesto">-</span>
                                </div>
                                <div class="col-6">
                                    <strong>Total:</strong> <span id="resumenTotal" class="fw-bold text-info">-</span>
                                </div>
                            </div>
                        </div>

                        <form id="formInfoFiscal">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="correoFiscal" class="form-label fw-semibold">
                                        Correo Electrónico *
                                    </label>
                                    <input type="email" class="form-control form-control-lg" id="correoFiscal"
                                        placeholder="tu.correo@email.com" required>
                                    <div class="form-text">
                                        Aquí recibirás tu factura electrónica
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="rfcFiscal" class="form-label fw-semibold">
                                        RFC *
                                    </label>
                                    <input type="text" class="form-control form-control-lg text-uppercase" id="rfcFiscal"
                                        placeholder="PEPJ8001019Q8" maxlength="13" required>
                                    <div class="form-text">
                                        Registro Federal de Contribuyentes
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="nombreFiscal" class="form-label fw-semibold">
                                        Nombre o Razón Social *
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="nombreFiscal"
                                        placeholder="Ej. Juan Pérez o Empresa S.A. de C.V." required>
                                </div>

                                <div class="col-md-6">
                                    <label for="tipoPersona" class="form-label fw-semibold">
                                        Tipo de Persona *
                                    </label>
                                    <select class="form-select form-select-lg" id="tipoPersona" required>
                                        <option value="">Selecciona una opción</option>
                                        <option value="Fisica">Persona Física</option>
                                        <option value="Moral">Persona Moral</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="regimenFiscal" class="form-label fw-semibold">
                                        Régimen Fiscal *
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

                                <div class="col-md-6">
                                    <label for="cpFiscal" class="form-label fw-semibold">
                                        Código Postal *
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="cpFiscal"
                                        placeholder="12345" maxlength="5" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="usoCFDI" class="form-label fw-semibold">
                                        Uso CFDI
                                    </label>
                                    <select class="form-select form-select-lg" id="usoCFDI">
                                        <option value="G01">G01 - Adquisición de mercancías</option>
                                        <option value="G02">G02 - Devoluciones, descuentos o bonificaciones</option>
                                        <option value="G03">G03 - Gastos en general</option>
                                        <option value="I01">I01 - Construcciones</option>
                                        <option value="I02">I02 - Mobiliario y equipo de oficina</option>
                                        <option value="I03">I03 - Equipo de transporte</option>
                                        <option value="I04">I04 - Equipo de cómputo y accesorios</option>
                                        <option value="I05">I05 - Dados, troqueles, moldes, matrices y herramental</option>
                                        <option value="I06">I06 - Adaptaciones</option>
                                        <option value="I07">I07 - Equipo de comunicaciones telefónicas</option>
                                        <option value="I08">I08 - Equipo y material eléctrico</option>
                                        <option value="D01">D01 - Honorarios médicos</option>
                                        <option value="D02">D02 - Honorarios de medicina dental</option>
                                        <option value="D03">D03 - Gastos médicos, dentales y hospitalarios</option>
                                        <option value="D04">D04 - Gastos de funeral</option>
                                        <option value="D05">D05 - Gastos de transportación escolar gravados</option>
                                        <option value="D06">D06 - Hijos menores de edad</option>
                                        <option value="D07">D07 - Becas educacionales</option>
                                        <option value="D08">D08 - Donativos</option>
                                        <option value="D09">D09 - Pérdida o robo de bienes</option>
                                        <option value="D10">D10 - Reembolsos</option>
                                        <option value="S01">S01 - Servicios profesionales</option>
                                        <option value="S02">S02 - Servicios administrativos</option>
                                        <option value="S03">S03 - Servicios de comisión</option>
                                        <option value="S04">S04 - Servicios de comida</option>
                                        <option value="S05">S05 - Servicios de hospedaje</option>
                                        <option value="S06">S06 - Servicios de transporte, tránsito y acarreo</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <hr class="my-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="confirmaDataos" required>
                                        <label class="form-check-label" for="confirmaDataos">
                                            Confirmo que mis datos fiscales son correctos y autorizo su uso para generar la factura electrónica.
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-info btn-lg py-3 fw-semibold" id="btnGenerarFactura">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ========================================================================
    // VARIABLES GLOBALES
    // ========================================================================
    let ticketEncontrado = null;
    let idTicket = null;

    // ========================================================================
    // EVENTO: BUSCAR TICKET
    // ========================================================================
    document.getElementById('btnBuscarTicket').addEventListener('click', async function(e) {
        e.preventDefault();

        const nombreEmpresa = document.getElementById('lugarCompraInput').value.trim();
        const folio = document.getElementById('numeroTicket').value.trim();
        const monto = parseFloat(document.getElementById('montoTicket').value);

        if (!nombreEmpresa || !folio || !monto) {
            Swal.fire('Error', 'Por favor completa todos los campos.', 'error');
            return;
        }

        // Mostrar loading
        Swal.fire({
            title: 'Buscando tu ticket...',
            html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch('core/buscar-ticket-cliente.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    nombre_empresa: nombreEmpresa,
                    folio: folio,
                    monto: monto.toString()
                })
            });

            const result = await response.json();

            if (result.success && result.ticket) {
                ticketEncontrado = result.ticket;
                idTicket = result.ticket.id_ticket;

                // Llenar el resumen del ticket
                document.getElementById('resumenFolio').textContent = result.ticket.folio;
                document.getElementById('resumenFecha').textContent = new Date(result.ticket.fecha_venta).toLocaleDateString('es-MX');
                document.getElementById('resumenSubtotal').textContent = '$' + parseFloat(result.ticket.subtotal).toFixed(2);
                document.getElementById('resumenImpuesto').textContent = '$' + parseFloat(result.ticket.impuesto).toFixed(2);
                document.getElementById('resumenTotal').textContent = '$' + parseFloat(result.ticket.total).toFixed(2);

                // Mostrar el formulario de datos fiscales
                const infoContainer = document.getElementById('infoRegistroContainer');
                if (infoContainer) {
                    infoContainer.classList.remove('d-none');

                    // Actualizar steps
                    document.getElementById('step1').classList.remove('active');
                    document.getElementById('step1').querySelector('.step-circle').classList.remove('bg-primary', 'text-white');
                    document.getElementById('step1').querySelector('.step-circle').classList.add('bg-light', 'text-muted');
                    document.getElementById('step1').querySelector('h6').classList.remove('text-primary');
                    document.getElementById('step1').querySelector('h6').classList.add('text-muted');

                    document.getElementById('step2').classList.add('active');
                    document.getElementById('step2').querySelector('.step-circle').classList.remove('bg-light', 'text-muted');
                    document.getElementById('step2').querySelector('.step-circle').classList.add('bg-primary', 'text-white');
                    document.getElementById('step2').querySelector('h6').classList.remove('text-muted');
                    document.getElementById('step2').querySelector('h6').classList.add('text-primary');

                    // Scroll suave
                    infoContainer.scrollIntoView({
                        behavior: 'smooth'
                    });
                }

                Swal.close();
            } else {
                Swal.fire('Ticket no encontrado', result.message || 'Por favor verifica los datos.', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error de conexión', 'No se pudo conectar con el servidor. Intenta de nuevo.', 'error');
        }
    });

    // ========================================================================
    // EVENTO: GENERAR FACTURA
    // ========================================================================
    document.getElementById('formInfoFiscal').addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!idTicket) {
            mostrarErrorSweetAlert('Primero debes buscar un ticket válido.');
            return;
        }

        // Validar checkbox de confirmación
        if (!document.getElementById('confirmaDataos').checked) {
            mostrarErrorSweetAlert('Debes confirmar que tus datos son correctos.');
            return;
        }

        // Recolectar datos fiscales
        const datosFactura = {
            id_ticket: idTicket,
            nombre_empresa: document.getElementById('lugarCompraInput').value,
            folio_ticket: ticketEncontrado.folio,
            monto_ticket: ticketEncontrado.total,

            // Datos fiscales
            correo: document.getElementById('correoFiscal').value.trim(),
            rfc: document.getElementById('rfcFiscal').value.trim().toUpperCase(),
            razon_social: document.getElementById('nombreFiscal').value.trim(),
            tipo_persona: document.getElementById('tipoPersona').value,
            reg_fiscal: document.getElementById('regimenFiscal').value,
            cp: parseInt(document.getElementById('cpFiscal').value),
            uso_cfdi: document.getElementById('usoCFDI').value
        };

        // Mostrar progreso
        await Swal.fire({
            title: 'Procesando Factura',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch('core/facturar-invitado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datosFactura)
            });

            // Intentar parsear siempre la respuesta JSON
            let data = null;
            try {
                data = await response.json();
            } catch (e) {
                throw new Error('No se pudo interpretar la respuesta del servidor');
            }

            // Si el backend devolvió éxito
            if (response.ok && data.success) {
                Swal.close();
                mostrarModalExitoInvitado(data);
                return;
            }

            // Manejar errores devueltos por el backend (aunque status sea 400)
            Swal.close();
            const errorInfo = mapearErrorFacturaBackend(data);

            if (errorInfo.tipo === 'cliente') {
                mostrarErrorClienteSweetAlert(errorInfo);
            } else if (errorInfo.tipo === 'validacion') {
                mostrarErrorValidacionSweetAlert(errorInfo);
            } else {
                mostrarErrorGeneralSweetAlert(errorInfo);
            }

        } catch (error) {
            console.error('Error al facturar:', error);
            mostrarErrorGeneralSweetAlert({
                titulo: 'Error',
                mensaje: error.message || 'Error desconocido al generar la factura'
            });
        }
    });

    // ========================================================================
    // FUNCIONES DE MANEJO DE ERRORES
    // ========================================================================
    async function mostrarErrorSweetAlert(mensaje) {
        await Swal.fire({
            title: 'Error',
            text: mensaje,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Entendido'
        });
    }

    async function mostrarErrorClienteSweetAlert(errorInfo) {
        await Swal.fire({
            title: 'No se puede generar la factura',
            html: `
                <div class="text-start">
                    <p><strong>Motivo:</strong></p>
                    <p class="text-danger">${errorInfo.mensaje}</p>
                    <hr>
                    <p class="small text-muted mb-3">Por favor, contacta a nuestro equipo de soporte para resolver este problema.</p>
                </div>
            `,
            icon: 'warning',
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'Entendido'
        });
    }

    async function mostrarErrorValidacionSweetAlert(errorInfo) {
        await Swal.fire({
            title: 'Error de Validación',
            html: `
                <div class="text-start">
                    <p><strong>El siguiente problema impide generar la factura:</strong></p>
                    <p class="text-danger">${errorInfo.mensaje}</p>
                    <p class="small text-muted mt-3">Revisa los datos ingresados y asegúrate de que sean correctos.</p>
                </div>
            `,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Entendido'
        });
    }

    async function mostrarErrorGeneralSweetAlert(errorInfo) {
        await Swal.fire({
            title: errorInfo.titulo || 'Error al generar factura',
            html: `
                <div class="text-start">
                    <p>${errorInfo.mensaje}</p>
                    <p class="small text-muted mt-3">Si el problema persiste, contacta a soporte.</p>
                </div>
            `,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Entendido'
        });
    }

    function mapearErrorFacturaBackend(data) {
        // Si viene código específico del backend
        if (data && data.codigo_error) {
            // Régimen/uso CFDI
            if (data.codigo_error === 'CFDI40161') {
                return {
                    tipo: 'validacion',
                    titulo: 'Régimen Fiscal Inválido',
                    mensaje: data.message || 'No se puede facturar con este régimen fiscal',
                    controlableUsuario: true
                };
            }

            // Problemas de timbres / suspensión => administrador
            if (data.alcance === 'admin' || data.codigo_error === 'ADMIN_CUPO_TIMBRES') {
                return {
                    tipo: 'cliente',
                    titulo: 'Cuenta de timbrado no disponible',
                    mensaje: data.message || 'No se puede timbrar por disponibilidad de timbres o suspensión.',
                    controlableUsuario: false
                };
            }
        }

        // Si el mensaje indica factura previa activa
        if (data && data.message && data.message.toLowerCase().includes('factura activa')) {
            return {
                tipo: 'validacion',
                titulo: 'Ticket ya facturado',
                mensaje: 'Este ticket ya tiene una factura activa. Primero cancela la factura anterior para refacturar.',
                controlableUsuario: true
            };
        }

        // Error genérico
        return {
            tipo: 'general',
            titulo: 'Error al generar factura',
            mensaje: data.message || 'Ocurrió un error al procesar tu solicitud.',
            controlableUsuario: false
        };
    }

    async function mostrarModalExitoInvitado(data) {
        // Actualizar steps
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step2').querySelector('.step-circle').classList.remove('bg-primary', 'text-white');
        document.getElementById('step2').querySelector('.step-circle').classList.add('bg-light', 'text-muted');
        document.getElementById('step2').querySelector('h6').classList.remove('text-primary');
        document.getElementById('step2').querySelector('h6').classList.add('text-muted');

        document.getElementById('step3').classList.add('active');
        document.getElementById('step3').querySelector('.step-circle').classList.remove('bg-light', 'text-muted');
        document.getElementById('step3').querySelector('.step-circle').classList.add('bg-success', 'text-white');
        document.getElementById('step3').querySelector('h6').classList.remove('text-muted');
        document.getElementById('step3').querySelector('h6').classList.add('text-success');

        await Swal.fire({
            icon: 'success',
            title: '¡Factura Generada Exitosamente!',
            html: `
                <div class="alert alert-success border-0 mb-3">
                    <h5 class="mb-3"><i class="bi bi-check-circle-fill me-2"></i>¡Tu factura ha sido procesada correctamente!</h5>
                </div>
                
                <div class="alert alert-light border border-primary mb-3 p-3 rounded">
                    <div class="row text-start small">
                        <div class="col-6">
                            <strong>Folio:</strong><br>
                            <span class="text-primary fw-bold" style="font-size: 1.1em;">${data.folio ? 'A' + String(data.folio).padStart(6, '0') : 'N/A'}</span>
                        </div>
                        <div class="col-6">
                            <strong>ID Factura:</strong><br>
                            <code>${data.id_factura || 'N/A'}</code>
                        </div>
                    </div>
                    ${data.uuid ? `
                    <div class="row text-start small mt-2">
                        <div class="col-12">
                            <strong>UUID:</strong><br>
                            <code class="small">${data.uuid}</code>
                        </div>
                    </div>
                    ` : ''}
                </div>

                <div class="alert alert-info mb-3">
                    <i class="bi bi-envelope me-2"></i>
                    Se ha enviado la factura a:<br>
                    <strong>${data.correo || 'tu correo electrónico'}</strong>
                </div>

                ${!data.uuid ? `
                <div class="alert alert-warning">
                    <i class="bi bi-hourglass-split me-2"></i>
                    <strong>Timbrado en proceso:</strong> Recibirás un correo con los archivos XML y PDF timbrados en los próximos minutos.
                </div>
                ` : `
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Factura timbrada:</strong> Tu factura ha sido timbrada correctamente.
                </div>
                `}

                <p class="text-muted small mt-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Los archivos XML y PDF han sido enviados a tu correo electrónico.
                </p>
            `,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#198754'
        }).then(() => {
            // Limpiar formularios y recargar
            document.getElementById('formBuscarTicket').reset();
            document.getElementById('formInfoFiscal').reset();
            document.getElementById('infoRegistroContainer').classList.add('d-none');
            location.reload();
        });
    }

    // ========================================================================
    // VALIDACIÓN EN TIEMPO REAL
    // ========================================================================

    // Validar RFC
    document.getElementById('rfcFiscal').addEventListener('change', function() {
        const rfc = this.value.toUpperCase();
        this.value = rfc;

        if (rfc.length !== 12 && rfc.length !== 13) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Validar Código Postal
    document.getElementById('cpFiscal').addEventListener('change', function() {
        const cp = parseInt(this.value);
        if (isNaN(cp) || cp < 1000 || cp > 99999) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Validar Email
    document.getElementById('correoFiscal').addEventListener('change', function() {
        const email = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Auto-format para montos
    document.getElementById('montoTicket').addEventListener('input', function() {
        if (this.value) {
            const valor = parseFloat(this.value);
            if (!isNaN(valor)) {
                this.value = valor.toFixed(2);
            }
        }
    });
</script>