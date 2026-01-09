<!-- Página de detalle del ticket -->
<!-- SweetAlert2 CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

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

// =========================
// FUNCIONES DE VALIDACIÓN
// =========================

// Validar datos del ticket antes de facturar
function validarDatosTicket(ticket) {
    if (!ticket) {
        return { valido: false, mensaje: 'No hay datos de ticket para validar' };
    }

    // Validar que tenga folio
    if (!ticket.folio) {
        return { valido: false, mensaje: 'El ticket debe tener un folio' };
    }

    // Validar que tenga detalles
    if (!ticket.detalles || ticket.detalles.length === 0) {
        return { valido: false, mensaje: 'El ticket debe tener al menos un producto' };
    }

    // Validar que tenga total
    if (!ticket.total || parseFloat(ticket.total) <= 0) {
        return { valido: false, mensaje: 'El total del ticket debe ser mayor a 0' };
    }

    // Validar que tenga forma de pago
    if (!ticket.pagos || ticket.pagos.length === 0) {
        return { valido: false, mensaje: 'El ticket debe tener forma de pago registrada' };
    }

    return { valido: true, mensaje: '' };
}

// Procesar error de respuesta de Finkok
function procesarErrorFinkok(data) {
    if (!data.message) {
        return {
            tipo: 'general',
            titulo: 'Error desconocido',
            mensaje: 'Ocurrió un error al generar la factura'
        };
    }

    const mensaje = data.message.toLowerCase();
    const incidencias = data.debug?.incidencias || '';

    // Errores del cliente - contactar soporte
    const erroresCliente = [
        { 
            pattern: /suspended|suspendido/i, 
            mensaje: 'Tu RFC está suspendido en Finkok. No es posible generar facturas en este momento.'
        },
        { 
            pattern: /718|timbres agotados|timbre/i, 
            mensaje: 'Se han agotado los timbres disponibles. No es posible generar más facturas.'
        },
        { 
            pattern: /credito|credit/i, 
            mensaje: 'Problema con los créditos en tu cuenta de Finkok. Verifica tu cuenta.'
        }
    ];

    for (let error of erroresCliente) {
        if (error.pattern.test(mensaje) || error.pattern.test(incidencias)) {
            return {
                tipo: 'cliente',
                titulo: 'Problema con tu cuenta de Finkok',
                mensaje: error.mensaje
            };
        }
    }

    // Errores de validación de datos
    const erroresValidacion = [
        {
            pattern: /cfdi40161|usocfdi|regimen|régimen/i,
            mensaje: 'El régimen fiscal o uso CFDI seleccionado no es válido para este tipo de comprobante. Verifica los datos fiscales del cliente.'
        },
        {
            pattern: /xml|estructura|sintaxis|schema/i,
            mensaje: 'Hay un problema con la estructura del XML. Revisa los datos del ticket.'
        },
        {
            pattern: /rfc|registro/i,
            mensaje: 'El RFC del cliente no es válido o está incompleto.'
        },
        {
            pattern: /cantidad|importe|precio/i,
            mensaje: 'Hay un problema con los precios o cantidades de los productos.'
        }
    ];

    for (let error of erroresValidacion) {
        if (error.pattern.test(mensaje) || error.pattern.test(incidencias)) {
            return {
                tipo: 'validacion',
                titulo: 'Error de validación',
                mensaje: error.mensaje
            };
        }
    }

    // Error genérico
    return {
        tipo: 'general',
        titulo: 'Error al generar factura',
        mensaje: data.message || 'Ocurrió un error desconocido'
    };
}

// =========================
// FUNCIONES SWEETALERT
// =========================

// Mostrar error simple
async function mostrarErrorSweetAlert(mensaje) {
    await Swal.fire({
        title: 'Error',
        text: mensaje,
        icon: 'error',
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Entendido'
    });
}

// Mostrar error del cliente (contactar soporte)
async function mostrarErrorClienteSweetAlert(errorInfo) {
    await Swal.fire({
        title: 'No se puede generar la factura',
        html: `
            <div class="text-start">
                <p><strong>Motivo:</strong></p>
                <p class="text-danger">${errorInfo.mensaje}</p>
                <hr>
                <p class="small text-muted mb-3">Por favor, contacta a nuestro equipo de soporte para resolver este problema:</p>
                <div class="alert alert-info mb-3">
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i><strong>Email:</strong> <a href="mailto:soporte@tuempresa.com">soporte@tuempresa.com</a></p>
                    <p class="mb-0"><i class="fas fa-phone me-2"></i><strong>Teléfono:</strong> +1-234-567-8900</p>
                </div>
            </div>
        `,
        icon: 'warning',
        confirmButtonColor: '#ffc107',
        confirmButtonText: 'Entendido'
    });
}

// Mostrar error de validación
async function mostrarErrorValidacionSweetAlert(errorInfo) {
    await Swal.fire({
        title: 'Error de Validación',
        html: `
            <div class="text-start">
                <p><strong>El siguiente problema impide generar la factura:</strong></p>
                <p class="text-danger">${errorInfo.mensaje}</p>
                <p class="small text-muted mt-3">Revisa los datos del ticket y asegúrate de que sean correctos.</p>
            </div>
        `,
        icon: 'error',
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Entendido'
    });
}

// Mostrar error general
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

// Mapear error devuelto por generar-factura-ticket.php
function mapearErrorFacturaBackend(data) {
    // Si viene código específico del backend
    if (data && data.codigo_error) {
        // Régimen/uso CFDI
        if (data.codigo_error === 'CFDI40161') {
            return {
                tipo: 'validacion',
                titulo: 'Régimen Fiscal Inválido',
                mensaje: data.message || 'No se pueden facturar este ticket para su régimen fiscal',
                detalles: {
                    detalle: 'El régimen fiscal o uso CFDI no corresponde con el tipo de persona o régimen.',
                    solucion: 'Actualiza los datos fiscales (régimen fiscal y uso CFDI) y vuelve a intentar.',
                    codigoError: 'CFDI40161'
                },
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

    // Fallback al analizador previo
    return procesarErrorFinkok(data || {});
}

// Mostrar modal de éxito con SweetAlert
async function mostrarModalExitoSweetAlert(data) {
    await Swal.fire({
        title: '¡Factura Generada Exitosamente!',
        html: `
            <div class="text-center">
                <i class="fas fa-check-circle text-success" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <h5>Folio: <strong>${data.folio}</strong></h5>
                ${data.uuid ? `<p class="text-muted small">UUID: ${data.uuid}</p>` : ''}
                <div class="alert alert-success mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Tu factura ha sido timbrada correctamente y está lista para descargar.
                </div>
                <div class="row g-2 mt-3">
                    ${data.xml_url ? `
                    <div class="col-6">
                        <a href="${data.xml_url}" download class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-file-code me-2"></i>XML
                        </a>
                    </div>
                    ` : ''}
                    ${data.pdf_url ? `
                    <div class="col-6">
                        <a href="${data.pdf_url}&guardar=1" class="btn btn-sm btn-outline-danger w-100" target="_blank">
                            <i class="fas fa-file-pdf me-2"></i>PDF
                        </a>
                    </div>
                    ` : ''}
                </div>
            </div>
        `,
        icon: 'success',
        confirmButtonColor: '#198754',
        confirmButtonText: 'Ver mis facturas',
        didClose: () => {
            window.location.href = 'panel?pg=historial';
        }
    });
}

// Función auxiliar para mostrar alertas (legacy, usa SweetAlert)
function mostrarAlerta(mensaje, tipo = 'info') {
    const iconMap = {
        'info': 'info',
        'success': 'success',
        'warning': 'warning',
        'danger': 'error',
        'error': 'error'
    };
    
    Swal.fire({
        icon: iconMap[tipo] || 'info',
        title: tipo === 'danger' || tipo === 'error' ? 'Error' : 'Información',
        text: mensaje,
        timer: 3000,
        timerProgressBar: true
    });
}

// Facturar ticket
async function facturarTicket() {
    if (!ticketActual) {
        mostrarErrorSweetAlert('No hay ticket seleccionado');
        return;
    }

    // Validar datos requeridos del ticket
    const validacionTicket = validarDatosTicket(ticketActual);
    if (!validacionTicket.valido) {
        mostrarErrorSweetAlert(validacionTicket.mensaje);
        return;
    }

    // Mostrar confirmación con SweetAlert
    const result = await Swal.fire({
        title: '¿Generar Factura?',
        html: `<p>Se generará la factura para el siguiente ticket:</p>
               <p><strong>Folio:</strong> ${ticketActual.folio}</p>
               <p><strong>Total:</strong> $${parseFloat(ticketActual.total || 0).toFixed(2)}</p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check me-2"></i>Generar Factura',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar'
    });

    if (!result.isConfirmed) {
        return;
    }

    // Mostrar progreso
    await Swal.fire({
        title: 'Procesando Factura',
        html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><p class="mt-3">Por favor espera mientras procesamos tu factura...</p>',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
       
    });

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
            mostrarModalExitoSweetAlert(data);
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
}

// Función para volver a búsqueda desde el modal
function volverABusqueda() {
    irABusqueda();
}

// Función auxiliar para formatear fechas
function formatearFecha(fecha) {
    if (!fecha) return '-';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(fecha + 'T00:00:00').toLocaleDateString('es-MX', options);
}
</script>

