<?php
// pages/config-correo.inc.php
?>
<div class="bg-success text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                        <i class="bi bi-envelope-at display-6"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-2">Configuración de Correo Electrónico</h1>
                        <p class="lead mb-0 opacity-90">Configura el servidor de correo para el envío de facturas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-server me-2 text-success"></i>
                        Configuración del Servidor SMTP
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="correoConfigForm">
                        <!-- Configuración SMTP -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="bi bi-gear me-2"></i>Configuración SMTP
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="smtpHost" class="form-label fw-semibold">Servidor SMTP</label>
                                        <input type="text" class="form-control" id="smtpHost" placeholder="smtp.gmail.com">
                                        <small class="text-muted">Ejemplo: smtp.gmail.com, smtp.outlook.com</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="smtpPort" class="form-label fw-semibold">Puerto</label>
                                        <select class="form-select" id="smtpPort">
                                            <option value="587" selected>587 (TLS)</option>
                                            <option value="465">465 (SSL)</option>
                                            <option value="25">25 (Sin cifrado)</option>
                                            <option value="2525">2525 (Alternativo)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="smtpSeguridad" class="form-label fw-semibold">Seguridad</label>
                                        <select class="form-select" id="smtpSeguridad">
                                            <option value="tls" selected>TLS</option>
                                            <option value="ssl">SSL</option>
                                            <option value="none">Ninguna</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="smtpAuth" class="form-label fw-semibold">Autenticación</label>
                                        <select class="form-select" id="smtpAuth">
                                            <option value="true" selected>Requerida</option>
                                            <option value="false">No requerida</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Credenciales -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="bi bi-key me-2"></i>Credenciales de Acceso
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="smtpUsuario" class="form-label fw-semibold">Usuario/Email</label>
                                        <input type="email" class="form-control" id="smtpUsuario" placeholder="facturacion@empresa.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="smtpPassword" class="form-label fw-semibold">Contraseña</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="smtpPassword" placeholder="••••••••">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('smtpPassword')">
                                                <i class="bi bi-eye" id="smtpPassword-icon"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Para Gmail, usa una "Contraseña de aplicación"</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Configuración del Remitente -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="bi bi-person-badge me-2"></i>Información del Remitente
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="remitenteNombre" class="form-label fw-semibold">Nombre del Remitente</label>
                                        <input type="text" class="form-control" id="remitenteNombre" placeholder="Sistema de Facturación">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="remitenteEmail" class="form-label fw-semibold">Email del Remitente</label>
                                        <input type="email" class="form-control" id="remitenteEmail" placeholder="noreply@empresa.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="emailRespuesta" class="form-label fw-semibold">Email de Respuesta</label>
                                        <input type="email" class="form-control" id="emailRespuesta" placeholder="soporte@empresa.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="emailCopia" class="form-label fw-semibold">Copia Oculta (BCC)</label>
                                        <input type="email" class="form-control" id="emailCopia" placeholder="admin@empresa.com">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Plantilla de Correo -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="bi bi-file-text me-2"></i>Plantilla de Correo
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="asuntoFactura" class="form-label fw-semibold">Asunto del Correo</label>
                                        <input type="text" class="form-control" id="asuntoFactura" 
                                               placeholder="Factura Electrónica - Folio: {FOLIO}" 
                                               value="Factura Electrónica - Folio: {FOLIO}">
                                        <small class="text-muted">Variables disponibles: {FOLIO}, {EMPRESA}, {CLIENTE}, {FECHA}, {TOTAL}</small>
                                    </div>
                                    <div class="col-12">
                                        <label for="plantillaCorreo" class="form-label fw-semibold">Mensaje del Correo</label>
                                        <textarea class="form-control" id="plantillaCorreo" rows="8" placeholder="Estimado {CLIENTE}...">Estimado {CLIENTE},

Adjunto encontrará su factura electrónica con los siguientes datos:

• Folio: {FOLIO}
• Fecha: {FECHA}
• Importe Total: {TOTAL}

Esta factura ha sido generada electrónicamente y tiene la misma validez que una factura impresa.

Agradecemos su preferencia.

Atentamente,
{EMPRESA}</textarea>
                                        <small class="text-muted">Variables disponibles: {FOLIO}, {EMPRESA}, {CLIENTE}, {FECHA}, {TOTAL}, {RFC_CLIENTE}, {RFC_EMPRESA}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex gap-3 justify-content-end">
                            <button type="button" class="btn btn-outline-success" onclick="probarConexion()">
                                <i class="bi bi-wifi me-2"></i>Probar Conexión
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="enviarPrueba()">
                                <i class="bi bi-envelope me-2"></i>Enviar Prueba
                            </button>
                            <button type="button" class="btn btn-success" onclick="guardarConfigCorreo()">
                                <i class="bi bi-check-circle me-2"></i>Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Estado y Ayuda -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-info-circle me-2 text-info"></i>
                        Estado de Configuración
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div id="estadoConexion" class="mb-3">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>No configurado</strong><br>
                            <small>Configure el servidor SMTP para enviar facturas</small>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-2">Estadísticas de Envío</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <small><strong>Enviados hoy:</strong> <span id="enviadosHoy">0</span></small>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-clock text-warning me-2"></i>
                            <small><strong>Pendientes:</strong> <span id="pendientesEnvio">0</span></small>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-x-circle text-danger me-2"></i>
                            <small><strong>Fallos:</strong> <span id="fallosEnvio">0</span></small>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 py-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-question-circle me-2 text-primary"></i>
                        Configuraciones Populares
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <strong class="text-primary">Gmail</strong>
                        <small class="d-block text-muted">
                            Host: smtp.gmail.com<br>
                            Puerto: 587, TLS<br>
                            Requiere contraseña de aplicación
                        </small>
                    </div>
                    <div class="mb-3">
                        <strong class="text-primary">Outlook/Hotmail</strong>
                        <small class="d-block text-muted">
                            Host: smtp-mail.outlook.com<br>
                            Puerto: 587, TLS
                        </small>
                    </div>
                    <div class="mb-3">
                        <strong class="text-primary">Yahoo</strong>
                        <small class="d-block text-muted">
                            Host: smtp.mail.yahoo.com<br>
                            Puerto: 587, TLS
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarConfiguracionCorreo();
    cargarEstadisticasEnvio();
});

function cargarConfiguracionCorreo() {
    fetch('./core/obtener-config-correo.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Llenar campos con configuración actual
                const config = data.config;
                document.getElementById('smtpHost').value = config.smtp_host || '';
                document.getElementById('smtpPort').value = config.smtp_port || '587';
                document.getElementById('smtpSeguridad').value = config.smtp_seguridad || 'tls';
                document.getElementById('smtpAuth').value = config.smtp_auth || 'true';
                document.getElementById('smtpUsuario').value = config.smtp_usuario || '';
                document.getElementById('remitenteNombre').value = config.remitente_nombre || '';
                document.getElementById('remitenteEmail').value = config.remitente_email || '';
                document.getElementById('emailRespuesta').value = config.email_respuesta || '';
                document.getElementById('emailCopia').value = config.email_copia || '';
                document.getElementById('asuntoFactura').value = config.asunto_factura || '';
                document.getElementById('plantillaCorreo').value = config.plantilla_correo || '';
                
                // Actualizar estado de conexión
                actualizarEstadoConexion(config.configurado);
            }
        })
        .catch(error => console.error('Error cargando configuración:', error));
}

function cargarEstadisticasEnvio() {
    fetch('./core/estadisticas-envio.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('enviadosHoy').textContent = data.enviados_hoy || '0';
                document.getElementById('pendientesEnvio').textContent = data.pendientes || '0';
                document.getElementById('fallosEnvio').textContent = data.fallos || '0';
            }
        })
        .catch(error => console.error('Error cargando estadísticas:', error));
}

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

function actualizarEstadoConexion(configurado) {
    const estadoDiv = document.getElementById('estadoConexion');
    
    if (configurado) {
        estadoDiv.innerHTML = `
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Configurado correctamente</strong><br>
                <small>El servidor SMTP está listo para enviar facturas</small>
            </div>
        `;
    } else {
        estadoDiv.innerHTML = `
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>No configurado</strong><br>
                <small>Configure el servidor SMTP para enviar facturas</small>
            </div>
        `;
    }
}

function probarConexion() {
    const datos = recopilarDatos();
    
    Swal.fire({
        title: 'Probando conexión SMTP...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            
            fetch('./core/probar-smtp.php', {
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
                        title: 'Conexión Exitosa',
                        text: 'El servidor SMTP está configurado correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    actualizarEstadoConexion(true);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Conexión',
                        text: data.message || 'No se pudo conectar al servidor SMTP'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al probar la conexión'
                });
            });
        }
    });
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
            const datos = recopilarDatos();
            datos.email_prueba = result.value;
            
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
                                timer: 3000,
                                showConfirmButton: false
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
                        console.error('Error:', error);
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

function recopilarDatos() {
    return {
        smtp_host: document.getElementById('smtpHost').value,
        smtp_port: document.getElementById('smtpPort').value,
        smtp_seguridad: document.getElementById('smtpSeguridad').value,
        smtp_auth: document.getElementById('smtpAuth').value,
        smtp_usuario: document.getElementById('smtpUsuario').value,
        smtp_password: document.getElementById('smtpPassword').value,
        remitente_nombre: document.getElementById('remitenteNombre').value,
        remitente_email: document.getElementById('remitenteEmail').value,
        email_respuesta: document.getElementById('emailRespuesta').value,
        email_copia: document.getElementById('emailCopia').value,
        asunto_factura: document.getElementById('asuntoFactura').value,
        plantilla_correo: document.getElementById('plantillaCorreo').value
    };
}

function guardarConfigCorreo() {
    const datos = recopilarDatos();
    
    // Validar campos requeridos
    if (!datos.smtp_host || !datos.smtp_usuario || !datos.remitente_email) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos Requeridos',
            text: 'Complete al menos el servidor SMTP, usuario y email del remitente'
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
                        timer: 2000,
                        showConfirmButton: false
                    });
                    actualizarEstadoConexion(true);
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
                console.error('Error:', error);
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