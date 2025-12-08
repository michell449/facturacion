<meta charset="UTF-8">
<style>
    .logo-upload-container {
        display: flex;
        gap: 20px;
        align-items: center;
        padding: 20px;
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .logo-upload-container:hover {
        border-color: #0d6efd;
        background: #f0f8ff;
    }

    .logo-preview {
        flex-shrink: 0;
        width: 120px;
        height: 120px;
        border-radius: 12px;
        border: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        overflow: hidden;
        position: relative;
    }

    .logo-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 10px;
    }

    .logo-placeholder {
        text-align: center;
        color: #6c757d;
    }

    .logo-controls {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .logo-controls .btn {
        align-self: flex-start;
    }

    @media (max-width: 768px) {
        .logo-upload-container {
            flex-direction: column;
            text-align: center;
        }

        .logo-controls .btn {
            align-self: center;
        }
    }
</style>
<div class="content-wrapper bg-light loaded">
    <div class="container py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-8">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="bi bi-receipt-cutoff display-6 text-primary me-2"></i>
                    Configuración de Facturas
                </h2>
                <p class="text-muted mb-0">Personaliza el formato y la información predeterminada de las facturas.</p>
            </div>
            <div class="col-4 text-end">
                <button type="button" class="btn btn-outline-primary btn-lg rounded-3" onclick="window.history.back()">
                    <i class="bi bi-arrow-left me-2"></i>Regresar
                </button>
            </div>
        </div>
    </div>

    <div class="container py-2">
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
                            <!-- Información de la Empresa Emisora -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-building me-2"></i>Información de la Empresa Emisora
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="sucursalSelect" class="form-label fw-semibold">Sucursal</label>
                                            <select class="form-select" id="sucursalSelect" required>
                                                <option value="">Cargando sucursales...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nombreEmpresa" class="form-label fw-semibold">Nombre de la Empresa</label>
                                            <input type="text" class="form-control" id="nombreEmpresa" placeholder="Nombre completo de la empresa">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="rfcEmpresa" class="form-label fw-semibold">RFC de la Empresa</label>
                                            <input type="text" class="form-control text-uppercase" id="rfcEmpresa" placeholder="RFC123456789" maxlength="13">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="regimenEmisor" class="form-label fw-semibold">Régimen Fiscal del Emisor</label>
                                            <select class="form-select form-select-lg rounded-3" id="regimenFiscal" required>
                                                <option value="">Cargando regímenes fiscales...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cpEmisor" class="form-label fw-semibold">Código Postal Fiscal</label>
                                            <input type="text" class="form-control" id="cpEmisor" placeholder="12345" maxlength="5">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="direccionEmpresa" class="form-label fw-semibold">Dirección Fiscal</label>
                                            <textarea class="form-control" id="direccionEmpresa" rows="2" placeholder="Dirección completa de la empresa"></textarea>
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
                                            <label for="logoSucursal" class="form-label fw-semibold">
                                                Logotipo
                                            </label>
                                            <div class="logo-upload-container border rounded-3 p-4 text-center bg-light">
                                                <div class="logo-preview" id="logoPreview">
                                                    <div class="logo-placeholder">
                                                        <i class="bi bi-image display-5 text-muted"></i>
                                                        <p class="text-muted mb-0">Vista previa del logo</p>
                                                    </div>
                                                </div>
                                                <div class="logo-controls">
                                                    <input type="file" class="form-control" id="logoEmpresa" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="form-text">
                                                <small class="text-muted">
                                                    Formatos: PNG, JPG, JPEG, SVG • Tamaño máximo: 2MB • Recomendado: 200x200px
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="colorPrimario" class="form-label fw-semibold">Color Primario</label>
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <input type="color" class="form-control form-control-color" id="colorPrimario" value="#0d6efd">
                                                <input type="text" class="form-control" id="colorPrimarioText" value="#0d6efd" readonly>
                                            </div>
                                            <label for="colorSecundario" class="form-label fw-semibold">Color Secundario</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" class="form-control form-control-color" id="colorSecundario" value="#6c757d">
                                                <input type="text" class="form-control" id="colorSecundarioText" value="#6c757d" readonly>
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

                            <!-- Serie y Folio -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-123 me-2"></i>Serie y Folio
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="serieFactura" class="form-label fw-semibold">Serie de Factura</label>
                                            <input type="text" class="form-control text-uppercase" id="serieFactura" placeholder="A" maxlength="10" value="A">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="folioInicial" class="form-label fw-semibold">Folio Inicial</label>
                                            <input type="number" class="form-control" id="folioInicial" placeholder="1" value="1" min="1">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="folioActual" class="form-label fw-semibold">Folio Actual</label>
                                            <input type="number" class="form-control" id="folioActual" placeholder="1" value="1" min="1" readonly>
                                            <small class="text-muted">Se incrementa automáticamente</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Textos Personalizados -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-chat-square-text me-2"></i>Textos Personalizados
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="leyendaFactura" class="form-label fw-semibold">Leyenda al pie de factura</label>
                                            <textarea class="form-control" id="leyendaFactura" rows="2" placeholder="Ej: Este documento es una representación impresa de un CFDI"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="condicionesPagoTexto" class="form-label fw-semibold">Condiciones de Pago</label>
                                            <input type="text" class="form-control" id="condicionesPagoTexto" placeholder="Ej: Pago en una sola exhibición">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="observacionesDefault" class="form-label fw-semibold">Observaciones Predeterminadas</label>
                                            <input type="text" class="form-control" id="observacionesDefault" placeholder="Ej: Gracias por su compra">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-flex gap-3 justify-content-end">
                                <button type="button" class="btn btn-outline-secondary" onclick="resetearFormulario()">
                                    <i class="bi bi-arrow-counterclockwise me-2"></i>Restablecer
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="previsualizarPlantilla()">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>Vista Previa Factura
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
</div>

<script>
    let listaSucursales = []; 

    
    async function cargarRegimenesFiscales() {
        try {
            const response = await fetch('core/listar-regimen-fiscal.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();

            if (result.success && result.data) {
                const select = document.getElementById('regimenFiscal');
                select.innerHTML = '<option value="">Selecciona tu régimen fiscal</option>';
                result.data.forEach(regimen => {
                    const option = document.createElement('option');
                    option.value = regimen.codigo;
                    option.textContent = `${regimen.codigo} - ${regimen.descripcion}`;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error al obtener regímenes fiscales:', error);
        }
    }

    async function cargarSucursales() {
        try {
            const response = await fetch('core/consultar-sucursales.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();
            const select = document.getElementById('sucursalSelect');

            if (result.success && result.data) {
                // GUARDAMOS LOS DATOS EN LA VARIABLE GLOBAL
                listaSucursales = result.data;

                select.innerHTML = '<option value="">Selecciona una sucursal</option>';
                result.data.forEach(sucursal => {
                    const option = document.createElement('option');
                    option.value = sucursal.id_empresa;
                    option.textContent = sucursal.nombre || sucursal.razon_social;
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = '<option value="">No hay sucursales registradas</option>';
            }
        } catch (error) {
            console.error('Error al cargar sucursales:', error);
            document.getElementById('sucursalSelect').innerHTML = '<option value="">Error de conexión</option>';
        }
    }

    document.getElementById('sucursalSelect').addEventListener('change', function() {
        const idSeleccionado = this.value;

        if (!idSeleccionado) {
            limpiarFormulario();
            return;
        }

        // Buscamos en la variable global (sin hacer fetch de nuevo)
        const sucursal = listaSucursales.find(s => s.id_empresa == idSeleccionado);

        if (sucursal) {
            console.log("Datos cargados:", sucursal);

            // Rellenar Textos
            document.getElementById('nombreEmpresa').value = sucursal.razon_social || '';
            document.getElementById('rfcEmpresa').value = sucursal.rfc || '';
            document.getElementById('cpEmisor').value = sucursal.cp || '';

            // Dirección Completa
            let dirCompleta = sucursal.direccion || '';
            if (sucursal.colonia) dirCompleta += ', ' + sucursal.colonia;
            document.getElementById('direccionEmpresa').value = dirCompleta;

            // Régimen Fiscal
            if (sucursal.reg_fiscal) {
                document.getElementById('regimenFiscal').value = sucursal.reg_fiscal;
            }

            // Manejo del Logo (Evita el error 404 si no hay logo)
            const logoPreview = document.getElementById('logoPreview');
            if (sucursal.logo && sucursal.logo.trim() !== "") {
                // Crear ruta completa del logo
                let rutaLogo = sucursal.logo;
                
                // Si la ruta no incluye 'uploads/', agregarla
                if (!rutaLogo.includes('uploads/')) {
                    rutaLogo = `uploads/logos/${sucursal.logo}`;
                }
                
                // Crear imagen y validar si existe antes de mostrarla
                const img = new Image();
                img.onload = function() {
                    logoPreview.innerHTML = `<img src="${rutaLogo}" alt="Logo Empresa" />`;
                };
                img.onerror = function() {
                    // Si la imagen no carga, mostrar placeholder
                    logoPreview.innerHTML = `
                        <div class="logo-placeholder">
                            <i class="bi bi-image display-5 text-muted"></i>
                            <p class="text-muted mb-0">Logo no disponible</p>
                        </div>`;
                };
                img.src = rutaLogo;
            } else {
                // Placeholder si no hay logo
                logoPreview.innerHTML = `
                    <div class="logo-placeholder">
                        <i class="bi bi-image display-5 text-muted"></i>
                        <p class="text-muted mb-0">Sin logo registrado</p>
                    </div>`;
            }
        }
    });

    function limpiarFormulario() {
        // Limpia inputs
        ['nombreEmpresa', 'rfcEmpresa', 'cpEmisor', 'direccionEmpresa', 'leyendaFactura', 'condicionesPagoTexto', 'observacionesDefault'].forEach(id => {
            document.getElementById(id).value = '';
        });

        // Resetea selects y colores
        document.getElementById('regimenFiscal').value = '';
        document.getElementById('colorPrimario').value = '#0d6efd';
        document.getElementById('colorPrimarioText').value = '#0d6efd';
        document.getElementById('colorSecundario').value = '#6c757d';
        document.getElementById('colorSecundarioText').value = '#6c757d';

        // Resetea Logo
        document.getElementById('logoPreview').innerHTML = `
            <div class="logo-placeholder">
                <i class="bi bi-image display-5 text-muted"></i>
                <p class="text-muted mb-0">Vista previa del logo</p>
            </div>`;
    }

    function resetearFormulario() {
        Swal.fire({
            title: '¿Restablecer?',
            text: 'Se borrarán los datos del formulario',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, limpiar'
        }).then((result) => {
            if (result.isConfirmed) {
                limpiarFormulario();
                document.getElementById('sucursalSelect').value = ""; // Reset del select
            }
        });
    }

    function setupLogoHandler() {
        const logoInput = document.getElementById('logoEmpresa');
        const logoPreview = document.getElementById('logoPreview');
        if (!logoInput) return;

        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    logoPreview.innerHTML = `<img src="${event.target.result}" alt="Logo preview" />`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function setupColorHandlers() {
        const cp = document.getElementById('colorPrimario');
        const cs = document.getElementById('colorSecundario');
        if (cp) cp.addEventListener('input', function() {
            document.getElementById('colorPrimarioText').value = this.value;
        });
        if (cs) cs.addEventListener('input', function() {
            document.getElementById('colorSecundarioText').value = this.value;
        });
    }

    async function previsualizarPlantilla() {
        try {
            const response = await fetch('uploads/templates/template-factura.html');
            if (!response.ok) throw new Error('No se pudo cargar la plantilla HTML');
            let html = await response.text();

            // Datos actuales del DOM
            const datos = {
                COLOR_PRIMARIO: document.getElementById('colorPrimario').value,
                COLOR_SECUNDARIO: document.getElementById('colorSecundario').value,
                TIPO_LETRA: document.getElementById('tipoLetra').value,
                TAMANO_LETRA: document.getElementById('tamanoLetra').value + 'px',
                EMISOR_NOMBRE: document.getElementById('nombreEmpresa').value,
                EMISOR_RFC: document.getElementById('rfcEmpresa').value,
                EMISOR_REGIMEN: document.getElementById('regimenFiscal').selectedOptions[0]?.text || '',
                EMISOR_DOMICILIO: document.getElementById('direccionEmpresa').value,
                EMISOR_CP: document.getElementById('cpEmisor').value,
                LOGO_URL: '', // Se llena abajo
                // Datos Dummy
                UUID: 'A1B2C3D4-E5F6-7890-ABCD-EF1234567890',
                FECHA_EMISION: new Date().toLocaleString('es-MX'),
                SERIE: document.getElementById('serieFactura').value,
                FOLIO: document.getElementById('folioInicial').value,
                TOTAL_LETRA: 'CIEN PESOS 00/100 M.N.'
            };

            // Obtener logo actual (ya sea de BD o recién subido)
            const imgTag = document.getElementById('logoPreview').querySelector('img');
            if (imgTag && imgTag.src) datos.LOGO_URL = imgTag.src;

            // Reemplazo
            Object.keys(datos).forEach(key => {
                html = html.replace(new RegExp(key, 'g'), datos[key]);
            });

            const win = window.open('', '_blank', 'width=900,height=1200,scrollbars=yes');
            if (win) {
                win.document.write(html);
                win.document.close();
            } else {
                Swal.fire('Bloqueado', 'Permite pop-ups para ver la factura', 'warning');
            }
        } catch (e) {
            Swal.fire('Error', e.message, 'error');
        }
    }

    async function guardarConfiguracion() {
        // Validación de sucursal
        const sucursalId = document.getElementById('sucursalSelect').value;
        if (!sucursalId) return Swal.fire('Error', 'Selecciona una sucursal', 'warning');

        // Construcción del FormData
        const formData = new FormData();
        formData.append('sucursalId', sucursalId);

        // Agregamos todos los campos del formulario por ID
        const campos = ['nombreEmpresa', 'rfcEmpresa', 'regimenFiscal', 'cpEmisor', 'direccionEmpresa',
            'colorPrimario', 'colorSecundario', 'tipoLetra', 'tamanoLetra',
            'serieFactura', 'folioInicial', 'leyendaFactura', 'condicionesPagoTexto', 'observacionesDefault'
        ];

        campos.forEach(id => formData.append(id, document.getElementById(id).value));

        // Logo (solo si se subió uno nuevo)
        const logoFile = document.getElementById('logoEmpresa').files[0];
        if (logoFile) formData.append('logo', logoFile);

        Swal.showLoading();

        try {
            const res = await fetch('core/guardar-config-facturas.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                Swal.fire('Guardado', data.message, 'success');
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'No se pudo guardar la configuración', 'error');
            console.error(e);
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        await Promise.all([cargarRegimenesFiscales(), cargarSucursales()]);
        setupLogoHandler();
        setupColorHandlers();
    });
</script>