<!-- Página de detalle del ticket -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-receipt me-2"></i>Información del Ticket</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Card principal -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle me-2"></i>Datos del Ticket</h3>
                </div>
                <div class="card-body">
                    <!-- Información básica -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label class="text-muted small">Folio</label>
                            <div class="h5 mb-0" id="ticketFolio">-</div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label class="text-muted small">Fecha</label>
                            <div class="h5 mb-0" id="ticketFecha">-</div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label class="text-muted small">Sucursal</label>
                            <div class="h5 mb-0" id="ticketSucursal">-</div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label class="text-muted small">Código Sucursal</label>
                            <div class="h5 mb-0" id="ticketCodigoSuc">-</div>
                        </div>
                    </div>

                    <hr>

                    <!-- Productos -->
                    <h5 class="mb-3"><i class="fas fa-list me-2"></i>Productos</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Descripción</th>
                                    <th class="text-center" style="width: 100px;">Cantidad</th>
                                    <th class="text-end" style="width: 120px;">Precio Unit.</th>
                                    <th class="text-end" style="width: 120px;">Importe</th>
                                </tr>
                            </thead>
                            <tbody id="detallesTicket">
                                <tr>
                                    <td colspan="4" class="text-center py-3">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Cargando...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totales -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end" id="resumenSubtotal">$0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-end"><strong>IVA (16%):</strong></td>
                                    <td class="text-end" id="resumenImpuesto">$0.00</td>
                                </tr>
                                <tr class="table-active">
                                    <td class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong class="text-primary fs-5" id="resumenTotal">$0.00</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Métodos de pago -->
                    <h5 class="mb-3"><i class="fas fa-credit-card me-2"></i>Forma de Pago</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Método de Pago</th>
                                    <th>Forma de Pago</th>
                                    <th class="text-end" style="width: 150px;">Monto</th>
                                </tr>
                            </thead>
                            <tbody id="metodosTicket">
                                <tr>
                                    <td colspan="3" class="text-center py-3">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Cargando...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="btnNuevaBusqueda">
                            <i class="fas fa-arrow-left me-2"></i>Nueva Búsqueda
                        </button>
                        <button type="button" class="btn btn-primary" id="btnFacturar">
                            <i class="fas fa-file-invoice me-2"></i>Generar Factura
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variable global para el ticket
let ticketActual = null;

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Recuperar el ticket del sessionStorage
    const ticketJSON = sessionStorage.getItem('ticketActual');
    
    if (!ticketJSON) {
        mostrarAlerta('No hay ticket para mostrar. Por favor, realiza una búsqueda.', 'warning');
        setTimeout(() => {
            irABusqueda();
        }, 2000);
        return;
    }
    
    try {
        ticketActual = JSON.parse(ticketJSON);
        mostrarTicket(ticketActual);
    } catch (error) {
        console.error('Error al parsear ticket:', error);
        mostrarAlerta('Error al cargar los datos del ticket.', 'danger');
        setTimeout(() => {
            irABusqueda();
        }, 2000);
    }
    
    // Event Listeners
    const btnNuevaBusqueda = document.getElementById('btnNuevaBusqueda');
    const btnFacturar = document.getElementById('btnFacturar');
    
    if (btnNuevaBusqueda) {
        btnNuevaBusqueda.addEventListener('click', irABusqueda);
    }
    
    if (btnFacturar) {
        btnFacturar.addEventListener('click', facturarTicket);
    }
});

// Mostrar información del ticket
function mostrarTicket(ticket) {
    if (!ticket) return;
    
    // Información básica
    document.getElementById('ticketFolio').textContent = ticket.folio || '-';
    document.getElementById('ticketFecha').textContent = formatearFecha(ticket.fecha_venta) || '-';
    document.getElementById('ticketSucursal').textContent = ticket.sucursal || '-';
    document.getElementById('ticketCodigoSuc').textContent = ticket.codigo_sucursal || '-';

    // Detalles del ticket
    let htmlDetalles = '';
    if (ticket.detalles && ticket.detalles.length > 0) {
        ticket.detalles.forEach(detalle => {
            const precioUnit = parseFloat(detalle.precio_unit || detalle.precio_unitario || 0).toFixed(2);
            const importe = parseFloat(detalle.importe || 0).toFixed(2);
            const descr = detalle.descr || detalle.descripcion || 'Sin descripción';
            const cant = detalle.cant || detalle.cantidad || 0;
            
            htmlDetalles += `
                <tr>
                    <td>${descr}</td>
                    <td class="text-center">${cant}</td>
                    <td class="text-end">$${precioUnit}</td>
                    <td class="text-end"><strong>$${importe}</strong></td>
                </tr>
            `;
        });
    }
    document.getElementById('detallesTicket').innerHTML = htmlDetalles || '<tr><td colspan="4" class="text-center text-muted py-3">Sin productos</td></tr>';

    // Resumen
    document.getElementById('resumenSubtotal').textContent = '$' + parseFloat(ticket.subtotal || 0).toFixed(2);
    document.getElementById('resumenImpuesto').textContent = '$' + parseFloat(ticket.impuesto || 0).toFixed(2);
    document.getElementById('resumenTotal').textContent = '$' + parseFloat(ticket.total || 0).toFixed(2);

    // Métodos de pago
    let htmlPagos = '';
    if (ticket.pagos && ticket.pagos.length > 0) {
        ticket.pagos.forEach(pago => {
            const monto = parseFloat(pago.monto || 0).toFixed(2);
            const metodo = pago.metodo_pago || 'N/A';
            const forma = pago.forma_pago || 'N/A';
            
            // Traducción de métodos de pago
            let metodoTexto = metodo;
            if (metodo === 'PUE') metodoTexto = 'Pago en una Exhibición';
            if (metodo === 'PPD') metodoTexto = 'Pago Diferido';
            
            // Traducción de formas de pago
            let formaTexto = forma;
            const formasPago = {
                '01': 'Efectivo',
                '02': 'Cheque nominativo',
                '03': 'Transferencia electrónica',
                '04': 'Tarjeta de crédito',
                '05': 'Monedero electrónico',
                '06': 'Dinero electrónico',
                '08': 'Vales de despensa',
                '12': 'Dación en pago',
                '13': 'Pago por subasta',
                '14': 'Pago con divisas',
                '15': 'Transporte de dinero',
                '16': 'Transferencia de fondos electrónica',
                '17': 'Hospedaje',
                '18': 'Valuación de bienes inmuebles',
                '19': 'Pago con criptomonedas',
                '99': 'Otros'
            };
            formaTexto = formasPago[forma] || forma;
            
            htmlPagos += `
                <tr>
                    <td>${metodoTexto}</td>
                    <td>${formaTexto}</td>
                    <td class="text-end"><strong>$${monto}</strong></td>
                </tr>
            `;
        });
    }
    document.getElementById('metodosTicket').innerHTML = htmlPagos || '<tr><td colspan="3" class="text-center text-muted py-3">Sin información de pago</td></tr>';
}

// Ir a búsqueda
function irABusqueda() {
    // Limpiar el ticket del sessionStorage para hacer una nueva búsqueda
    sessionStorage.removeItem('ticketActual');
    // Redirigir a la página de facturación
    window.location.href = 'panel?pg=facturar';
}

// Facturar ticket
async function facturarTicket() {
    if (!ticketActual) {
        mostrarAlerta('No hay ticket seleccionado', 'danger');
        return;
    }

    // Mostrar confirmación
    if (!confirm('¿Estás seguro de que deseas generar la factura para este ticket?\n\nSe generará el XML, se timbrará con Finkok y se creará el PDF.')) {
        return;
    }

    // Desabilitar botón
    const btn = document.getElementById('btnFacturar');
    const textOriginal = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando factura...';

    try {
        // Preparar datos para enviar
        const datosFactura = {
            id_ticket: ticketActual.id_ticket,
            id_empresa: ticketActual.id_empresa
        };

        // Llamar al endpoint que procesa todo el flujo
        const response = await fetch('./core/generar-factura-ticket.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datosFactura)
        });

        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor: ' + response.status);
        }

        const data = await response.json();

        if (data.success) {
            // Mostrar mensaje de éxito con detalles
            let mensaje = `¡Factura generada exitosamente!<br>`;
            mensaje += `<strong>Folio:</strong> ${data.folio}<br>`;
            if (data.uuid) {
                mensaje += `<strong>UUID:</strong> ${data.uuid.substring(0, 20)}...<br>`;
            }
            
            // Crear modal con opciones de descarga
            mostrarModalExito(data);
        } else {
            throw new Error(data.message || 'Error desconocido al generar la factura');
        }

    } catch (error) {
        console.error('Error al facturar:', error);
        mostrarAlerta('Error: ' + error.message, 'danger');
        btn.disabled = false;
        btn.innerHTML = textOriginal;
    }
}

// Modal de éxito con opciones de descarga
function mostrarModalExito(data) {
    const modalHTML = `
        <div class="modal fade" id="modalFacturaExitosa" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle me-2"></i>Factura Generada Exitosamente
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-file-invoice fa-4x text-success mb-3"></i>
                            <h4>Folio: ${data.folio}</h4>
                            ${data.uuid ? `<p class="small text-muted">UUID: ${data.uuid}</p>` : ''}
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Tu factura ha sido timbrada correctamente y está lista para descargar.
                        </div>

                        <div class="d-grid gap-2">
                            ${data.xml_url ? `
                            <a href="${data.xml_url}" download class="btn btn-outline-primary" target="_blank">
                                <i class="fas fa-file-code me-2"></i>Descargar XML
                            </a>
                            ` : ''}
                            
                            ${data.pdf_url ? `
                            <a href="${data.pdf_url}&guardar=1" class="btn btn-outline-danger" target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                            </a>
                            ` : ''}
                            
                            <a href="?pagina=facturas" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>Ver Mis Facturas
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="volverABusqueda()">
                            <i class="fas fa-arrow-left me-2"></i>Nueva Búsqueda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Agregar modal al DOM
    const modalContainer = document.createElement('div');
    modalContainer.innerHTML = modalHTML;
    document.body.appendChild(modalContainer);

    // Mostrar modal usando Bootstrap
    const modal = new bootstrap.Modal(document.getElementById('modalFacturaExitosa'));
    modal.show();

    // Limpiar al cerrar
    document.getElementById('modalFacturaExitosa').addEventListener('hidden.bs.modal', function () {
        modalContainer.remove();
    });
}

// Función para volver a búsqueda desde el modal
function volverABusqueda() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalFacturaExitosa'));
    if (modal) {
        modal.hide();
    }
    irABusqueda();
}

// Función auxiliar para formatear fechas
function formatearFecha(fecha) {
    if (!fecha) return '-';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(fecha + 'T00:00:00').toLocaleDateString('es-MX', options);
}

// Función auxiliar para mostrar alertas
function mostrarAlerta(mensaje, tipo = 'info') {
    // Crear alerta Bootstrap
    const alertaHTML = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <i class="bi bi-info-circle me-2"></i>
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.createElement('div');
    container.innerHTML = alertaHTML;
    document.body.appendChild(container.firstElementChild);
    
    // Auto-cerrar después de 4 segundos
    setTimeout(() => {
        const alerta = document.querySelector('.alert:last-of-type');
        if (alerta) {
            alerta.remove();
        }
    }, 4000);
}
</script>

