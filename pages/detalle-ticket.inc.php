<!-- Página de detalle del ticket encontrado -->
<div class="content-wrapper">
    <!-- Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark fw-bold">
                        <i class="bi bi-receipt me-2 text-primary"></i>Detalles del Ticket
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" onclick="irABusqueda()">Inicio</a></li>
                        <li class="breadcrumb-item active">Ticket</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <div class="container-fluid">
            <!-- Información del Ticket Encontrado -->
            <div class="row mb-3">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <h3 class="card-title text-primary fw-bold">
                                <i class="bi bi-check-circle me-2"></i>Información del Ticket
                            </h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Datos del Ticket -->
                                <div class="col-md-3">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-primary">
                                            <i class="bi bi-receipt"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Folio</span>
                                            <span class="info-box-number text-primary fw-bold" id="ticketFolio">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-primary">
                                            <i class="bi bi-calendar"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Fecha de Venta</span>
                                            <span class="info-box-number text-primary fw-bold" id="ticketFecha">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-primary">
                                            <i class="bi bi-building"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Sucursal</span>
                                            <span class="info-box-number text-primary fw-bold" id="ticketSucursal">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-primary">
                                            <i class="bi bi-code"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Código</span>
                                            <span class="info-box-number text-primary fw-bold" id="ticketCodigoSuc">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles de la Compra -->
            <div class="card card-primary card-outline shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title fw-bold mb-0">
                        <i class="bi bi-list-check me-2"></i>Detalles de la Compra
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 tabla-mejorada">
                            <thead class="thead-azul">
                                <tr>
                                    <th class="ps-4">Descripción</th>
                                    <th class="text-center" style="width: 12%;">Cantidad</th>
                                    <th class="text-end" style="width: 15%;">Precio Unit.</th>
                                    <th class="text-end pe-4" style="width: 15%;">Importe</th>
                                </tr>
                            </thead>
                            <tbody id="detallesTicket">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        <p class="mt-2 mb-0">Cargando detalles...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumen de Montos -->
            <div class="row mb-3">
                <div class="col-lg-5 ms-auto">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <h3 class="card-title text-primary fw-bold">
                                <i class="bi bi-calculator me-2"></i>Resumen
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-semibold" id="resumenSubtotal">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <span class="text-muted">IVA (16%):</span>
                                <span class="fw-semibold text-primary" id="resumenImpuesto">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold fs-6">Total a Facturar:</span>
                                <span class="fw-bold fs-5 text-primary">
                                    <i class="bi bi-cash-coin me-1"></i>
                                    <span id="resumenTotal">$0.00</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Métodos de Pago -->
            <div class="card card-primary card-outline shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title fw-bold mb-0">
                        <i class="bi bi-credit-card me-2"></i>Métodos de Pago
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 tabla-mejorada">
                            <thead class="thead-azul">
                                <tr>
                                    <th class="ps-4" style="width: 30%;">Método de Pago</th>
                                    <th class="ps-3" style="width: 50%;">Forma de Pago</th>
                                    <th class="text-end pe-4" style="width: 20%;">Monto</th>
                                </tr>
                            </thead>
                            <tbody id="metodosTicket">
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        <p class="mt-2 mb-0">Cargando métodos de pago...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-outline-primary" id="btnNuevaBusqueda">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>
                            Nueva Búsqueda
                        </button>
                        <button type="button" class="btn btn-primary" id="btnFacturar">
                            <i class="bi bi-file-earmark-pdf me-2"></i>
                            Generar Factura
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
    document.getElementById('btnVolverBusqueda').addEventListener('click', irABusqueda);
    document.getElementById('btnNuevaBusqueda').addEventListener('click', irABusqueda);
    document.getElementById('btnFacturar').addEventListener('click', facturarTicket);
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
                <tr class="fila-detalle">
                    <td class="ps-4">
                        <i class="bi bi-box-seam text-primary me-2"></i>
                        <strong>${descr}</strong>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark border">${cant}</span>
                    </td>
                    <td class="text-end">$${precioUnit}</td>
                    <td class="text-end pe-4">
                        <strong class="text-primary">$${importe}</strong>
                    </td>
                </tr>
            `;
        });
    }
    document.getElementById('detallesTicket').innerHTML = htmlDetalles || '<tr><td colspan="4" class="text-center text-muted py-4">Sin detalles disponibles</td></tr>';

    // Resumen
    document.getElementById('resumenSubtotal').textContent = '$' + parseFloat(ticket.subtotal).toFixed(2);
    document.getElementById('resumenImpuesto').textContent = '$' + parseFloat(ticket.impuesto).toFixed(2);
    document.getElementById('resumenTotal').textContent = '$' + parseFloat(ticket.total).toFixed(2);

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
                    <td class="ps-3"><span class="badge bg-primary">${metodoTexto}</span></td>
                    <td class="ps-3">${formaTexto}</td>
                    <td class="text-end pe-3"><strong>$${monto}</strong></td>
                </tr>
            `;
        });
    }
    document.getElementById('metodosTicket').innerHTML = htmlPagos || '<tr><td colspan="3" class="text-center text-muted py-3">Sin métodos de pago</td></tr>';
}

// Ir a búsqueda
function irABusqueda() {
    sessionStorage.removeItem('ticketActual');
    window.location.href = '?pagina=facturar';
}

// Facturar ticket
function facturarTicket() {
    if (!ticketActual) {
        mostrarAlerta('No hay ticket seleccionado', 'danger');
        return;
    }

    // Mostrar confirmación
    if (!confirm('¿Estás seguro de que deseas generar la factura para este ticket?')) {
        return;
    }

    // Desabilitar botón
    const btn = document.getElementById('btnFacturar');
    const textOriginal = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generando factura...';

    const formData = new FormData();
    formData.append('id_ticket', ticketActual.id_ticket);
    formData.append('id_empresa', ticketActual.id_empresa);

    fetch('./core/generar-factura.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = textOriginal;

        if (data.success) {
            mostrarAlerta('¡Factura generada correctamente! Folio: ' + data.folio, 'success');
            // Limpiar y volver a búsqueda
            setTimeout(() => {
                irABusqueda();
            }, 1500);
        } else {
            mostrarAlerta(data.message || 'Error al generar la factura', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = textOriginal;
        mostrarAlerta('Error al procesar la factura', 'danger');
    });
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

<style>
.info-box {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 0.5rem;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.info-box-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    border-radius: 0.5rem;
    color: white;
    font-size: 1.5rem;
    margin-right: 1rem;
}

.info-box-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.info-box-text {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.info-box-number {
    font-size: 1.5rem;
    font-weight: bold;
    margin-top: 0.25rem;
}

.table-primary {
    background-color: #007bff !important;
}

.table-primary th {
    color: white;
    border-color: #0056b3 !important;
}

.card-primary.card-outline {
    border-top: 3px solid #007bff;
}

.bg-primary {
    background-color: #007bff !important;
}
</style>
