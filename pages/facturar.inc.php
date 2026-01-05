<!-- Página para que los clientes facturen sus compras -->
<div class="content-wrapper bg-light">
    <div class="container py-4">
        <!-- Título de la página -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="bi bi-receipt me-2"></i>
                    Facturar mis Compras
                </h2>
                <p class="text-muted mb-0">Busca tu ticket de compra y genera la factura electrónica</p>
            </div>
        </div>
    </div>

    <!-- Sección de Búsqueda -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4 py-4">
                    <h3 class="mb-0 fw-bold">
                        <i class="bi bi-search me-2"></i>
                        Encuentra tu Compra
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Ingresa los datos de tu ticket de compra</p>
                </div>

                <div class="card-body p-5">
                    <form id="formBuscarTicketCliente">
                        <div class="row g-3">
                            <!-- Nombre de la Empresa -->
                            <div class="col-12">
                                <label for="nombreSucursal" class="form-label fw-semibold">
                                    <i class="bi bi-building me-1"></i>Sucursal
                                </label>
                                <input type="text" class="form-control form-control-lg" 
                                    id="nombreSucursal"
                                    placeholder="Ej: Tienda Central" 
                                    required>
                                <div class="form-text">
                                    Ingresa el nombre de la sucursal donde realizaste tu compra
                                </div>
                            </div>

                            <!-- Folio del Ticket -->
                            <div class="col-12">
                                <label for="folioTicket" class="form-label fw-semibold">
                                    <i class="bi bi-hash me-1"></i>Número de Folio
                                </label>
                                <input type="text" class="form-control form-control-lg" 
                                    id="folioTicket"
                                    placeholder="Ej: 123456789" 
                                    required>
                                <div class="form-text">
                                    Encontrarás este número en tu ticket de compra
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
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="button" class="btn btn-primary btn-lg py-3 fw-semibold" 
                                id="btnBuscarTicketCliente">
                                <i class="bi bi-search me-2"></i>
                                Buscar Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Resultados (inicialmente oculta) - REMOVIDA, se muestra en página separada -->

    <!-- Área de Carga -->
    <div id="areaCarga" class="row justify-content-center" style="display:none;">
        <div class="col-lg-8">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="text-muted mt-3 fs-5">Buscando tu ticket...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Event Listeners
    document.getElementById('btnBuscarTicketCliente').addEventListener('click', buscarTicket);
});

// Establecer fecha máxima (hoy)
function establecerFechaMaxima() {
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fechaTicket').max = hoy;
}

// Cargar sucursales del cliente
async function cargarSucursales() {
    try {
        console.log('Iniciando cargarSucursales()...');
        const response = await fetch('./core/consultar-sucursales.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'  // Importante para pasar cookies de sesión
        });
        
        const responseText = await response.text();
        console.log('Respuesta bruta:', responseText);
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const result = JSON.parse(responseText);
        console.log('Resultado parseado:', result);
        
        const select = document.getElementById('selectSucursal');

        if (result.success && result.data && result.data.length > 0) {
            // GUARDAMOS LOS DATOS EN LA VARIABLE GLOBAL
            listaSucursales = result.data;
            console.log('Sucursales cargadas:', listaSucursales);

            const listaSucursalesModal = document.getElementById('listaSucursalesModal');
            let htmlModal = '';

            select.innerHTML = '<option value="">-- Selecciona la sucursal --</option>';
            result.data.forEach(sucursal => {
                const option = document.createElement('option');
                option.value = sucursal.id_empresa;
                option.textContent = (sucursal.razon_social || sucursal.nombre) + ' (' + sucursal.codigo_suc + ')';
                select.appendChild(option);
                
                // Agregar al modal con más información
                htmlModal += `
                    <div class="card mb-2 cursor-pointer hover-effect" 
                         onclick="seleccionarSucursal(${sucursal.id_empresa}, '${sucursal.razon_social || sucursal.nombre}')">
                        <div class="card-body py-3 px-4">
                            <h6 class="card-title mb-1 fw-bold">${sucursal.razon_social || sucursal.nombre}</h6>
                            <small class="text-muted">Código: ${sucursal.codigo_suc} | RFC: ${sucursal.rfc || 'N/A'}</small>
                        </div>
                    </div>
                `;
            });
            
            listaSucursalesModal.innerHTML = htmlModal;
        } else {
            select.innerHTML = '<option value="">No hay sucursales registradas</option>';
            console.warn('Sin sucursales:', result.message);
            mostrarAlerta(result.message || 'No tienes sucursales registradas. Contacta a soporte.', 'warning');
        }
    } catch (error) {
        console.error('Error al cargar sucursales:', error);
        document.getElementById('selectSucursal').innerHTML = '<option value="">Error de conexión</option>';
        mostrarAlerta('Error al cargar las sucursales: ' + error.message, 'danger');
    }
}

// Seleccionar sucursal desde el modal
function seleccionarSucursal(idEmpresa, nombre) {
    document.getElementById('selectSucursal').value = idEmpresa;
    modalSucursales.hide();
}

// Buscar ticket
function buscarTicket() {
    const nombreSucursal = document.getElementById('nombreSucursal').value.trim();
    const folio = document.getElementById('folioTicket').value.trim();
    const monto = document.getElementById('montoTicket').value.trim();

    // Validaciones básicas
    if (!nombreSucursal || !folio || !monto) {
        mostrarAlerta('Por favor completa todos los campos', 'warning');
        return;
    }

    // Mostrar área de carga
    document.getElementById('areaCarga').style.display = 'block';

    // Enviar solicitud al servidor
    const formData = new FormData();
    formData.append('nombre_empresa', nombreSucursal);
    formData.append('folio', folio);
    formData.append('monto', monto);

    fetch('./core/buscar-ticket-cliente.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta búsqueda:', data); // Debug
        document.getElementById('areaCarga').style.display = 'none';

        if (data.success) {
            // Guardar el ticket en sessionStorage para la página de detalles
            sessionStorage.setItem('ticketActual', JSON.stringify(data.ticket));
            mostrarAlerta('¡Ticket encontrado correctamente!', 'success');
            
            // Redirigir a la página de detalles
            setTimeout(() => {
                window.location.href = 'panel?pg=detalle-ticket';
            }, 800);
        } else {
            mostrarAlerta(data.message || 'No se encontró el ticket', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('areaCarga').style.display = 'none';
        mostrarAlerta('Error al buscar el ticket: ' + error.message, 'danger');
    });
}

// Mostrar información del ticket
function mostrarTicket(ticket) {
    // Esta función ya no se usa, el detalle se muestra en detalle-ticket.inc.php
}

// Nueva búsqueda
function nuevaBusqueda() {
    document.getElementById('formBuscarTicketCliente').reset();
    document.getElementById('folioTicket').focus();
}

// Función auxiliar para formatear fechas
function formatearFecha(fecha) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(fecha + 'T00:00:00').toLocaleDateString('es-MX', options);
}

// Función auxiliar para mostrar alertas
function mostrarAlerta(mensaje, tipo = 'info') {
    // Crear alerta Bootstrap
    const alertaHTML = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
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