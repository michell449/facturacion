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

                            <!-- Valores Predeterminados para Facturas -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-gear me-2"></i>Valores Predeterminados
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="usoCfdi" class="form-label fw-semibold">Uso de CFDI</label>
                                            <select class="form-select" id="usoCfdi">
                                                <option value="">Cargando usos de CFDI...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="formaPago" class="form-label fw-semibold">Forma de Pago</label>
                                            <select class="form-select" id="formaPago">
                                                <option value="">Cargando formas de pago...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="metodoPagoDefault" class="form-label fw-semibold">Método de Pago Predeterminado</label>
                                            <select class="form-select" id="metodoPagoDefault">
                                                <option value="PUE" selected>PUE - Pago en una sola exhibición</option>
                                                <option value="PPD">PPD - Pago en parcialidades o diferido</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="monedaDefault" class="form-label fw-semibold">Moneda Predeterminada</label>
                                            <select class="form-select" id="monedaDefault">
                                                <option value="MXN" selected>MXN - Peso Mexicano</option>
                                                <option value="USD">USD - Dólar Americano</option>
                                                <option value="EUR">EUR - Euro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tipoComprobante" class="form-label fw-semibold">Tipo de Comprobante</label>
                                            <select class="form-select" id="tipoComprobante">
                                                <option value="">Cargando tipos de comprobante...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="exportacionDefault" class="form-label fw-semibold">Exportación</label>
                                            <select class="form-select" id="exportacionDefault">
                                                <option value="01" selected>01 - No aplica</option>
                                                <option value="02">02 - Definitiva</option>
                                                <option value="03">03 - Temporal</option>
                                            </select>
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

                            <!-- Campos a Mostrar en el PDF -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bi bi-eye me-2"></i>Elementos a Mostrar en el PDF
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="mostrarLogo" checked>
                                                <label class="form-check-label fw-semibold" for="mostrarLogo">
                                                    Mostrar Logo
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="mostrarSelloDigital" checked>
                                                <label class="form-check-label fw-semibold" for="mostrarSelloDigital">
                                                    Mostrar Sello Digital
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="mostrarObservaciones" checked>
                                                <label class="form-check-label fw-semibold" for="mostrarObservaciones">
                                                    Mostrar Observaciones
                                                </label>
                                            </div>
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
                                <button type="button" class="btn btn-outline-primary" onclick="abrirModalPreview()">
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
</div>

<!-- Modal de Vista Previa de Factura -->
<div class="modal fade" id="modalPreviewFactura" tabindex="-1" aria-labelledby="modalPreviewFacturaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalPreviewFacturaLabel">
                    <i class="bi bi-file-earmark-pdf text-primary me-2"></i>Vista Previa de Factura
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Contenedor de la factura con estilos dinámicos -->
                <div id="facturaPreviewModal" class="bg-white border rounded-3 shadow-sm mx-auto" style="max-width: 800px;">
                    
                    <!-- Header de la Factura -->
                    <div class="p-4 border-bottom" id="facturaHeader" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <!-- Logo -->
                                <div id="modalPreviewLogo" class="mb-3">
                                    <div class="d-inline-block px-4 py-3 rounded" style="background-color: var(--color-primario, #0d6efd);">
                                        <span class="text-white fw-bold">LOGO</span>
                                    </div>
                                </div>
                                <!-- Datos del Emisor -->
                                <h4 class="fw-bold mb-1" id="modalPreviewNombreEmpresa" style="color: var(--color-primario, #0d6efd);">Nombre de la Empresa</h4>
                                <p class="mb-1 text-muted"><i class="bi bi-building me-1"></i><span id="modalPreviewRFC">RFC: ---</span></p>
                                <p class="mb-1 text-muted"><i class="bi bi-geo-alt me-1"></i><span id="modalPreviewDireccion">Dirección Fiscal</span></p>
                                <p class="mb-0 text-muted"><i class="bi bi-mailbox me-1"></i>C.P. <span id="modalPreviewCP">---</span></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="d-inline-block text-start p-3 rounded-3" style="background-color: var(--color-primario, #0d6efd); min-width: 200px;">
                                    <h3 class="text-white fw-bold mb-2">FACTURA</h3>
                                    <p class="text-white-50 mb-1">Serie: <span class="text-white fw-bold" id="modalPreviewSerie">A</span></p>
                                    <p class="text-white-50 mb-1">Folio: <span class="text-white fw-bold" id="modalPreviewFolio">001</span></p>
                                    <p class="text-white-50 mb-0">Fecha: <span class="text-white fw-bold" id="modalPreviewFecha"><?php echo date('d/m/Y'); ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Receptor -->
                    <div class="p-4 border-bottom bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-uppercase mb-3" style="color: var(--color-secundario, #6c757d);">
                                    <i class="bi bi-person-fill me-2"></i>Receptor
                                </h6>
                                <p class="fw-bold mb-1">Cliente de Ejemplo S.A. de C.V.</p>
                                <p class="text-muted mb-1">RFC: XAXX010101000</p>
                                <p class="text-muted mb-0">C.P.: 12345</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="mt-3 mt-md-0">
                                    <p class="mb-1"><span class="text-muted">Uso CFDI:</span> <strong id="modalPreviewUsoCFDI">G03 - Gastos en general</strong></p>
                                    <p class="mb-1"><span class="text-muted">Forma de Pago:</span> <strong id="modalPreviewFormaPago">04 - Tarjeta de crédito</strong></p>
                                    <p class="mb-1"><span class="text-muted">Método de Pago:</span> <strong id="modalPreviewMetodoPago">PUE</strong></p>
                                    <p class="mb-0"><span class="text-muted">Moneda:</span> <strong id="modalPreviewMoneda">MXN</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Conceptos -->
                    <div class="p-4">
                        <h6 class="fw-bold text-uppercase mb-3" style="color: var(--color-secundario, #6c757d);">
                            <i class="bi bi-list-ul me-2"></i>Conceptos
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="modalTablaConceptos">
                                <thead style="background-color: var(--color-primario, #0d6efd);">
                                    <tr class="text-white">
                                        <th style="width: 15%;">Clave SAT</th>
                                        <th>Descripción</th>
                                        <th class="text-center" style="width: 10%;">Cant.</th>
                                        <th class="text-center" style="width: 10%;">Unidad</th>
                                        <th class="text-end" style="width: 15%;">P. Unitario</th>
                                        <th class="text-end" style="width: 15%;">Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-light text-dark border">01010101</span></td>
                                        <td>Producto de ejemplo para demostración</td>
                                        <td class="text-center">2.00</td>
                                        <td class="text-center">PZA</td>
                                        <td class="text-end">$500.00</td>
                                        <td class="text-end fw-bold">$1,000.00</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-light text-dark border">84111506</span></td>
                                        <td>Servicio de consultoría</td>
                                        <td class="text-center">1.00</td>
                                        <td class="text-center">E48</td>
                                        <td class="text-end">$2,500.00</td>
                                        <td class="text-end fw-bold">$2,500.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Totales -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded-3" id="modalObservacionesContainer">
                                    <h6 class="fw-bold mb-2" style="color: var(--color-secundario, #6c757d);">
                                        <i class="bi bi-chat-left-text me-2"></i>Observaciones
                                    </h6>
                                    <p class="mb-0 text-muted" id="modalPreviewObservaciones">Gracias por su compra</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded-3">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="text-end text-muted">Subtotal:</td>
                                            <td class="text-end fw-bold" style="width: 120px;">$3,500.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end text-muted">IVA (16%):</td>
                                            <td class="text-end fw-bold">$560.00</td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="text-end"><h5 class="mb-0 fw-bold">Total:</h5></td>
                                            <td class="text-end">
                                                <h5 class="mb-0 fw-bold" style="color: var(--color-primario, #0d6efd);">$4,060.00</h5>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pie de Factura (Sellos y QR) -->
                    <div class="p-4 border-top" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="row">
                            <!-- QR Code -->
                            <div class="col-md-3 text-center mb-3 mb-md-0" id="modalQRContainer">
                                <div class="bg-white p-3 rounded-3 border d-inline-block">
                                    <i class="bi bi-qr-code" style="font-size: 80px; color: var(--color-primario, #0d6efd);"></i>
                                </div>
                                <small class="d-block text-muted mt-2">Verificación SAT</small>
                            </div>
                            <!-- Sellos Digitales -->
                            <div class="col-md-9" id="modalSelloContainer">
                                <div class="mb-3">
                                    <h6 class="fw-bold mb-2" style="color: var(--color-secundario, #6c757d);">
                                        <i class="bi bi-shield-check me-2"></i>Sello Digital del CFDI
                                    </h6>
                                    <p class="text-muted mb-0 small text-break" style="font-family: monospace; font-size: 0.7rem; line-height: 1.4;">
                                        ||1.1|aaaa-bbbb-cccc-dddd|2024-01-15T12:30:00|AAA010101AAA|XAXX010101000|4060.00|MXN|1|I|PUE|12345|...||
                                    </p>
                                </div>
                                <div class="border-top pt-3">
                                    <p class="mb-0 text-muted small" id="modalPreviewLeyenda">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Este documento es una representación impresa de un CFDI
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Condiciones de Pago -->
                    <div class="p-3 text-center border-top" style="background-color: var(--color-primario, #0d6efd);">
                        <p class="mb-0 text-white small" id="modalPreviewCondiciones">
                            <i class="bi bi-credit-card me-2"></i>Condiciones de pago: Pago en una sola exhibición
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-outline-primary" onclick="imprimirPreview()">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variable global para almacenar la configuración actual
    let configuracionActual = null;

  
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
            document.getElementById('regimenFiscal').innerHTML = '<option value="">Error al cargar regímenes</option>';
        }
    }

    async function cargarUsosCFDI() {
        try {
            const response = await fetch('core/listar-uso-cfdi.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();

            if (result.success && result.data) {
                const select = document.getElementById('usoCfdi');
                select.innerHTML = '<option value="">Selecciona el uso de CFDI</option>';
                result.data.forEach(uso => {
                    const option = document.createElement('option');
                    option.value = uso.codigo;
                    option.textContent = `${uso.codigo} - ${uso.descripcion}`;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error al obtener usos de CFDI:', error);
            document.getElementById('usoCfdi').innerHTML = '<option value="">Error al cargar usos de CFDI</option>';
        }
    }

    async function cargarFormaPago() {
        try {
            const response = await fetch('core/listar-formas-pago.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();

            if (result.success && result.data) {
                const select = document.getElementById('formaPago');
                select.innerHTML = '<option value="">Selecciona la forma de pago</option>';
                result.data.forEach(forma => {
                    const option = document.createElement('option');
                    option.value = forma.clave;
                    option.textContent = `${forma.clave} - ${forma.description}`;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error al obtener formas de pago:', error);
            document.getElementById('formaPago').innerHTML = '<option value="">Error al cargar formas de pago</option>';
        }
    }

    async function cargarTiposComprobante() {
        try {
            const response = await fetch('core/listar-tipos-comprobante.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();

            if (result.success && result.data) {
                const select = document.getElementById('tipoComprobante');
                select.innerHTML = '';
                result.data.forEach(tipo => {
                    const option = document.createElement('option');
                    option.value = tipo.clave;
                    option.textContent = `${tipo.clave} - ${tipo.description}`;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error al obtener tipos de comprobante:', error);
            document.getElementById('tipoComprobante').innerHTML = '<option value="">Error al cargar tipos de comprobante</option>';
        }
    }

    // ============================================
    // FUNCIÓN PARA CARGAR CONFIGURACIÓN ACTUAL
    // ============================================
    
    async function cargarConfiguracionActual() {
        try {
            const response = await fetch('core/obtener-config-facturas.php');
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();

            if (result.success && result.data) {
                configuracionActual = result.data;
                aplicarConfiguracionAlFormulario(result.data);
            }
        } catch (error) {
            console.error('Error al cargar configuración:', error);
        }
    }

    function aplicarConfiguracionAlFormulario(config) {
        // Información de la Empresa
        document.getElementById('nombreEmpresa').value = config.nombreEmpresa || '';
        document.getElementById('rfcEmpresa').value = config.rfcEmpresa || '';
        document.getElementById('cpEmisor').value = config.cpEmisor || '';
        document.getElementById('direccionEmpresa').value = config.direccionEmpresa || '';

        // Selects (esperamos a que se carguen los catálogos)
        setTimeout(() => {
            if (config.regimenFiscal) document.getElementById('regimenFiscal').value = config.regimenFiscal;
            if (config.usoCfdi) document.getElementById('usoCfdi').value = config.usoCfdi;
            if (config.formaPago) document.getElementById('formaPago').value = config.formaPago;
            if (config.tipoComprobante) document.getElementById('tipoComprobante').value = config.tipoComprobante;
        }, 1000);

        // Método de pago, moneda, exportación
        document.getElementById('metodoPagoDefault').value = config.metodoPagoDefault || 'PUE';
        document.getElementById('monedaDefault').value = config.monedaDefault || 'MXN';
        document.getElementById('exportacionDefault').value = config.exportacionDefault || '01';

        // Diseño
        document.getElementById('colorPrimario').value = config.colorPrimario || '#0d6efd';
        document.getElementById('colorPrimarioText').value = config.colorPrimario || '#0d6efd';
        document.getElementById('colorSecundario').value = config.colorSecundario || '#6c757d';
        document.getElementById('colorSecundarioText').value = config.colorSecundario || '#6c757d';
        document.getElementById('tipoLetra').value = config.tipoLetra || 'Arial';
        document.getElementById('tamanoLetra').value = config.tamanoLetra || 12;

        // Logo existente
        if (config.logoEmpresa) {
            const logoPreview = document.getElementById('logoPreview');
            logoPreview.innerHTML = `<img src="${config.logoEmpresa}" alt="Logo de la empresa" />`;
        }

        // Serie y Folio
        document.getElementById('serieFactura').value = config.serieFactura || 'A';
        document.getElementById('folioInicial').value = config.folioInicial || 1;
        document.getElementById('folioActual').value = config.folioActual || 0;

        // Checkboxes
        document.getElementById('mostrarLogo').checked = config.mostrarLogo == 1;
        document.getElementById('mostrarSelloDigital').checked = config.mostrarSelloDigital == 1;
        document.getElementById('mostrarObservaciones').checked = config.mostrarObservaciones == 1;

        // Textos personalizados
        document.getElementById('leyendaFactura').value = config.leyendaFactura || '';
        document.getElementById('condicionesPagoTexto').value = config.condicionesPagoTexto || '';
        document.getElementById('observacionesDefault').value = config.observacionesDefault || '';

        // Actualizar vista previa
        actualizarVistaPrevia();
    }

    // ============================================
    // FUNCIÓN PARA GUARDAR CONFIGURACIÓN
    // ============================================
    
    async function guardarConfiguracion() {
        // Validaciones básicas
        const rfcEmpresa = document.getElementById('rfcEmpresa').value.trim();
        if (rfcEmpresa && (rfcEmpresa.length < 12 || rfcEmpresa.length > 13)) {
            Swal.fire({
                icon: 'warning',
                title: 'RFC inválido',
                text: 'El RFC debe tener 12 o 13 caracteres'
            });
            return;
        }

        const cpEmisor = document.getElementById('cpEmisor').value.trim();
        if (cpEmisor && cpEmisor.length !== 5) {
            Swal.fire({
                icon: 'warning',
                title: 'Código Postal inválido',
                text: 'El código postal debe tener 5 dígitos'
            });
            return;
        }

        // Crear FormData para enviar datos incluyendo archivo
        const formData = new FormData();
        
        // Información de la empresa
        formData.append('nombreEmpresa', document.getElementById('nombreEmpresa').value);
        formData.append('rfcEmpresa', document.getElementById('rfcEmpresa').value);
        formData.append('regimenFiscal', document.getElementById('regimenFiscal').value);
        formData.append('cpEmisor', document.getElementById('cpEmisor').value);
        formData.append('direccionEmpresa', document.getElementById('direccionEmpresa').value);
        
        // Valores predeterminados
        formData.append('usoCfdi', document.getElementById('usoCfdi').value);
        formData.append('formaPago', document.getElementById('formaPago').value);
        formData.append('metodoPagoDefault', document.getElementById('metodoPagoDefault').value);
        formData.append('monedaDefault', document.getElementById('monedaDefault').value);
        formData.append('tipoComprobante', document.getElementById('tipoComprobante').value);
        formData.append('exportacionDefault', document.getElementById('exportacionDefault').value);
        
        // Diseño
        formData.append('colorPrimario', document.getElementById('colorPrimario').value);
        formData.append('colorSecundario', document.getElementById('colorSecundario').value);
        formData.append('tipoLetra', document.getElementById('tipoLetra').value);
        formData.append('tamanoLetra', document.getElementById('tamanoLetra').value);
        
        // Serie y folio
        formData.append('serieFactura', document.getElementById('serieFactura').value);
        formData.append('folioInicial', document.getElementById('folioInicial').value);
        
        // Checkboxes (enviar como 1 o 0)
        formData.append('mostrarLogo', document.getElementById('mostrarLogo').checked ? 1 : 0);
        formData.append('mostrarSelloDigital', document.getElementById('mostrarSelloDigital').checked ? 1 : 0);
        formData.append('mostrarObservaciones', document.getElementById('mostrarObservaciones').checked ? 1 : 0);
        
        // Textos personalizados
        formData.append('leyendaFactura', document.getElementById('leyendaFactura').value);
        formData.append('condicionesPagoTexto', document.getElementById('condicionesPagoTexto').value);
        formData.append('observacionesDefault', document.getElementById('observacionesDefault').value);
        
        // Logo (si se seleccionó uno nuevo)
        const logoInput = document.getElementById('logoEmpresa');
        if (logoInput.files.length > 0) {
            formData.append('logo', logoInput.files[0]);
        }

        // Mostrar loading
        Swal.fire({
            title: 'Guardando configuración...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch('core/guardar-config-facturas.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Guardado!',
                    text: result.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                // Recargar configuración
                cargarConfiguracionActual();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message
                });
            }
        } catch (error) {
            console.error('Error al guardar configuración:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al guardar la configuración'
            });
        }
    }

    // ============================================
    // FUNCIÓN PARA ACTUALIZAR VISTA PREVIA EN MODAL
    // ============================================
    
    let modalPreview = null;

    function abrirModalPreview() {
        // Inicializar modal si no existe
        if (!modalPreview) {
            modalPreview = new bootstrap.Modal(document.getElementById('modalPreviewFactura'));
        }
        
        // Actualizar contenido del modal
        actualizarVistaPrevia();
        
        // Mostrar modal
        modalPreview.show();
    }

    function actualizarVistaPrevia() {
        const colorPrimario = document.getElementById('colorPrimario').value || '#0d6efd';
        const colorSecundario = document.getElementById('colorSecundario').value || '#6c757d';
        const tipoLetra = document.getElementById('tipoLetra').value || 'Arial';
        const tamanoLetra = document.getElementById('tamanoLetra').value || 12;
        
        // Aplicar variables CSS personalizadas al modal
        const facturaModal = document.getElementById('facturaPreviewModal');
        if (facturaModal) {
            facturaModal.style.setProperty('--color-primario', colorPrimario);
            facturaModal.style.setProperty('--color-secundario', colorSecundario);
            facturaModal.style.fontFamily = tipoLetra;
            facturaModal.style.fontSize = tamanoLetra + 'px';
        }

        // Actualizar colores dinámicamente en elementos específicos
        document.querySelectorAll('#facturaPreviewModal [style*="--color-primario"]').forEach(el => {
            // Los elementos que usan var(--color-primario) se actualizarán automáticamente
        });

        // Actualizar datos del emisor
        const nombreEmpresa = document.getElementById('nombreEmpresa').value || 'Nombre de la Empresa';
        const rfcEmpresa = document.getElementById('rfcEmpresa').value || '---';
        const direccion = document.getElementById('direccionEmpresa').value || 'Dirección Fiscal';
        const cpEmisor = document.getElementById('cpEmisor').value || '---';
        
        const modalNombre = document.getElementById('modalPreviewNombreEmpresa');
        if (modalNombre) {
            modalNombre.textContent = nombreEmpresa;
            modalNombre.style.color = colorPrimario;
        }
        
        const modalRFC = document.getElementById('modalPreviewRFC');
        if (modalRFC) modalRFC.textContent = `RFC: ${rfcEmpresa}`;
        
        const modalDireccion = document.getElementById('modalPreviewDireccion');
        if (modalDireccion) modalDireccion.textContent = direccion;
        
        const modalCP = document.getElementById('modalPreviewCP');
        if (modalCP) modalCP.textContent = cpEmisor;

        // Actualizar Serie y Folio
        const serie = document.getElementById('serieFactura').value || 'A';
        const folioActual = document.getElementById('folioActual').value || 0;
        const folioInicial = document.getElementById('folioInicial').value || 1;
        const folio = folioActual > 0 ? parseInt(folioActual) + 1 : folioInicial;
        
        const modalSerie = document.getElementById('modalPreviewSerie');
        if (modalSerie) modalSerie.textContent = serie;
        
        const modalFolio = document.getElementById('modalPreviewFolio');
        if (modalFolio) modalFolio.textContent = String(folio).padStart(3, '0');

        // Actualizar valores predeterminados
        const usoCfdi = document.getElementById('usoCfdi');
        const formaPago = document.getElementById('formaPago');
        const metodoPago = document.getElementById('metodoPagoDefault');
        const moneda = document.getElementById('monedaDefault');
        
        const modalUsoCFDI = document.getElementById('modalPreviewUsoCFDI');
        if (modalUsoCFDI && usoCfdi.selectedIndex > 0) {
            modalUsoCFDI.textContent = usoCfdi.options[usoCfdi.selectedIndex].text;
        }
        
        const modalFormaPago = document.getElementById('modalPreviewFormaPago');
        if (modalFormaPago && formaPago.selectedIndex > 0) {
            modalFormaPago.textContent = formaPago.options[formaPago.selectedIndex].text;
        }
        
        const modalMetodoPago = document.getElementById('modalPreviewMetodoPago');
        if (modalMetodoPago) modalMetodoPago.textContent = metodoPago.value || 'PUE';
        
        const modalMoneda = document.getElementById('modalPreviewMoneda');
        if (modalMoneda) modalMoneda.textContent = moneda.value || 'MXN';

        // Actualizar Logo
        const logoPreview = document.getElementById('logoPreview');
        const modalLogo = document.getElementById('modalPreviewLogo');
        const mostrarLogo = document.getElementById('mostrarLogo').checked;
        
        if (modalLogo) {
            if (!mostrarLogo) {
                modalLogo.style.display = 'none';
            } else {
                modalLogo.style.display = '';
                const logoImg = logoPreview ? logoPreview.querySelector('img') : null;
                if (logoImg) {
                    modalLogo.innerHTML = `<img src="${logoImg.src}" alt="Logo" style="max-height: 80px; max-width: 200px;" class="rounded">`;
                } else {
                    modalLogo.innerHTML = `
                        <div class="d-inline-block px-4 py-3 rounded" style="background-color: ${colorPrimario};">
                            <span class="text-white fw-bold">LOGO</span>
                        </div>
                    `;
                }
            }
        }

        // Actualizar textos personalizados
        const leyenda = document.getElementById('leyendaFactura').value || 'Este documento es una representación impresa de un CFDI';
        const condiciones = document.getElementById('condicionesPagoTexto').value || 'Pago en una sola exhibición';
        const observaciones = document.getElementById('observacionesDefault').value || 'Gracias por su compra';
        
        const modalLeyenda = document.getElementById('modalPreviewLeyenda');
        if (modalLeyenda) modalLeyenda.innerHTML = `<i class="bi bi-info-circle me-1"></i>${leyenda}`;
        
        const modalCondiciones = document.getElementById('modalPreviewCondiciones');
        if (modalCondiciones) modalCondiciones.innerHTML = `<i class="bi bi-credit-card me-2"></i>Condiciones de pago: ${condiciones}`;
        
        const modalObservaciones = document.getElementById('modalPreviewObservaciones');
        if (modalObservaciones) modalObservaciones.textContent = observaciones;

        // Mostrar/ocultar sello digital
        const mostrarSello = document.getElementById('mostrarSelloDigital').checked;
        const modalQR = document.getElementById('modalQRContainer');
        const modalSello = document.getElementById('modalSelloContainer');
        
        if (modalQR) modalQR.style.display = mostrarSello ? '' : 'none';
        if (modalSello) modalSello.classList.toggle('col-md-12', !mostrarSello);
        if (modalSello) modalSello.classList.toggle('col-md-9', mostrarSello);

        // Mostrar/ocultar observaciones
        const mostrarObs = document.getElementById('mostrarObservaciones').checked;
        const modalObsContainer = document.getElementById('modalObservacionesContainer');
        if (modalObsContainer) modalObsContainer.style.display = mostrarObs ? '' : 'none';

        // Actualizar colores en header de tabla
        const tablaHeader = document.querySelector('#modalTablaConceptos thead');
        if (tablaHeader) {
            tablaHeader.style.backgroundColor = colorPrimario;
        }

        // Actualizar color del footer
        const footerCondiciones = document.querySelector('#facturaPreviewModal .p-3.text-center.border-top');
        if (footerCondiciones) {
            footerCondiciones.style.backgroundColor = colorPrimario;
        }
    }

    function imprimirPreview() {
        const contenido = document.getElementById('facturaPreviewModal').outerHTML;
        const colorPrimario = document.getElementById('colorPrimario').value || '#0d6efd';
        const colorSecundario = document.getElementById('colorSecundario').value || '#6c757d';
        const tipoLetra = document.getElementById('tipoLetra').value || 'Arial';
        
        const ventana = window.open('', '_blank');
        ventana.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Vista Previa de Factura</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
                <style>
                    :root {
                        --color-primario: ${colorPrimario};
                        --color-secundario: ${colorSecundario};
                    }
                    body { 
                        padding: 20px; 
                        font-family: ${tipoLetra}, sans-serif;
                    }
                    @media print { 
                        body { padding: 0; }
                        .no-print { display: none !important; }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    ${contenido}
                </div>
                <script>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                        }, 500);
                    }
                <\/script>
            </body>
            </html>
        `);
        ventana.document.close();
    }

    // ============================================
    // FUNCIÓN PARA RESETEAR FORMULARIO
    // ============================================
    
    function resetearFormulario() {
        Swal.fire({
            title: '¿Restablecer formulario?',
            text: 'Se perderán todos los cambios no guardados',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, restablecer',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                if (configuracionActual) {
                    // Restaurar a la configuración guardada
                    aplicarConfiguracionAlFormulario(configuracionActual);
                } else {
                    // Limpiar formulario a valores por defecto
                    document.getElementById('formatoFacturasForm').reset();
                    document.getElementById('colorPrimario').value = '#0d6efd';
                    document.getElementById('colorPrimarioText').value = '#0d6efd';
                    document.getElementById('colorSecundario').value = '#6c757d';
                    document.getElementById('colorSecundarioText').value = '#6c757d';
                    document.getElementById('logoPreview').innerHTML = `
                        <div class="logo-placeholder">
                            <i class="bi bi-image display-5 text-muted"></i>
                            <p class="text-muted mb-0">Vista previa del logo</p>
                        </div>
                    `;
                }
                actualizarVistaPrevia();
                Swal.fire({
                    icon: 'success',
                    title: 'Formulario restablecido',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    // ============================================
    // MANEJADOR DE LOGO
    // ============================================
    
    function setupLogoHandler() {
        const logoInput = document.getElementById('logoEmpresa');
        const logoPreview = document.getElementById('logoPreview');

        if (!logoInput) return;

        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validar tipo de archivo
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo no válido',
                        text: 'Solo se permiten archivos PNG, JPG, JPEG, SVG, GIF o WEBP'
                    });
                    e.target.value = '';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo muy grande',
                        text: 'El archivo no puede ser mayor a 2MB'
                    });
                    e.target.value = '';
                    return;
                }

                // Mostrar vista previa
                const reader = new FileReader();
                reader.onload = function(event) {
                    logoPreview.innerHTML = `<img src="${event.target.result}" alt="Logo preview" />`;
                    actualizarVistaPrevia();
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // ============================================
    // MANEJADORES DE COLORES
    // ============================================
    
    function setupColorHandlers() {
        const colorPrimario = document.getElementById('colorPrimario');
        const colorPrimarioText = document.getElementById('colorPrimarioText');
        const colorSecundario = document.getElementById('colorSecundario');
        const colorSecundarioText = document.getElementById('colorSecundarioText');

        colorPrimario.addEventListener('input', function() {
            colorPrimarioText.value = this.value;
            actualizarVistaPrevia();
        });

        colorSecundario.addEventListener('input', function() {
            colorSecundarioText.value = this.value;
            actualizarVistaPrevia();
        });
    }

    // ============================================
    // MANEJADORES DE CAMBIOS EN TIEMPO REAL
    // ============================================
    
    function setupRealTimePreview() {
        const campos = [
            'nombreEmpresa', 'rfcEmpresa', 'direccionEmpresa', 
            'serieFactura', 'folioInicial', 'usoCfdi', 'formaPago',
            'leyendaFactura', 'mostrarLogo', 'mostrarSelloDigital', 'mostrarObservaciones'
        ];

        campos.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', actualizarVistaPrevia);
                el.addEventListener('change', actualizarVistaPrevia);
            }
        });
    }

    // ============================================
    // INICIALIZACIÓN
    // ============================================
    
    document.addEventListener('DOMContentLoaded', async function() {
        // Cargar catálogos primero
        await Promise.all([
            cargarRegimenesFiscales(),
            cargarUsosCFDI(),
            cargarFormaPago(),
            cargarTiposComprobante()
        ]);

        // Cargar configuración guardada
        await cargarConfiguracionActual();

        // Configurar manejadores
        setupLogoHandler();
        setupColorHandlers();
        setupRealTimePreview();

        // Vista previa inicial
        actualizarVistaPrevia();
    });
</script>