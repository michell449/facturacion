<?php
// pages/config-correo.inc.php
?>
<div class="bg-primary text-white py-4">
    <div class="container h-100 d-flex align-items-center">
        <div class="row w-100 align-items-center">
            <div class="col-lg-6">
                <h1 class="fw-bold mb-4">Configuración de Correo <i class="bi bi-envelope-at m-2 opacity-75"></i></h1>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <form id="correoConfigForm">
                        <!-- Formato del Correo -->
                        <div class="mb-4">
                            <h5 class="text-primary fw-bold mb-3">
                                <i class="bi bi-file-text me-2"></i>Formato del Correo
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="asuntoFactura" class="form-label fw-semibold">Asunto del Correo</label>
                                    <input type="text" class="form-control form-control-lg" id="asuntoFactura" 
                                           placeholder="Tu factura electrónica - Folio {FOLIO}" 
                                           value="Factura Electrónica - Folio {FOLIO}" required>
                                    <small class="text-muted">Variables: {FOLIO}, {EMPRESA}, {CLIENTE}, {FECHA}, {TOTAL}</small>
                                </div>
                                <div class="col-12">
                                    <label for="plantillaCorreo" class="form-label fw-semibold">Mensaje del Correo</label>
                                    <textarea class="form-control" id="plantillaCorreo" rows="12" required>Estimado(a) {CLIENTE},

Adjunto encontrará su factura electrónica con los siguientes detalles:

• Folio: {FOLIO}
• Fecha de emisión: {FECHA}
• Importe Total: {TOTAL}

Esta factura ha sido generada electrónicamente y tiene plena validez fiscal.

Agradecemos su preferencia.

Atentamente,
{EMPRESA}</textarea>
                                    <small class="text-muted">
                                        Variables disponibles: {FOLIO}, {EMPRESA}, {CLIENTE}, {FECHA}, {TOTAL}, {RFC_CLIENTE}, {RFC_EMPRESA}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-outline-primary btn-lg" onclick="enviarPrueba()">
                                <i class="bi bi-envelope me-2"></i>Enviar Correo de Prueba
                            </button>
                            <button type="button" class="btn btn-primary btn-lg" onclick="guardarConfigCorreo()">
                                <i class="bi bi-check-circle me-2"></i>Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarConfiguracionCorreo();
});

function cargarConfiguracionCorreo() {
    fetch('./core/obtener-config-correo.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.config) {
                const config = data.config;
                document.getElementById('asuntoFactura').value = config.asunto_factura || 'Factura Electrónica - Folio {FOLIO}';
                document.getElementById('plantillaCorreo').value = config.plantilla_correo || '';
            }
        })
        .catch(error => console.error('Error cargando configuración:', error));
}

function enviarPrueba() {
    Swal.fire({
        title: 'Enviar Correo de Prueba',
        input: 'email',
        inputLabel: 'Correo de destino',
        inputPlaceholder: 'ejemplo@correo.com',
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Debe ingresar un correo electrónico';
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                return 'Ingrese un correo válido';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const datos = {
                asunto_factura: document.getElementById('asuntoFactura').value,
                plantilla_correo: document.getElementById('plantillaCorreo').value,
                email_prueba: result.value
            };
            
            Swal.fire({
                title: 'Enviando correo de prueba...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    
                    fetch('./core/enviar-correo-prueba.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(datos)
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Correo Enviado',
                                text: `El correo de prueba se envió correctamente a ${result.value}`,
                                timer: 3000
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al Enviar',
                                text: data.message || 'No se pudo enviar el correo de prueba'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al enviar el correo de prueba'
                        });
                    });
                }
            });
        }
    });
}

function guardarConfigCorreo() {
    const datos = {
        asunto_factura: document.getElementById('asuntoFactura').value,
        plantilla_correo: document.getElementById('plantillaCorreo').value
    };
    
    // Validar campos requeridos
    if (!datos.asunto_factura || !datos.plantilla_correo) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos Requeridos',
            text: 'Complete todos los campos obligatorios'
        });
        return;
    }
    
    Swal.fire({
        title: 'Guardando configuración...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            
            fetch('./core/guardar-config-correo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Configuración Guardada',
                        text: 'La configuración de correo se ha actualizado correctamente.',
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al guardar la configuración'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo conectar con el servidor'
                });
            });
        }
    });
}
</script>