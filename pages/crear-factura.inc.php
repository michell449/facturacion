<style>
    .form-factura {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .concepto-row {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
        border-left: 4px solid #0d6efd;
    }

    .btn-eliminar-concepto {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        cursor: pointer;
    }
</style>

<div class="content-wrapper bg-light">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="bi bi-receipt display-6 text-primary me-2"></i>
                    Generar Factura
                </h2>
                <p class="text-muted mb-0">Complete los datos para generar la factura CFDI 4.0</p>
            </div>
            <div class="col-md-4 text-end">
                <button type="button" class="btn btn-outline-primary" onclick="window.history.back()">
                    <i class="bi bi-arrow-left me-2"></i>Regresar
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 bg-white">
                <div class="form-factura">
                    <form id="formFactura">
                        <!-- Buscar Ticket -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-primary mb-0">
                                    <i class="bi bi-ticket-perforated me-2"></i>Buscar Ticket
                                </h5>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="limpiarFormulario()">
                                    <i class="bi bi-x-circle me-1"></i>Limpiar
                                </button>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Sucursal</label>
                                    <select class="form-select" id="sucursalBusqueda">
                                        <option value="">Todas las sucursales...</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Folio Ticket</label>
                                    <input type="text" class="form-control" id="folioTicketBusqueda" placeholder="Buscar por folio...">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">&nbsp;</label>
                                    <button type="button" class="btn btn-primary w-100" onclick="buscarTickets()">
                                        <i class="bi bi-search me-1"></i>Buscar
                                    </button>
                                </div>
                            </div>

                            <!-- Resultados de búsqueda -->
                            <div id="resultadosTickets" class="mt-3" style="display:none;">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Folio</th>
                                                <th>Fecha</th>
                                                <th>Sucursal</th>
                                                <th>Importe</th>
                                                <th>Items</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaTickets"></tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Ticket seleccionado -->
                            <div id="ticketSeleccionado" class="alert alert-info mt-3" style="display:none;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <strong>Ticket cargado:</strong> <span id="infoTicket"></span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="limpiarTicket()">
                                        Cambiar ticket
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Sucursal (ahora solo lectura cuando viene de ticket) -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="bi bi-building me-2"></i>Sucursal
                            </h5>
                            <select class="form-select form-select-lg" id="sucursalSelect" required>
                                <option value="">Seleccione una sucursal...</option>
                            </select>
                            <input type="hidden" id="ticketIdActual">
                        </div>

                        <!-- Datos del Receptor -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="bi bi-person-badge me-2"></i>Datos del Receptor
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Razón Social</label>
                                    <input type="text" class="form-control" id="receptorNombre"
                                        placeholder="Nombre completo" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">RFC</label>
                                    <input type="text" class="form-control text-uppercase" id="receptorRFC"
                                        placeholder="XAXX010101000" maxlength="13" required>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Código Postal</label>
                                    <input type="text" class="form-control" id="receptorCP" list="cpSuggestions"
                                        placeholder="12345" maxlength="5" required>
                                    <!-- onblur="validarCodigoPostal()" COMENTADO TEMPORALMENTE -->
                                    <small id="cpValidationMsg" class="form-text"></small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Uso CFDI</label>
                                    <select class="form-select" id="usoCFDI" required>
                                        <option value="">Seleccione...</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Regimen Fiscal</label>
                                    <select class="form-select" id="regimenFiscal" required>
                                        <option value="">Seleccione...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Condiciones de Pago -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="bi bi-credit-card me-2"></i>Condiciones de Pago
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Forma de Pago</label>
                                    <select class="form-select" id="formaPago" required>
                                        <option value="">Seleccione...</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Método de Pago</label>
                                    <select class="form-select" id="metodoPago" required>
                                        <option value="PUE">PUE - Pago en una sola exhibición</option>
                                        <option value="PPD">PPD - Pago en parcialidades o diferido</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Conceptos -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-primary mb-0">
                                    <i class="bi bi-card-list me-2"></i>Conceptos
                                </h5>
                                <button type="button" class="btn btn-sm btn-success" onclick="agregarConcepto()">
                                    <i class="bi bi-plus-circle me-1"></i>Agregar
                                </button>
                            </div>
                            <div id="conceptosContainer"></div>
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-chat-left-text me-2"></i>Observaciones
                            </label>
                            <textarea class="form-control" id="observaciones" rows="3"
                                placeholder="Observaciones adicionales (opcional)"></textarea>
                        </div>

                        <!-- Totales -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-semibold">Subtotal:</span>
                                    <span id="subtotalDisplay">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-semibold">IVA:</span>
                                    <span id="ivaDisplay">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2">
                                    <h5 class="fw-bold mb-0">Total:</h5>
                                    <h5 class="fw-bold mb-0 text-primary" id="totalDisplay">$0.00</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-3">
                            <button type="button" class="btn btn-primary flex-fill" onclick="generarFactura()">
                                <i class="bi bi-file-earmark-check me-2"></i>Generar Factura
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // ============================================
    // MATRIZ DE COMPATIBILIDAD CFDI 4.0
    // ============================================

    /**
     * Catálogo de compatibilidad Uso CFDI con Régimen Fiscal
     * Cada uso tiene un array de regímenes permitidos
     */
    const compatibilidadUsoCFDI = {
        'G01': { // Adquisición de mercancías
            regimenes: ['601', '603', '606', '612', '620', '621', '622', '623', '624', '625', '626'],
            moral: true, // Aplica para Morales
            fisica: true, //Aplica para Físicas
            descripcion: 'Adquisición de mercancías'
        },
        'G02': { // Devoluciones, descuentos o bonificaciones
            regimenes: ['601', '603', '606', '612', '616', '620', '621', '622', '623', '624', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Devoluciones, descuentos o bonificaciones'
        },
        'G03': { // Gastos en general
            regimenes: ['601', '603', '606', '612', '620', '621', '622', '623', '624', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Gastos en general'
        },
        'I01': { // Construcciones
            regimenes: ['601', '603', '606', '612', '620', '621', '622', '623', '624', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Construcciones'
        },
        'I02': { // Mobilario y equipo de oficina por inversiones
            regimenes: ['601', '603', '606', '612', '620', '621', '622', '623', '624', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Mobilario y equipo de oficina por inversiones'
        },
        'I03': { // Equipo de transporte
            regimenes: ['601', '603', '606', '612', '620', '621', '622', '623', '624', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Equipo de transporte'
        },
        'I04': { // Equipo de computo y accesorios
            regimenes: ['601', '603', '606', '612', '620', '621', '622', '623', '624', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Equipo de computo y accesorios'
        },
        'I05': { // Dados, troqueles, moldes, matrices y herramental
            regimenes: ['601', '603', '606', '612', '620', '621', '622', '623', '624', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Dados, troqueles, moldes, matrices y herramental'
        },
        'I06': { // Comunicaciones telefónicas
            regimenes: ['601', '603', '606', '612', '620', '621', '622', '623', '624', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Comunicaciones telefónicas'
        },
        'I07': { // Comunicaciones satelitales
            regimenes: ['601', '603', '606', '612', '620', '621', '622', '623', '624', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Comunicaciones satelitales'
        },
        'I08': { // Otra maquinaria y equipo
            regimenes: ['601', '603', '606', '612', '620', '621', '622', '623', '624', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Otra maquinaria y equipo'
        },
        'D01': { // Honorarios médicos, dentales y gastos hospitalarios
            regimenes: ['605', '606', '608', '611', '612', '614', '607', '615', '625'],
            moral: false, // No aplica para Morales
            fisica: true, // Aplica para Físicas
            descripcion: 'Honorarios médicos, dentales y gastos hospitalarios'
        },
        'D02': { // Gastos médicos por incapacidad o discapacidad
            regimenes: ['605', '606', '608', '611', '612', '614', '607', '615', '625'],
            moral: false,
            fisica: true,
            descripcion: 'Gastos médicos por incapacidad o discapacidad'
        },
        'D03': { // Gastos funerales
            regimenes: ['605', '606', '608', '611', '612', '614', '607', '615', '625'],
            moral: false,
            fisica: true,
            descripcion: 'Gastos funerales'
        },
        'D04': { // Donativos
            regimenes: ['605', '606', '608', '611', '612', '614', '607', '615', '625'],
            moral: true,
            fisica: true,
            descripcion: 'Donativos'
        },
        'D05': { // Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación)
            regimenes: ['605', '606', '608', '611', '612', '614', '607', '615', '625'],
            moral: false,
            fisica: true,
            descripcion: 'Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación)'
        },
        'D06': { // Aportaciones voluntarias al SAR
            regimenes: ['605', '606', '608', '611', '612', '614', '607', '615', '625'],
            moral: false,
            fisica: true,
            descripcion: 'Aportaciones voluntarias al SAR'
        },
        'D07': { // Primas por seguros de gastos médicos
            regimenes: ['605', '606', '608', '611', '612', '614', '607', '615', '625'],
            moral: false,
            fisica: true,
            descripcion: 'Primas por seguros de gastos médicos'
        },
        'D08': { // Gastos de transportación escolar obligatoria
            regimenes: ['605', '606', '608', '611', '612', '614', '607', '615', '625'],
            moral: false,
            fisica: true,
            descripcion: 'Gastos de transportación escolar obligatoria'
        },
        'D09': { // Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones
            regimenes: ['605', '606', '608', '611', '612', '614', '607', '615', '625'],
            moral: false,
            fisica: true,
            descripcion: 'Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones'
        },
        'D10': { // Pagos por servicios educativos (colegiaturas)
            regimenes: ['605', '606', '608', '611', '612', '614', '607', '615', '625'],
            moral: false,
            fisica: true,
            descripcion: 'Pagos por servicios educativos (colegiaturas)'
        },
        'S01': { // Sin efectos fiscales
            regimenes: ['601', '603', '605', '606', '608', '610', '611', '612', '614', '616', '620', '621', '622', '623', '624', '607', '615', '625', '626'], // Régimen sin obligaciones fiscales
            moral: true,
            fisica: true,
            descripcion: 'Sin efectos fiscales (Público en General)'
        },
        'CP01': { // Por definir
            regimenes: ['601', '603', '605', '606', '608', '610', '611', '612', '614', '616', '620', '621', '622', '623', '624', '607', '615', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Pagos'
        },
        'CN01': { // Nómina
            regimenes: ['601', '603', '605', '606', '608', '610', '611', '612', '614', '616', '620', '621', '622', '623', '624', '607', '615', '625', '626'],
            moral: true,
            fisica: true,
            descripcion: 'Nómina'
        }
    };

    /**
     * Detecta si un RFC es Persona Física o Moral
     * @param {string} rfc - RFC a validar
     * @returns {string} 'fisica', 'moral' o 'generico'
     */
    function detectarTipoPersona(rfc) {
        rfc = rfc.toUpperCase().trim();

        // RFC Genérico
        if (rfc === 'XAXX010101000' || rfc === 'XEXX010101000') {
            return 'generico';
        }

        // Persona Física: 13 caracteres (4 letras + 6 dígitos + 3 homoclave)
        if (rfc.length === 13) {
            return 'fisica';
        }

        // Persona Moral: 12 caracteres (3 letras + 6 dígitos + 3 homoclave)
        if (rfc.length === 12) {
            return 'moral';
        }

        return null; // RFC inválido
    }

    /**
     * Valida compatibilidad entre Uso CFDI y Régimen Fiscal
     * @param {string} usoCfdi - Código de Uso CFDI
     * @param {string} regimenFiscal - Código de Régimen Fiscal
     * @param {string} tipoPersona - 'fisica', 'moral', 'generico'
     * @returns {object} {valido: boolean, mensaje: string}
     */
    function validarCompatibilidadUsoCfdiRegimen(usoCfdi, regimenFiscal, tipoPersona) {
        // Si no existe el uso en el catálogo, permitir (para no bloquear usos no catalogados)
        if (!compatibilidadUsoCFDI[usoCfdi]) {
            return {
                valido: true,
                mensaje: ''
            };
        }

        const catalogoUso = compatibilidadUsoCFDI[usoCfdi];

        // Validar que el régimen esté en la lista permitida
        if (!catalogoUso.regimenes.includes(regimenFiscal)) {
            return {
                valido: false,
                mensaje: `El Uso CFDI ${usoCfdi} (${catalogoUso.descripcion}) no es compatible con el Régimen ${regimenFiscal}. Regímenes permitidos: ${catalogoUso.regimenes.join(', ')}`
            };
        }

        // Validar tipo de persona si aplica
        if (tipoPersona === 'fisica' && !catalogoUso.fisica) {
            return {
                valido: false,
                mensaje: `El Uso CFDI ${usoCfdi} no aplica para Personas Físicas`
            };
        }

        if (tipoPersona === 'moral' && !catalogoUso.moral) {
            return {
                valido: false,
                mensaje: `El Uso CFDI ${usoCfdi} no aplica para Personas Morales`
            };
        }

        return {
            valido: true,
            mensaje: 'Compatibilidad correcta'
        };
    }

    async function CargarUsoCFDI() {
        try {
            const response = await fetch('core/listar-uso-cfdi.php');
            const data = await response.json();
            const usoCFDISelect = document.getElementById('usoCFDI');
            data.data.forEach(uso => {
                const option = document.createElement('option');
                option.value = uso.codigo;
                option.textContent = `${uso.codigo} - ${uso.descripcion}`;
                usoCFDISelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error al cargar Uso CFDI:', error);
        }
    }

    async function CargarFormaPago() {
        try {
            const response = await fetch('core/listar-formas-pago.php');
            const data = await response.json();
            const formaPagoSelect = document.getElementById('formaPago');
            data.data.forEach(forma => {
                const option = document.createElement('option');
                option.value = forma.clave;
                option.textContent = `${forma.clave} - ${forma.description}`;
                formaPagoSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error al cargar Forma de Pago:', error);
        }
    }

    async function cargarRegimenesFiscales() {
        try {
            const response = await fetch('core/listar-regimen-fiscal.php');
            const data = await response.json();
            const regimenFiscalSelect = document.getElementById('regimenFiscal');
            data.data.forEach(regimen => {
                const option = document.createElement('option');
                option.value = regimen.codigo;
                option.textContent = `${regimen.codigo} - ${regimen.descripcion}`;
                regimenFiscalSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error al cargar Regímenes Fiscales:', error);
        }
    }

    async function cargarSucursales() {
        try {
            const response = await fetch('core/consultar-sucursales.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();
            const select = document.getElementById('sucursalSelect');
            const selectBusqueda = document.getElementById('sucursalBusqueda');

            if (result.success && result.data) {
                // GUARDAMOS LOS DATOS EN LA VARIABLE GLOBAL
                listaSucursales = result.data;

                select.innerHTML = '<option value="">Selecciona una sucursal</option>';
                selectBusqueda.innerHTML = '<option value="">Todas las sucursales...</option>';

                result.data.forEach(sucursal => {
                    const option1 = document.createElement('option');
                    option1.value = sucursal.id_empresa;
                    option1.textContent = sucursal.nombre || sucursal.razon_social;
                    select.appendChild(option1);

                    const option2 = document.createElement('option');
                    option2.value = sucursal.id_empresa;
                    option2.textContent = sucursal.nombre || sucursal.razon_social;
                    selectBusqueda.appendChild(option2);
                });
            } else {
                select.innerHTML = '<option value="">No hay sucursales registradas</option>';
            }
        } catch (error) {
            console.error('Error al cargar sucursales:', error);
            document.getElementById('sucursalSelect').innerHTML = '<option value="">Error de conexión</option>';
        }
    }

    // ============================================
    // BÚSQUEDA Y CARGA DE TICKETS
    // ============================================

    let ticketActual = null;
    let conceptoCounter = 0;

    // Función para agregar concepto manualmente
    function agregarConcepto() {
        conceptoCounter++;
        const container = document.getElementById('conceptosContainer');

        const conceptoHtml = `
            <div class="concepto-row" id="concepto-${conceptoCounter}">
                <button type="button" class="btn btn-sm btn-danger btn-eliminar-concepto" onclick="eliminarConcepto(${conceptoCounter})">
                    <i class="bi bi-trash"></i>
                </button>
                <div class="row g-2">
                    <div class="col-md-9">
                        <label class="form-label fw-semibold">Descripción</label>
                        <input type="text" class="form-control concepto-descripcion" placeholder="Producto o servicio" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Cantidad</label>
                        <input type="number" class="form-control concepto-cantidad" value="1" min="0.01" step="0.01" onchange="calcularTotales()" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Precio Unitario</label>
                        <input type="number" class="form-control concepto-precio" value="0" min="0" step="0.01" onchange="calcularTotales()" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Clave SAT</label>
                        <input type="text" class="form-control concepto-clave" placeholder="01010101" required>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', conceptoHtml);
    }

    function eliminarConcepto(id) {
        document.getElementById(`concepto-${id}`).remove();
        calcularTotales();
    }

    function calcularTotales() {
        let subtotal = 0;

        document.querySelectorAll('.concepto-row').forEach(row => {
            const cantidad = parseFloat(row.querySelector('.concepto-cantidad').value) || 0;
            const precio = parseFloat(row.querySelector('.concepto-precio').value) || 0;
            subtotal += cantidad * precio;
        });

        const iva = subtotal * 0.16;
        const total = subtotal + iva;

        document.getElementById('subtotalDisplay').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('ivaDisplay').textContent = `$${iva.toFixed(2)}`;
        document.getElementById('totalDisplay').textContent = `$${total.toFixed(2)}`;
    }

    async function buscarTickets() {
        const sucursalId = document.getElementById('sucursalBusqueda').value;
        const folio = document.getElementById('folioTicketBusqueda').value;

        try {
            let url = 'core/consultar-tickets.php?estatus=pendiente&limite=10';
            if (sucursalId) url += `&id_empresa=${sucursalId}`;
            if (folio) url += `&folio=${folio}`;

            const response = await fetch(url);
            const result = await response.json();

            if (result.success && result.tickets.length > 0) {
                mostrarResultadosTickets(result.tickets);
            } else {
                Swal.fire('Sin resultados', 'No se encontraron tickets pendientes con esos criterios', 'info');
            }
        } catch (error) {
            console.error('Error al buscar tickets:', error);
            Swal.fire('Error', 'No se pudo realizar la búsqueda', 'error');
        }
    }

    function mostrarResultadosTickets(tickets) {
        const tbody = document.getElementById('tablaTickets');
        tbody.innerHTML = '';

        tickets.forEach(ticket => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><strong>${ticket.folio_ticket}</strong></td>
                <td>${ticket.fecha_fmt}</td>
                <td>${ticket.sucursal_fmt}</td>
                <td class="text-end">$${ticket.importe_fmt}</td>
                <td class="text-center">${ticket.items_count}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-success" onclick='cargarTicket(${JSON.stringify(ticket)})'>
                        <i class="bi bi-arrow-down-circle me-1"></i>Cargar
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('resultadosTickets').style.display = 'block';
    }

    function cargarTicket(ticket) {
        ticketActual = ticket;

        // Ocultar resultados de búsqueda
        document.getElementById('resultadosTickets').style.display = 'none';

        // Mostrar ticket seleccionado
        document.getElementById('infoTicket').textContent =
            `Folio: ${ticket.folio_ticket} | Sucursal: ${ticket.nombre_sucursal} | Importe: $${ticket.importe_fmt}`;
        document.getElementById('ticketSeleccionado').style.display = 'block';
        document.getElementById('ticketIdActual').value = ticket.id_ticket;

        // Seleccionar sucursal del ticket
        const sucursalSelect = document.getElementById('sucursalSelect');
        const sucursalId = listaSucursales.find(s => s.nombre === ticket.nombre_sucursal)?.id_empresa;
        if (sucursalId) {
            sucursalSelect.value = sucursalId;
            sucursalSelect.dispatchEvent(new Event('change'));
        }

        // Cargar conceptos del ticket
        limpiarConceptos();
        if (ticket.productos && ticket.productos.length > 0) {
            ticket.productos.forEach(producto => {
                agregarConceptoDesdeTicket(producto);
            });
        }

        // Prellenar forma de pago si existe
        if (ticket.forma_pago) {
            document.getElementById('formaPago').value = ticket.forma_pago;
        }

        calcularTotales();
    }

    function limpiarTicket() {
        ticketActual = null;
        document.getElementById('ticketSeleccionado').style.display = 'none';
        document.getElementById('ticketIdActual').value = '';
        document.getElementById('resultadosTickets').style.display = 'block';
    }

    function limpiarFormulario() {
        document.getElementById('formFactura').reset();
        document.getElementById('sucursalBusqueda').value = '';
        document.getElementById('folioTicketBusqueda').value = '';
        document.getElementById('resultadosTickets').style.display = 'none';
        limpiarTicket();
        limpiarConceptos();
        document.getElementById('subtotalDisplay').textContent = '$0.00';
        document.getElementById('ivaDisplay').textContent = '$0.00';
        document.getElementById('totalDisplay').textContent = '$0.00';
    }

    function limpiarConceptos() {
        document.getElementById('conceptosContainer').innerHTML = '';
        conceptoCounter = 0;
    }

    function agregarConceptoDesdeTicket(producto) {
        conceptoCounter++;
        const container = document.getElementById('conceptosContainer');

        const conceptoHtml = `
            <div class="concepto-row" id="concepto-${conceptoCounter}">
                <button type="button" class="btn btn-sm btn-danger btn-eliminar-concepto" onclick="eliminarConcepto(${conceptoCounter})">
                    <i class="bi bi-trash"></i>
                </button>
                <div class="row g-2">
                    <div class="col-md-9">
                        <label class="form-label fw-semibold">Descripción</label>
                        <input type="text" class="form-control concepto-descripcion" value="${producto.descr}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Cantidad</label>
                        <input type="number" class="form-control concepto-cantidad" value="${producto.cant}" min="0.01" step="0.01" onchange="calcularTotales()" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Precio Unitario</label>
                        <input type="number" class="form-control concepto-precio" value="${producto.precio_unit}" min="0" step="0.01" onchange="calcularTotales()" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Clave SAT</label>
                        <input type="text" class="form-control concepto-clave" value="${producto.id_prod_serv}" required>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', conceptoHtml);
    }

    // ============================================
    // GENERAR FACTURA
    // ============================================
    /**
     * Genera la factura en 3 pasos:
     * 1) Guarda la factura en BD
     * 2) Genera el XML (sellado con CSD)
     * 3) Timbra el XML con el PAC
     * Incluye validaciones previas de sucursal, receptor, CP, conceptos y forma/método de pago.
     */
    async function generarFactura() {
        // Validar que haya sucursal seleccionada
        const sucursalId = document.getElementById('sucursalSelect').value;
        if (!sucursalId) {
            Swal.fire('Error', 'Seleccione una sucursal', 'warning');
            return;
        }

        // Validar datos del receptor
        const receptorRFC = document.getElementById('receptorRFC').value;
        const receptorNombre = document.getElementById('receptorNombre').value;
        const receptorCP = document.getElementById('receptorCP').value;
        const usoCFDI = document.getElementById('usoCFDI').value;

        if (!receptorRFC || !receptorNombre || !receptorCP || !usoCFDI) {
            Swal.fire('Error', 'Complete todos los datos del receptor', 'warning');
            return;
        }

        // Validar formato del código postal
        /* VALIDACIÓN CP COMENTADA TEMPORALMENTE
        if (!/^\d{5}$/.test(receptorCP)) {
            Swal.fire('Error', 'El código postal debe ser exactamente 5 dígitos', 'warning');
            return;
        }
        */

        // Validar que el CP es válido antes de continuar
        /* VALIDACIÓN CP CATÁLOGO SAT COMENTADA TEMPORALMENTE
        try {
            const responseCP = await fetch('core/obtener-codigos-postales.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    termino: receptorCP,
                    validar: true
                })
            });
            const resultCP = await responseCP.json();

            if (!resultCP.valid) {
                Swal.fire('Error', `Código postal ${receptorCP} no válido. No se encontró en el catálogo del SAT`, 'warning');
                return;
            }
        } catch (error) {
            console.error('Error validando CP:', error);
            Swal.fire('Error', 'No se pudo validar el código postal', 'warning');
            return;
        }
        */

        // Validar conceptos
        const conceptos = document.querySelectorAll('.concepto-row');
        if (conceptos.length === 0) {
            Swal.fire('Error', 'Agregue al menos un concepto', 'warning');
            return;
        }

        // Validar forma de pago
        const formaPago = document.getElementById('formaPago').value;
        if (!formaPago) {
            Swal.fire('Error', 'Seleccione una forma de pago', 'warning');
            return;
        }

        const metodoPago = document.getElementById('metodoPago').value;

        // Validación Forma 99 con Método PPD
        if (formaPago === '99' && metodoPago !== 'PPD') {
            Swal.fire('Error', 'La Forma de Pago 99 solo es compatible con Método PPD', 'warning');
            return;
        }

        // Validación Método PUE no puede usar Forma 99
        if (metodoPago === 'PUE' && formaPago === '99') {
            Swal.fire('Error', 'El Método PUE no es compatible con Forma de Pago 99', 'warning');
            return;
        }

        // Validar compatibilidad Uso CFDI - Régimen Fiscal - Tipo Persona
        const regimenFiscal = document.getElementById('regimenFiscal').value;
        const tipoPersona = detectarTipoPersona(receptorRFC);

        if (!tipoPersona) {
            Swal.fire('Error', 'RFC inválido. Debe tener 12 (Moral) o 13 (Física) caracteres', 'warning');
            return;
        }

        const validacionUsoRegimen = validarCompatibilidadUsoCfdiRegimen(usoCFDI, regimenFiscal, tipoPersona);

        if (!validacionUsoRegimen.valido) {
            Swal.fire({
                icon: 'error',
                title: 'Incompatibilidad CFDI',
                html: validacionUsoRegimen.mensaje,
                confirmButtonText: 'Corregir'
            });
            return;
        }

        // Preparar datos para enviar
        const conceptosData = [];
        document.querySelectorAll('.concepto-row').forEach(row => {
            const unidadInput = row.querySelector('.concepto-unidad');
            conceptosData.push({
                descripcion: row.querySelector('.concepto-descripcion').value,
                cantidad: row.querySelector('.concepto-cantidad').value,
                precio: row.querySelector('.concepto-precio').value,
                clave: row.querySelector('.concepto-clave').value,
                unidad: unidadInput ? unidadInput.value : 'ACT'
            });
        });

        const datosFactura = {
            id_sucursal: sucursalId,
            // Si no hay ticket seleccionado enviamos 0 para evitar violar NOT NULL en BD
            id_ticket: document.getElementById('ticketIdActual').value || 0,
            receptor: {
                rfc: receptorRFC,
                nombre: receptorNombre,
                cp: receptorCP,
                regimen: document.getElementById('regimenFiscal').value,
                uso_cfdi: usoCFDI
            },
            forma_pago: formaPago,
            metodo_pago: document.getElementById('metodoPago').value,
            conceptos: conceptosData,
            observaciones: document.getElementById('observaciones').value
        };

        try {
            // mostrar loading guardar factura
            Swal.fire({
                title: 'Guardando factura...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            })

            //Guardar datos en a base de datos
            const responseBD = await fetch('core/generar-factura.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datosFactura)
            });
            const resultBD = await responseBD.json();

            if (resultBD.success) {
                // SI SE GUARDÓ EN BD, PASAMOS AL XML
                const idFacturaNueva = resultBD.id_factura; // Asegúrate que tu PHP devuelva este ID

                // ACTUALIZAR ALERT (PASO 2)
                Swal.update({
                    title: 'Generando XML...',
                    text: 'Creando estructura y aplicando sello digital...'
                });

                // 5. PETICIÓN 2: GENERAR XML
                const responseXML = await fetch('core/generar-xml.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id_factura: idFacturaNueva
                    })
                });

                let resultXML;
                try {
                    resultXML = await responseXML.json();
                } catch (jsonError) {
                    const textResponse = await responseXML.text();
                    console.error('Error parseando JSON de generar-xml.php:', jsonError);
                    console.error('Respuesta del servidor:', textResponse);
                    throw new Exception(`Error en generación de XML: Respuesta no válida del servidor. ${textResponse.substring(0, 200)}`);
                }

                if (resultXML.success) {
                    // --- NUEVO PASO: TIMBRAR ---
                    Swal.update({
                        title: 'Timbrando...',
                        text: 'Conectando con el SAT/Finkok para certificar...'
                    });

                    try {
                        const responseTimbre = await fetch('core/timbrar-xml.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id_factura: idFacturaNueva
                            })
                        });

                        let resultTimbre;
                        try {
                            resultTimbre = await responseTimbre.json();
                        } catch (jsonError) {
                            const textResponse = await responseTimbre.text();
                            console.error('Error parseando JSON de timbrar-xml.php:', jsonError);
                            console.error('Respuesta del servidor:', textResponse);
                            throw new Error(`Respuesta no válida del servidor de timbrado. ${textResponse.substring(0, 200)}`);
                        }

                        if (resultTimbre.success) {
                            // ÉXITO TOTAL (BD + XML + TIMBRE)
                            // Preparar mensaje de estado del correo
                            let emailStatusHTML = '';
                            if (resultTimbre.email) {
                                if (resultTimbre.email.attempted) {
                                    if (resultTimbre.email.sent) {
                                        emailStatusHTML = `<p class="text-success mb-0"><i class="bi bi-envelope-check me-2"></i>Factura enviada por correo electrónico</p>`;
                                    } else {
                                        emailStatusHTML = `<p class="text-warning mb-0"><i class="bi bi-envelope-exclamation me-2"></i>No se pudo enviar el correo: ${resultTimbre.email.message || 'Error desconocido'}</p>`;
                                    }
                                } else {
                                    emailStatusHTML = `<p class="text-muted mb-0"><i class="bi bi-envelope me-2"></i>No se envió correo (sin dirección registrada)</p>`;
                                }
                            }

                            Swal.fire({
                                icon: 'success',
                                title: '¡Factura Timbrada! ',
                                html: `
                                    <div class="text-start">
                                        <p><strong>Folio:</strong> ${resultBD.folio}</p>
                                        <p><strong>UUID:</strong> ${resultTimbre.uuid}</p>
                                        ${emailStatusHTML}
                                        <div class="d-grid gap-2 mt-3">
                                            <a href="${resultTimbre.xml_url || resultXML.xml_url}" target="_blank" class="btn btn-outline-primary">
                                                <i class="bi bi-filetype-xml me-2"></i>Descargar XML
                                            </a>
                                            <button type="button" class="btn btn-outline-info" onclick="enviarFacturaPorCorreo(${idFacturaNueva}, '${resultBD.folio}', '${resultTimbre.uuid}')">
                                                <i class="bi bi-envelope-paper me-2"></i>Enviar por Correo Electrónico
                                            </button>
                                        </div>
                                    </div>
                                `,
                                showConfirmButton: true,
                                confirmButtonText: '<i class="bi bi-plus-circle me-2"></i>Crear otra factura',
                                showDenyButton: true,
                                denyButtonText: '<i class="bi bi-list-ul me-2"></i>Ver facturas generadas',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Usuario eligió crear otra factura
                                    limpiarFormulario();
                                } else if (result.isDenied) {
                                    // Usuario eligió ver facturas generadas
                                    window.location.href = '?pg=facturas-generadas-admin';
                                }
                            });

                        } else {
                            // Error en timbrado (pero el XML pre-sellado sí se creó)
                            Swal.fire('Error al Timbrar', resultTimbre.message || 'Ocurrió un error al timbrar la factura', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error de conexión', 'No se pudo contactar al servidor de timbrado', 'error');
                    }

                } else {
                    // ERROR EN XML (PERO SE GUARDÓ EN BD)
                    console.error("Errores validación:", resultXML.errores_validacion);

                    let errorMsg = resultXML.message;
                    if (resultXML.errores_validacion && resultXML.errores_validacion.length > 0) {
                        errorMsg += "<br><small class='text-danger'>" + resultXML.errores_validacion.join('<br>') + "</small>";
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Guardada sin XML',
                        html: `La factura se registró con folio <b>${resultBD.folio}</b>, pero hubo un error al crear el XML:<br>${errorMsg}`,
                        confirmButtonText: 'Entendido'
                    });
                }
            } else {
                // ERROR AL GUARDAR EN BD
                Swal.fire({
                    icon: 'error',
                    title: 'Error al guardar factura',
                    text: resultBD.message || 'No se pudo guardar la factura en la base de datos.'
                });
            }

        } catch (error) {
            console.error('Error al generar factura:', error);
            Swal.fire('Error', 'No se pudo generar la factura', 'error');
        }
    }

    // Variable global para almacenar sucursales
    let listaSucursales = [];

    // ============================================
    // VALIDACIÓN DE CÓDIGO POSTAL
    // ============================================
    /**
     * Valida el código postal del receptor contra el catálogo en BD.
     * - Verifica formato (5 dígitos)
     * - Consulta core/obtener-codigos-postales.php con validar=true
     * - Actualiza UI con estados is-valid / is-invalid y mensaje de ayuda
     * 
     * COMENTADA TEMPORALMENTE PARA PERMITIR DATOS DE PRUEBA
     */
    async function validarCodigoPostal() {
        /* FUNCIÓN COMPLETA COMENTADA
        const cpInput = document.getElementById('receptorCP');
        const cp = cpInput.value.trim();
        const validationMsg = document.getElementById('cpValidationMsg');

        if (!cp) {
            validationMsg.textContent = '';
            validationMsg.className = 'form-text';
            return;
        }

        if (!/^\d{5}$/.test(cp)) {
            validationMsg.textContent = 'Deben ser 5 dígitos';
            validationMsg.className = 'form-text text-warning';
            cpInput.classList.remove('is-valid');
            cpInput.classList.add('is-invalid');
            return;
        }

        try {
            const response = await fetch('core/obtener-codigos-postales.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    termino: cp,
                    validar: true
                })
            });

            const result = await response.json();

            if (result.valid) {
                validationMsg.innerHTML = `CP válido - ${result.municipio}, ${result.estado}`;
                validationMsg.className = 'form-text text-success';
                cpInput.classList.remove('is-invalid');
                cpInput.classList.add('is-valid');
            } else {
                validationMsg.textContent = `✗ CP no encontrado en el catálogo del SAT`;
                validationMsg.className = 'form-text text-danger';
                cpInput.classList.remove('is-valid');
                cpInput.classList.add('is-invalid');
            }
        } catch (error) {
            console.error('Error validando CP:', error);
            validationMsg.textContent = 'Error al validar código postal';
            validationMsg.className = 'form-text text-warning';
        }
        */
    }

    // Sugerencias de CP mientras se escribe
    // COMENTADO TEMPORALMENTE PARA PERMITIR DATOS DE PRUEBA
    /*
    document.addEventListener('DOMContentLoaded', async () => {
        document.getElementById('receptorCP').addEventListener('input', async function() {
            const cp = this.value.trim();

            if (cp.length < 2) return;

            if (!/^\d{1,5}$/.test(cp)) return;

            try {
                const response = await fetch('core/obtener-codigos-postales.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        termino: cp,
                        validar: false
                    })
                });

                const result = await response.json();

                if (result.success && result.data.length > 0) {
                    // Mostrar sugerencia si hay coincidencias
                    const datalist = document.getElementById('cpSuggestions') ||
                        (function() {
                            const dl = document.createElement('datalist');
                            dl.id = 'cpSuggestions';
                            document.body.appendChild(dl);
                            return dl;
                        })();

                    datalist.innerHTML = '';
                    result.data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.d_codigo;
                        datalist.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error obteniendo sugerencias de CP:', error);
            }
        });
    });
    */

    document.addEventListener('DOMContentLoaded', async () => {
        await Promise.all([cargarRegimenesFiscales(), cargarSucursales(), CargarUsoCFDI(), CargarFormaPago()]);

        // Evento para buscar con Enter
        document.getElementById('folioTicketBusqueda').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarTickets();
            }
        });

        // ============================================
        // VALIDACIONES EN TIEMPO REAL
        // ============================================

        // Validar Forma de Pago 99 con Método PPD
        const formaPagoSelect = document.getElementById('formaPago');
        const metodoPagoSelect = document.getElementById('metodoPago');

        function validarFormaPagoMetodo() {
            const formaPago = formaPagoSelect.value;
            const metodoPago = metodoPagoSelect.value;

            // Forma de Pago 99 (Por definir) solo puede usar Método PPD
            if (formaPago === '99' && metodoPago === 'PUE') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incompatibilidad detectada',
                    text: 'La Forma de Pago 99 (Por definir) solo puede usarse con Método de Pago PPD',
                    confirmButtonText: 'Corregir'
                }).then(() => {
                    metodoPagoSelect.value = 'PPD';
                });
            }

            // Método PUE no puede usar Forma 99
            if (metodoPago === 'PUE' && formaPago === '99') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incompatibilidad detectada',
                    text: 'El Método PUE no puede usarse con Forma de Pago 99',
                    confirmButtonText: 'Corregir'
                }).then(() => {
                    formaPagoSelect.value = '';
                });
            }
        }

        formaPagoSelect.addEventListener('change', validarFormaPagoMetodo);
        metodoPagoSelect.addEventListener('change', validarFormaPagoMetodo);

        // Validar Uso CFDI con Régimen Fiscal
        const usoCfdiSelect = document.getElementById('usoCFDI');
        const regimenFiscalSelect = document.getElementById('regimenFiscal');
        const rfcInput = document.getElementById('receptorRFC');

        function validarUsoRegimenRFC() {
            const usoCfdi = usoCfdiSelect.value;
            const regimenFiscal = regimenFiscalSelect.value;
            const rfc = rfcInput.value.trim().toUpperCase();

            if (!usoCfdi || !regimenFiscal || !rfc) return;

            const tipoPersona = detectarTipoPersona(rfc);

            if (!tipoPersona) {
                // RFC inválido
                return;
            }

            const validacion = validarCompatibilidadUsoCfdiRegimen(usoCfdi, regimenFiscal, tipoPersona);

            if (!validacion.valido) {
                Swal.fire({
                    icon: 'error',
                    title: 'Incompatibilidad CFDI',
                    html: validacion.mensaje,
                    confirmButtonText: 'Entendido'
                });
            }
        }

        usoCfdiSelect.addEventListener('change', validarUsoRegimenRFC);
        regimenFiscalSelect.addEventListener('change', validarUsoRegimenRFC);
        rfcInput.addEventListener('blur', validarUsoRegimenRFC);

        // Detectar tipo de persona al escribir RFC
        rfcInput.addEventListener('input', function() {
            const rfc = this.value.trim().toUpperCase();
            this.value = rfc;

            if (rfc.length >= 12) {
                const tipoPersona = detectarTipoPersona(rfc);
                const tipoLabel = document.getElementById('tipoPersonaLabel') ||
                    (function() {
                        const label = document.createElement('small');
                        label.id = 'tipoPersonaLabel';
                        label.className = 'form-text';
                        rfcInput.parentElement.appendChild(label);
                        return label;
                    })();

                if (tipoPersona === 'fisica') {
                    tipoLabel.innerHTML = '<i class="bi bi-person"></i> Persona Física';
                    tipoLabel.className = 'form-text text-info';
                } else if (tipoPersona === 'moral') {
                    tipoLabel.innerHTML = '<i class="bi bi-building"></i> Persona Moral';
                    tipoLabel.className = 'form-text text-info';
                } else if (tipoPersona === 'generico') {
                    tipoLabel.innerHTML = '<i class="bi bi-people"></i> RFC Genérico (Público en General)';
                    tipoLabel.className = 'form-text text-warning';

                    // Autocompletar Régimen 616 y Uso S01 para RFC Genérico
                    if (regimenFiscalSelect.value !== '616') {
                        Swal.fire({
                            icon: 'info',
                            title: 'RFC Genérico detectado',
                            text: 'Se configurará automáticamente Régimen 616 y Uso CFDI S01',
                            timer: 2000
                        });
                        regimenFiscalSelect.value = '616';
                        usoCfdiSelect.value = 'S01';
                    }
                } else {
                    tipoLabel.textContent = '';
                }
            }
        });
    });

    // ============================================
    // ENVIAR FACTURA POR CORREO
    // ============================================
    async function enviarFacturaPorCorreo(idFactura, folio, uuid) {
        const { value: correo } = await Swal.fire({
            title: 'Enviar Factura por Correo',
            html: `
                <p class="text-start mb-3">Enviar factura <strong>${folio}</strong> por correo electrónico</p>
                <input type="email" id="correoReceptor" class="form-control" placeholder="correo@ejemplo.com" required>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-send me-2"></i>Enviar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const correo = document.getElementById('correoReceptor').value;
                if (!correo) {
                    Swal.showValidationMessage('Por favor ingrese un correo electrónico');
                    return false;
                }
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
                    Swal.showValidationMessage('Por favor ingrese un correo válido');
                    return false;
                }
                return correo;
            }
        });

        if (correo) {
            Swal.fire({
                title: 'Enviando factura...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch('core/enviar-factura-correo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id_factura: idFactura,
                        correo_destino: correo
                    })
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Factura Enviada',
                        text: `La factura ${folio} ha sido enviada a ${correo}`,
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al enviar',
                        text: result.message || 'No se pudo enviar la factura por correo',
                        confirmButtonText: 'Aceptar'
                    });
                }
            } catch (error) {
                console.error('Error enviando factura:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor',
                    confirmButtonText: 'Aceptar'
                });
            }
        }
    }

    // al dar generar factura, tambien se generara el archivo xml
    async function generarXMLFactura(id_factura) {
        try {
            const response = await fetch('core/generar-xml.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_factura: id_factura
                })
            });

            const result = await response.json();

            if (result.success) {
                console.log('XML generado correctamente para la factura ID:', id_factura);
            } else {
                console.error('Error al generar XML:', result.message);
            }
        } catch (error) {
            console.error('Error al generar XML de la factura:', error);
        }
    }
</script>