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
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">RFC</label>
                                    <input type="text" class="form-control text-uppercase" id="receptorRFC"
                                        placeholder="XAXX010101000" maxlength="13" required>
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
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="receptorCorreo"
                                        placeholder="correo@ejemplo.com" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Código Postal</label>
                                    <input type="text" class="form-control" id="receptorCP"
                                        placeholder="12345" maxlength="5" required>
                                </div>
                                <div class="col-8">
                                    <label class="form-label fw-semibold">Domicilio Fiscal</label>
                                    <textarea class="form-control" id="receptorDomicilio" rows="2"
                                        placeholder="Calle, número, colonia"></textarea>
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
                                    <span class="fw-semibold">IVA (16%):</span>
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
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Descripción</label>
                        <input type="text" class="form-control concepto-descripcion" placeholder="Producto o servicio" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Cantidad</label>
                        <input type="number" class="form-control concepto-cantidad" value="1" min="0.01" step="0.01" onchange="calcularTotales()" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Precio Unitario</label>
                        <input type="number" class="form-control concepto-precio" value="0" min="0" step="0.01" onchange="calcularTotales()" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Clave SAT</label>
                        <input type="text" class="form-control concepto-clave" placeholder="01010101" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Unidad SAT</label>
                        <input type="text" class="form-control concepto-unidad" placeholder="H87" required>
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
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Descripción</label>
                        <input type="text" class="form-control concepto-descripcion" value="${producto.descr}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Cantidad</label>
                        <input type="number" class="form-control concepto-cantidad" value="${producto.cant}" min="0.01" step="0.01" onchange="calcularTotales()" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Precio Unitario</label>
                        <input type="number" class="form-control concepto-precio" value="${producto.precio_unit}" min="0" step="0.01" onchange="calcularTotales()" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Clave SAT</label>
                        <input type="text" class="form-control concepto-clave" value="01010101" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Unidad SAT</label>
                        <input type="text" class="form-control concepto-unidad" value="H87" required>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', conceptoHtml);
    }

    // ============================================
    // GENERAR FACTURA
    // ============================================

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

        Swal.fire({
            title: 'Generando factura...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Preparar datos para enviar
        const conceptosData = [];
        document.querySelectorAll('.concepto-row').forEach(row => {
            conceptosData.push({
                descripcion: row.querySelector('.concepto-descripcion').value,
                cantidad: row.querySelector('.concepto-cantidad').value,
                precio: row.querySelector('.concepto-precio').value,
                clave: row.querySelector('.concepto-clave').value,
                unidad: row.querySelector('.concepto-unidad').value
            });
        });

        const datosFactura = {
            id_sucursal: sucursalId,
            id_ticket: document.getElementById('ticketIdActual').value || null,
            receptor: {
                rfc: receptorRFC,
                nombre: receptorNombre,
                cp: receptorCP,
                domicilio: document.getElementById('receptorDomicilio').value,
                correo: document.getElementById('receptorCorreo').value,
                regimen: document.getElementById('regimenFiscal').value,
                uso_cfdi: usoCFDI
            },
            forma_pago: formaPago,
            metodo_pago: document.getElementById('metodoPago').value,
            conceptos: conceptosData,
            observaciones: document.getElementById('observaciones').value
        };

        try {
            // Llamar al backend para generar la factura
            const response = await fetch('core/generar-factura.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datosFactura)
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Factura generada',
                    html: `La factura <strong>${result.folio}</strong> se ha generado correctamente`,
                    showConfirmButton: true
                }).then(() => {
                    // Limpiar formulario
                    limpiarFormulario();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'No se pudo generar la factura'
                });
            }
        } catch (error) {
            console.error('Error al generar factura:', error);
            Swal.fire('Error', 'No se pudo generar la factura', 'error');
        }
    }

    // Variable global para almacenar sucursales
    let listaSucursales = [];

    document.addEventListener('DOMContentLoaded', async () => {
        await Promise.all([cargarRegimenesFiscales(), cargarSucursales(), CargarUsoCFDI(), CargarFormaPago()]);

        // Evento para buscar con Enter
        document.getElementById('folioTicketBusqueda').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarTickets();
            }
        });
    });

    // al dar generar factura, tambien se generara el archivo xml
    async function generarXMLFactura(id_factura) {
        try {
            const response = await fetch('core/generar-xml.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id_factura: id_factura })
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