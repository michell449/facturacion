<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura CFDI 4.0</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        :root {
            --color-primario: COLOR_PRIMARIO;
            --color-secundario: COLOR_SECUNDARIO;
            --tipo-letra: 'TIPO_LETRA', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            --tamano-letra: TAMANO_LETRApt;
        }

        @page {
            size: letter;
            margin: 10mm;
        }

        @media print {
            html,
            body {
                width: 8.5in;
                height: 11in;
                margin: 0;
                padding: 0;
            }

            .factura-container {
                box-shadow: none !important;
                margin: 0 !important;
            }
        }

        body {
            background: #f0f2f5;
            font-family: var(--tipo-letra);
            font-size: var(--tamano-letra);
        }

        .bg-primary-custom {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%) !important;
        }

        .text-primary-custom {
            color: var(--color-primario) !important;
        }

        .border-primary-custom {
            border-color: var(--color-primario) !important;
        }

        .badge-primary-custom {
            background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%);
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="background: #f0f2f5; min-height: 100vh; padding: 30px 0;">
        <div class="factura-container bg-white shadow-lg mx-auto"
            style="max-width: 8.5in; border-radius: 12px; overflow: hidden;">

            <!-- ===== HEADER ===== -->
            <div class="text-white bg-primary-custom"
                style="padding: 25px 30px;">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center" style="gap: 20px;">
                        <div class="bg-white rounded-3 shadow-sm"
                            style="width: 85px; height: 85px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 3px solid rgba(255,255,255,0.3);">
                            <img src="LOGO_URL" alt="Logo"
                                style="max-width: 75px; max-height: 75px; object-fit: contain;">
                        </div>
                        <div>
                            <h1 class="mb-1"
                                style="font-size: 13pt; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                EMISOR_NOMBRE</h1>
                            <p class="mb-0" style="font-size: 8pt; opacity: 0.9;">RFC: EMISOR_RFC</p>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="rounded-3 shadow-sm"
                            style="background: rgba(255,255,255,0.25); padding: 15px 30px; border: 2px solid rgba(255,255,255,0.4);">
                            <h2 class="mb-1" style="font-size: 13pt; font-weight: 700;">TIPO_COMPROBANTE</h2>
                            <div style="font-size: 8pt; font-weight: 600;">SERIE-FOLIO</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== BARRA UUID ===== -->
            <div class="bg-light border-bottom border-4 border-primary-custom"
                style="padding: 15px 30px; background: linear-gradient(to right, #e7f1ff, #f8f9fa) !important; box-shadow: inset 0 -3px 10px rgba(13, 110, 253, 0.1);">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <span class="text-uppercase text-secondary fw-bold" style="font-size: 8pt;">Folio Fiscal
                            (UUID):</span>
                        <span
                            class="bg-white border border-2 rounded px-3 py-1 fw-bold shadow-sm text-primary-custom border-primary-custom"
                            style="font-family: 'Consolas', monospace; font-size: 10pt;">UUID</span>
                        <span class="badge rounded-pill fw-bold"
                            style="background: linear-gradient(135deg, #198754 0%, #146c43 100%); font-size: 8pt; padding: 4px 12px; box-shadow: 0 2px 8px rgba(25, 135, 84, 0.4);">CFDI
                            4.0</span>
                    </div>
                    <div class="text-end" style="font-size: 9pt;">
                        <div><strong class="text-primary-custom">Fecha y Hora:</strong> FECHA_EMISION</div>
                        <div><strong class="text-primary-custom">Lugar de Expedición:</strong> LUGAR_EXPEDICION</div>
                    </div>
                </div>
            </div>

            <!-- ===== DATOS EMISOR / RECEPTOR ===== -->
            <div class="row g-0 border-bottom">
                <!-- Emisor -->
                <div class="col-md-6 border-end" style="padding: 20px 30px;">
                    <span class="badge text-white mb-3 d-inline-flex align-items-center badge-primary-custom"
                        style="padding: 8px 16px; border-radius: 6px; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 3px 10px rgba(13, 110, 253, 0.3);">
                        Emisor
                    </span>
                    <dl class="row g-2 mb-0" style="font-size: 9pt;">
                        <dt class="col-4 text-secondary fw-semibold">Razón Social:</dt>
                        <dd class="col-8 fw-bold mb-2 text-primary-custom">EMISOR_NOMBRE</dd>

                        <dt class="col-4 text-secondary fw-semibold">RFC:</dt>
                        <dd class="col-8 mb-2">EMISOR_RFC</dd>

                        <dt class="col-4 text-secondary fw-semibold">Régimen Fiscal:</dt>
                        <dd class="col-8 mb-2">EMISOR_REGIMEN</dd>

                        <dt class="col-4 text-secondary fw-semibold">Domicilio Fiscal:</dt>
                        <dd class="col-8 mb-2">EMISOR_DOMICILIO</dd>

                        <dt class="col-4 text-secondary fw-semibold">C.P.:</dt>
                        <dd class="col-8 mb-0">EMISOR_CP</dd>
                    </dl>
                </div>

                <!-- Receptor -->
                <div class="col-md-6" style="padding: 20px 30px;">
                    <span class="badge text-white mb-3 d-inline-flex align-items-center badge-primary-custom"
                        style="padding: 8px 16px; border-radius: 6px; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 3px 10px rgba(13, 110, 253, 0.3);">
                        Receptor
                    </span>
                    <dl class="row g-2 mb-0" style="font-size: 9pt;">
                        <dt class="col-4 text-secondary fw-semibold">Razón Social:</dt>
                        <dd class="col-8 fw-bold mb-2 text-primary-custom">RECEPTOR_NOMBRE</dd>

                        <dt class="col-4 text-secondary fw-semibold">RFC:</dt>
                        <dd class="col-8 mb-2">RECEPTOR_RFC</dd>

                        <dt class="col-4 text-secondary fw-semibold">Régimen Fiscal:</dt>
                        <dd class="col-8 mb-2">RECEPTOR_REGIMEN</dd>

                        <dt class="col-4 text-secondary fw-semibold">Domicilio Fiscal:</dt>
                        <dd class="col-8 mb-2">RECEPTOR_DOMICILIO</dd>

                        <dt class="col-4 text-secondary fw-semibold">C.P.:</dt>
                        <dd class="col-8 mb-2">RECEPTOR_CP</dd>

                        <dt class="col-4 text-secondary fw-semibold">Uso CFDI:</dt>
                        <dd class="col-8 mb-0">USO_CFDI</dd>
                    </dl>
                </div>
            </div>

            <!-- ===== CONCEPTOS ===== -->
            <div style="padding: 25px 30px;">
                <h3 class="fw-bold border-bottom border-3 pb-2 mb-4 d-flex align-items-center text-primary-custom border-primary-custom"
                    style="font-size: 12pt; gap: 10px;">
                    <span class="rounded badge-primary-custom"
                        style="width: 5px; height: 22px; box-shadow: 0 2px 8px rgba(13, 110, 253, 0.4);"></span>
                    Conceptos
                </h3>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 8pt;">
                        <thead class="text-white bg-primary-custom"
                            style="box-shadow: 0 3px 10px rgba(13, 110, 253, 0.3);">
                            <tr>
                                <th style="padding: 12px 10px; text-transform: uppercase; letter-spacing: 0.3px;">Clave
                                    SAT</th>
                                <th style="padding: 12px 10px; text-transform: uppercase;">Cant.</th>
                                <th style="padding: 12px 10px; text-transform: uppercase;">Unidad</th>
                                <th style="padding: 12px 10px; text-transform: uppercase;">Descripción</th>
                                <th class="text-end" style="padding: 12px 10px; text-transform: uppercase;">P. Unitario
                                </th>
                                <th class="text-end" style="padding: 12px 10px; text-transform: uppercase;">Descuento
                                </th>
                                <th class="text-end" style="padding: 12px 10px; text-transform: uppercase;">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 12px 10px; vertical-align: middle;">
                                    <span class="text-secondary"
                                        style="font-family: 'Consolas', monospace; font-size: 7pt;">ClaveProdServ</span>
                                </td>
                                <td style="padding: 12px 10px; vertical-align: middle;">Cantidad</td>
                                <td style="padding: 12px 10px; vertical-align: middle;">
                                    <span class="text-secondary"
                                        style="font-family: 'Consolas', monospace; font-size: 7pt;">ClaveUnidad</span>
                                </td>
                                <td style="padding: 12px 10px; vertical-align: middle;">
                                    <span class="fw-medium">Descripcion</span>
                                </td>
                                <td class="text-end"
                                    style="padding: 12px 10px; font-family: 'Consolas', monospace; vertical-align: middle;">
                                    $ValorUnitario</td>
                                <td class="text-end"
                                    style="padding: 12px 10px; font-family: 'Consolas', monospace; vertical-align: middle;">
                                    $Descuento</td>
                                <td class="text-end fw-bold"
                                    style="padding: 12px 10px; font-family: 'Consolas', monospace; vertical-align: middle;">
                                    $Importe</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===== TOTALES ===== -->
            <div class="bg-light border-top" style="padding: 15px 30px;">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="bg-white rounded-3 shadow-sm"
                            style="padding: 20px; border-left: 5px solid var(--color-primario); box-shadow: 0 3px 15px rgba(13, 110, 253, 0.15) !important;">
                            <div class="text-secondary text-uppercase mb-2" style="font-size: 8pt; font-weight: 600;">
                                Importe con Letra</div>
                            <div class="fst-italic fw-medium" style="font-size: 11pt; color: #2c3e50;">TOTAL_LETRA</div>
                        </div>
                        <div class="bg-white rounded-3 shadow-sm overflow-hidden mt-4 ">
                            <div class="d-flex justify-content-between align-items-center text-secondary border-bottom"
                                style="padding: 10px 18px; font-size: 9pt;">
                                <span>Forma de Pago</span>
                                <span style="font-family: 'Consolas', monospace;">FORMA_PAGO</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-secondary border-bottom"
                                style="padding: 10px 18px; font-size: 9pt;">
                                <span>Metodo de Pago</span>
                                <span style="font-family: 'Consolas', monospace;">METODO_PAGO</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-secondary border-bottom"
                                style="padding: 10px 18px; font-size: 9pt;">
                                <span>Moneda</span>
                                <span style="font-family: 'Consolas', monospace;">MONEDA</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-secondary border-bottom"
                                style="padding: 10px 18px; font-size: 9pt;">
                                <span>Exportación</span>
                                <span style="font-family: 'Consolas', monospace;">EXPORTACION</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="bg-white rounded-3 shadow-sm overflow-hidden">
                            <div class="border-bottom d-flex justify-content-between align-items-center"
                                style="padding: 12px 18px; font-size: 9pt;">
                                <span class="fw-medium">Subtotal</span>
                                <span class="fw-semibold" style="font-family: 'Consolas', monospace;">$SUBTOTAL <span
                                        class="text-secondary" style="font-size: 8pt;">MONEDA</span></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-secondary border-bottom"
                                style="padding: 10px 18px; font-size: 9pt;">
                                <span>IVA Trasladado (16%)</span>
                                <span style="font-family: 'Consolas', monospace;">$IVA_TRASLADADO</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-secondary border-bottom"
                                style="padding: 10px 18px; font-size: 9pt;">
                                <span>IVA Retenido</span>
                                <span style="font-family: 'Consolas', monospace;">$IVA_RETENIDO</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-secondary border-bottom"
                                style="padding: 10px 18px; font-size: 9pt;">
                                <span>ISR Retenido</span>
                                <span style="font-family: 'Consolas', monospace;">$ISR_RETENIDO</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-secondary border-bottom"
                                style="padding: 10px 18px; font-size: 9pt;">
                                <span>IEPS</span>
                                <span style="font-family: 'Consolas', monospace;">$IEPS</span>
                            </div>
                            <div class="text-white d-flex justify-content-between align-items-center bg-primary-custom"
                                style="padding: 18px; font-size: 13pt; font-weight: 700; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.4);">
                                <span>TOTAL</span>
                                <span style="font-family: 'Consolas', monospace;">$TOTAL MONEDA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== SELLOS DIGITALES ===== -->
            <div class="border-top" style="padding: 25px 30px;">
                <h3 class="fw-bold border-bottom border-3 pb-2 mb-4 d-flex align-items-center text-primary-custom border-primary-custom"
                    style="font-size: 12pt; gap: 10px;">
                    <span class="rounded badge-primary-custom"
                        style="width: 5px; height: 22px; box-shadow: 0 2px 8px rgba(13, 110, 253, 0.4);"></span>
                    Sellos Digitales
                </h3>
                <div class="row g-4">
                    <div class="col-auto">
                        <div class="text-center">
                            <div class="bg-white border border-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center"
                                style="width: 110px; height: 110px; overflow: hidden;">
                                <img src="{{QR_CODE_URL}}" alt="QR" style="max-width: 100px; max-height: 100px;">
                            </div>
                            <span class="d-block mt-2 text-secondary text-uppercase"
                                style="font-size: 7pt;">Verificación SAT</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-column gap-3">
                            <div class="rounded-3 shadow-sm"
                                style="background: #e7f1ff; padding: 12px 15px; border-left: 4px solid var(--color-primario);">
                                <div class="text-secondary text-uppercase fw-bold mb-2" style="font-size: 7pt;">Sello
                                    Digital del CFDI</div>
                                <div class="text-break"
                                    style="font-family: 'Consolas', monospace; font-size: 6.5pt; line-height: 1.4;">
                                    SELLO_CFDI</div>
                            </div>
                            <div class="rounded-3 shadow-sm"
                                style="background: #e7f1ff; padding: 12px 15px; border-left: 4px solid var(--color-primario);">
                                <div class="text-secondary text-uppercase fw-bold mb-2" style="font-size: 7pt;">Sello
                                    Digital del SAT</div>
                                <div class="text-break"
                                    style="font-family: 'Consolas', monospace; font-size: 6.5pt; line-height: 1.4;">
                                    SELLO_SAT</div>
                            </div>
                            <div class="rounded-3 shadow-sm"
                                style="background: #e7f1ff; padding: 12px 15px; border-left: 4px solid var(--color-primario);">
                                <div class="text-secondary text-uppercase fw-bold mb-2" style="font-size: 7pt;">Cadena
                                    Original del Complemento</div>
                                <div class="text-break"
                                    style="font-family: 'Consolas', monospace; font-size: 6.5pt; line-height: 1.4;">
                                    CADENA_ORIGINAL</div>
                            </div>
                            <div class="rounded-3 shadow-sm"
                                style="background: #e7f1ff; padding: 10px 15px; border-left: 4px solid var(--color-primario);">
                                <div class="text-secondary fw-bold" style="font-size: 7pt;">No. Certificado SAT:
                                    NO_CERTIFICADO_SAT | No. Certificado Emisor: NO_CERTIFICADO_EMISOR</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>