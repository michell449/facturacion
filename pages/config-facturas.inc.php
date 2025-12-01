<?php
// pages/config-facturas.inc.php
?>
<div class="bg-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                        <i class="bi bi-file-earmark-text display-6"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-2">Configuración de Formato de Facturas</h1>
                        <p class="lead mb-0 opacity-90">Personaliza el diseño y formato de tus facturas electrónicas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 py-4">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-palette me-2 text-primary"></i>
                        Personalización del Formato
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="formatoFacturasForm">
                        <!-- Información de la Empresa -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-building me-2"></i>Información de la Empresa
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nombreEmpresa" class="form-label fw-semibold">Nombre de la Empresa</label>
                                        <input type="text" class="form-control" id="nombreEmpresa" placeholder="Nombre completo de la empresa">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rfcEmpresa" class="form-label fw-semibold">RFC de la Empresa</label>
                                        <input type="text" class="form-control" id="rfcEmpresa" placeholder="RFC123456789">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="direccionEmpresa" class="form-label fw-semibold">Dirección Fiscal</label>
                                        <textarea class="form-control" id="direccionEmpresa" rows="3" placeholder="Dirección completa de la empresa"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Diseño de la Factura -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-image me-2"></i>Diseño de la Factura
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Logo de la Empresa</label>
                                        <div class="border rounded-3 p-4 text-center bg-light">
                                            <div id="logoPreview" class="mb-3">
                                                <i class="bi bi-image display-4 text-muted"></i>
                                                <p class="text-muted mb-0">No hay logo seleccionado</p>
                                            </div>
                                            <input type="file" class="form-control" id="logoEmpresa" accept="image/*">
                                            <small class="text-muted">Tamaño recomendado: 300x100px</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="colorPrimario" class="form-label fw-semibold">Color Primario</label>
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <input type="color" class="form-control form-control-color" id="colorPrimario" value="#0d6efd">
                                            <input type="text" class="form-control" value="#0d6efd" readonly>
                                        </div>
                                        <label for="colorSecundario" class="form-label fw-semibold">Color Secundario</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" class="form-control form-control-color" id="colorSecundario" value="#6c757d">
                                            <input type="text" class="form-control" value="#6c757d" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="tipoLetra" class="form-label fw-semibold">Tipo de Letra</label>
                                        <select class="form-select mb-3" id="tipoLetra">
                                            <option value="Arial">Arial</option>
                                            <option value="Helvetica">Helvetica</option>
                                            <option value="Times New Roman">Times New Roman</option>
                                            <option value="Roboto">Roboto</option>
                                        </select>
                                        <label for="tamanoLetra" class="form-label fw-semibold">Tamaño de Letra</label>
                                        <select class="form-select" id="tamanoLetra">
                                            <option value="10">10px</option>
                                            <option value="12" selected>12px</option>
                                            <option value="14">14px</option>
                                            <option value="16">16px</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formato de Números -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-calculator me-2"></i>Formato de Números y Monedas
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="decimales" class="form-label fw-semibold">Decimales</label>
                                        <select class="form-select" id="decimales">
                                            <option value="2" selected>2 decimales</option>
                                            <option value="3">3 decimales</option>
                                            <option value="4">4 decimales</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="separadorMiles" class="form-label fw-semibold">Separador de Miles</label>
                                        <select class="form-select" id="separadorMiles">
                                            <option value="," selected>Coma (,)</option>
                                            <option value=".">Punto (.)</option>
                                            <option value=" ">Espacio ( )</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="monedaPredeterminada" class="form-label fw-semibold">Moneda Predeterminada</label>
                                        <select class="form-select" id="monedaPredeterminada">
                                            <option value="MXN" selected>Peso Mexicano (MXN)</option>
                                            <option value="USD">Dólar Americano (USD)</option>
                                            <option value="EUR">Euro (EUR)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Campos Adicionales -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-plus-circle me-2"></i>Campos Adicionales
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarObservaciones" checked>
                                            <label class="form-check-label fw-semibold" for="mostrarObservaciones">
                                                Mostrar Observaciones
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarCondicionesPago" checked>
                                            <label class="form-check-label fw-semibold" for="mostrarCondicionesPago">
                                                Mostrar Condiciones de Pago
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarCodigoQR" checked>
                                            <label class="form-check-label fw-semibold" for="mostrarCodigoQR">
                                                Mostrar Código QR
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarCadenaOriginal">
                                            <label class="form-check-label fw-semibold" for="mostrarCadenaOriginal">
                                                Mostrar Cadena Original
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vista Previa -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-eye me-2"></i>Vista Previa
                                </h6>
                                <div class="border rounded-3 p-4 bg-light">
                                    <div id="facturaPreview" class="bg-white p-4 rounded shadow-sm">
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <div>
                                                <div class="bg-primary text-white px-3 py-1 rounded mb-2">LOGO</div>
                                                <h6 class="fw-bold">Nombre de la Empresa</h6>
                                                <small class="text-muted">RFC: EMPRESA123456789</small>
                                            </div>
                                            <div class="text-end">
                                                <h4 class="fw-bold text-primary">FACTURA</h4>
                                                <p class="mb-1"><strong>Folio:</strong> A-001</p>
                                                <p class="mb-0"><strong>Fecha:</strong> <?php echo date('d/m/Y'); ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Cliente:</h6>
                                                <p class="mb-1">Ejemplo Cliente S.A. de C.V.</p>
                                                <p class="mb-0 text-muted">RFC: CLIENTE123456789</p>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>Descripción</th>
                                                        <th class="text-end">Cantidad</th>
                                                        <th class="text-end">Precio</th>
                                                        <th class="text-end">Importe</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Producto de Ejemplo</td>
                                                        <td class="text-end">1.00</td>
                                                        <td class="text-end">$100.00</td>
                                                        <td class="text-end">$100.00</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-end mt-3">
                                            <p class="mb-1"><strong>Subtotal: $100.00</strong></p>
                                            <p class="mb-1"><strong>IVA (16%): $16.00</strong></p>
                                            <h5 class="fw-bold text-primary">Total: $116.00</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex gap-3 justify-content-end">
                            <button type="button" class="btn btn-outline-primary" onclick="previsualizarFormato()">
                                <i class="bi bi-eye me-2"></i>Previsualizar
                            </button>
                            <button type="button" class="btn btn-success" onclick="guardarConfiguracion()">
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
    // Cargar configuración actual
    cargarConfiguracionActual();
    
    // Event listeners
    document.getElementById('logoEmpresa').addEventListener('change', previsualizarLogo);
    document.getElementById('colorPrimario').addEventListener('change', actualizarColores);
    document.getElementById('colorSecundario').addEventListener('change', actualizarColores);
    document.getElementById('tipoLetra').addEventListener('change', actualizarVistaPrevia);
    document.getElementById('tamanoLetra').addEventListener('change', actualizarVistaPrevia);
});

function cargarConfiguracionActual() {
    // Aquí cargarías la configuración actual desde el servidor
    fetch('./core/obtener-config-facturas.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Llenar los campos con los datos actuales
                document.getElementById('nombreEmpresa').value = data.config.nombre_empresa || '';
                document.getElementById('rfcEmpresa').value = data.config.rfc_empresa || '';
                document.getElementById('direccionEmpresa').value = data.config.direccion_empresa || '';
                // ... más campos
            }
        })
        .catch(error => console.error('Error cargando configuración:', error));
}

function previsualizarLogo(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('logoPreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-fluid" style="max-height: 80px;">`;
        };
        reader.readAsDataURL(file);
    }
}

function actualizarColores() {
    const colorPrimario = document.getElementById('colorPrimario').value;
    const colorSecundario = document.getElementById('colorSecundario').value;
    
    // Actualizar inputs de texto
    document.querySelector('input[readonly][value^="#"]:first-of-type').value = colorPrimario;
    document.querySelector('input[readonly][value^="#"]:last-of-type').value = colorSecundario;
    
    // Actualizar vista previa
    actualizarVistaPrevia();
}

function actualizarVistaPrevia() {
    const colorPrimario = document.getElementById('colorPrimario').value;
    const tipoLetra = document.getElementById('tipoLetra').value;
    const tamanoLetra = document.getElementById('tamanoLetra').value;
    
    const preview = document.getElementById('facturaPreview');
    preview.style.fontFamily = tipoLetra;
    preview.style.fontSize = tamanoLetra + 'px';
    
    // Actualizar colores en la vista previa
    const elementos = preview.querySelectorAll('.bg-primary, .text-primary, .table-primary');
    elementos.forEach(el => {
        if (el.classList.contains('bg-primary')) {
            el.style.backgroundColor = colorPrimario;
        }
        if (el.classList.contains('text-primary')) {
            el.style.color = colorPrimario;
        }
        if (el.classList.contains('table-primary')) {
            el.style.backgroundColor = colorPrimario + '20';
        }
    });
}

function previsualizarFormato() {
    Swal.fire({
        title: 'Vista Previa de Factura',
        html: document.getElementById('facturaPreview').outerHTML,
        width: '80%',
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
            popup: 'swal-wide'
        }
    });
}

function guardarConfiguracion() {
    const formData = new FormData();
    
    // Recopilar todos los datos del formulario
    const campos = [
        'nombreEmpresa', 'rfcEmpresa', 'direccionEmpresa',
        'colorPrimario', 'colorSecundario', 'tipoLetra', 'tamanoLetra',
        'decimales', 'separadorMiles', 'monedaPredeterminada'
    ];
    
    campos.forEach(campo => {
        formData.append(campo, document.getElementById(campo).value);
    });
    
    // Agregar checkboxes
    const checkboxes = [
        'mostrarObservaciones', 'mostrarCondicionesPago', 
        'mostrarCodigoQR', 'mostrarCadenaOriginal'
    ];
    
    checkboxes.forEach(checkbox => {
        formData.append(checkbox, document.getElementById(checkbox).checked ? '1' : '0');
    });
    
    // Agregar logo si existe
    const logoFile = document.getElementById('logoEmpresa').files[0];
    if (logoFile) {
        formData.append('logo', logoFile);
    }
    
    Swal.fire({
        title: 'Guardando configuración...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            
            fetch('./core/guardar-config-facturas.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Configuración Guardada',
                        text: 'La configuración de facturas se ha actualizado correctamente.',
                        timer: 2000,
                        showConfirmButton: false
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