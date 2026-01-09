<!-- Página de Historial -->
<div class="content-wrapper bg-light">
    <div class="container py-4">
        <!-- Título de la página -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-primary fw-bold mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Historial de Facturas
                    </h2>
                    <a href="panel?pg=facturar" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Nueva factura
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">
                            <i class="bi bi-funnel me-2"></i>Filtros de Búsqueda
                        </h5>
                        <form class="row g-3">
                            <div class="col-md-3">
                                <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" id="fechaInicio">
                            </div>
                            <div class="col-md-3">
                                <label for="fechaFin" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="fechaFin">
                            </div>
                            <div class="col-md-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado">
                                    <option value="">Todos</option>
                                    <option value="exitosa">Exitosa</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="error">Error</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="buscar" class="form-label">Buscar</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="buscar" placeholder="Número de factura">
                                    <button class="btn btn-outline-primary" type="button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de facturas -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <!-- Loading indicator -->
                        <div id="loadingFacturas" class="text-center py-5" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2">Cargando facturas...</p>
                        </div>

                        <!-- No data message -->
                        <div id="noDataMessage" class="text-center py-5" style="display: none;">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-3 text-muted">No se encontraron facturas</p>
                        </div>

                        <!-- Tabla -->
                        <div class="table-responsive" id="tablaFacturasContainer">
                            <table class="table table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Folio</th>
                                        <th>RFC</th>
                                        <th>Razón Social</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaFacturas">
                                    <!-- Datos cargados dinámicamente -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <nav aria-label="Paginación de facturas" id="paginacionContainer" style="display: none;">
                            <ul class="pagination justify-content-center mb-0" id="paginacion">
                                <!-- Paginación dinámica -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalle de factura -->
<div class="modal fade" id="modalDetalleFactura" tabindex="-1" aria-labelledby="modalDetalleFacturaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetalleFacturaLabel">
                    <i class="bi bi-file-text me-2"></i>Detalle de Factura
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading state -->
                <div id="detalleLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>

                <!-- Detalle content -->
                <div id="detalleContent" style="display: none;">
                    <!-- Información general -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary fw-bold mb-3">Información General</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-bold">Folio:</td>
                                    <td id="detalle_folio"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fecha Emisión:</td>
                                    <td id="detalle_fecha_emision"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fecha Timbrado:</td>
                                    <td id="detalle_fecha_timbrado"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">UUID:</td>
                                    <td id="detalle_uuid" class="text-break"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Estado:</td>
                                    <td id="detalle_estado"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary fw-bold mb-3">Receptor</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-bold">RFC:</td>
                                    <td id="detalle_rfc"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Razón Social:</td>
                                    <td id="detalle_razon_social"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Correo:</td>
                                    <td id="detalle_correo"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Uso CFDI:</td>
                                    <td id="detalle_uso_cfdi"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Régimen Fiscal:</td>
                                    <td id="detalle_regimen_fiscal"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Productos/Conceptos -->
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="bi bi-box-seam me-2"></i>Conceptos
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Clave</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Unidad</th>
                                        <th>Valor Unit.</th>
                                        <th>Importe</th>
                                        <th>Impuesto</th>
                                    </tr>
                                </thead>
                                <tbody id="detalleConceptos">
                                    <!-- Conceptos cargados dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totales -->
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-bold">Subtotal:</td>
                                    <td class="text-end" id="detalle_subtotal"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">IVA Trasladado:</td>
                                    <td class="text-end" id="detalle_iva"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Retenciones:</td>
                                    <td class="text-end" id="detalle_retenciones"></td>
                                </tr>
                                <tr class="table-primary">
                                    <td class="fw-bold">TOTAL:</td>
                                    <td class="text-end fw-bold" id="detalle_total"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnDescargarPdfModal" onclick="descargarArchivo('pdf')">
                    <i class="bi bi-file-pdf me-2"></i>Descargar PDF
                </button>
                <button type="button" class="btn btn-primary" id="btnDescargarXmlModal" onclick="descargarArchivo('xml')">
                    <i class="bi bi-file-code me-2"></i>Descargar XML
                </button>
            </div>
        </div>
    </div>
</div>

<!-- boton para regresar a la pagina anterior -->
    <div class="text-end mb-4 me-4">
        <button type="button" class="btn btn-outline-primary btn-lg rounded-3" onclick="window.history.back()">
            <i class="bi bi-arrow-left me-2"></i>Regresar
        </button>
    </div>

<script>
    let currentIdFactura = null;
    let currentPage = 1;

    // Cargar facturas al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        cargarFacturas();

        // Event listeners para filtros
        document.getElementById('fechaInicio').addEventListener('change', aplicarFiltros);
        document.getElementById('fechaFin').addEventListener('change', aplicarFiltros);
        document.getElementById('estado').addEventListener('change', aplicarFiltros);

        // Buscar al presionar Enter o botón
        document.getElementById('buscar').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                aplicarFiltros();
            }
        });

        document.querySelector('.btn-outline-primary[type="button"]').addEventListener('click', aplicarFiltros);
    });

    function cargarFacturas(pagina = 1) {
        currentPage = pagina;

        // Mostrar loading
        document.getElementById('loadingFacturas').style.display = 'block';
        document.getElementById('noDataMessage').style.display = 'none';
        document.getElementById('tablaFacturasContainer').style.display = 'none';
        document.getElementById('paginacionContainer').style.display = 'none';

        // Obtener filtros
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;
        const estado = document.getElementById('estado').value;
        const buscar = document.getElementById('buscar').value;

        // Construir URL con parámetros
        let url = 'core/listar-facturas-usuario.php?pagina=' + pagina;
        if (fechaInicio) url += '&fecha_inicio=' + fechaInicio;
        if (fechaFin) url += '&fecha_fin=' + fechaFin;
        if (estado) url += '&estado=' + estado;
        if (buscar) url += '&buscar=' + encodeURIComponent(buscar);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                document.getElementById('loadingFacturas').style.display = 'none';

                if (data.success && data.facturas.length > 0) {
                    mostrarFacturas(data.facturas);
                    mostrarPaginacion(data.paginacion);
                    document.getElementById('tablaFacturasContainer').style.display = 'block';
                    document.getElementById('paginacionContainer').style.display = 'block';
                } else {
                    document.getElementById('noDataMessage').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error al cargar facturas:', error);
                document.getElementById('loadingFacturas').style.display = 'none';
                document.getElementById('noDataMessage').style.display = 'block';
            });
    }

    function mostrarFacturas(facturas) {
        const tbody = document.getElementById('tablaFacturas');
        tbody.innerHTML = '';

        facturas.forEach(factura => {
            const estadoBadge = obtenerBadgeEstado(factura.estatus);

            const tr = document.createElement('tr');
            let botonesAcciones = `<div class="btn-group me-2" role="group" aria-label="Acciones de factura">`;

            // Botón descargar PDF
            if (factura.tiene_pdf) {
                botonesAcciones += `
                <a href="core/descargar-factura.php?id_factura=${factura.id_factura}&tipo=pdf" 
                   class="btn btn-outline-secondary" title="Descargar PDF" target="_blank">
                    <i class="bi bi-file-pdf"></i> PDF
                </a>`;
            } else {
                botonesAcciones += `<button type="button" class="btn btn-outline-secondary" disabled title="PDF no disponible"><i class="bi bi-file-pdf"></i> PDF</button>`;
            }

            // Botón descargar XML
            if (factura.tiene_xml) {
                botonesAcciones += `
                <a href="core/descargar-factura.php?id_factura=${factura.id_factura}&tipo=xml" 
                   class="btn btn-outline-secondary" title="Descargar XML" target="_blank">
                    <i class="bi bi-file-code"></i> XML
                </a>`;
            } else {
                botonesAcciones += `<button type="button" class="btn btn-outline-secondary" disabled title="XML no disponible"><i class="bi bi-file-code"></i> XML</button>`;
            }

            // Botón cancelar (solo si está pendiente o timbrada)
            if (factura.estatus === 'pendiente' || factura.estatus === 'timbrada') {
                botonesAcciones += `
                <button type="button" class="btn btn-outline-secondary" title="Solicitar cancelación" onclick="solicitarCancelacion(${factura.id_factura}, '${factura.folio_completo}')">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>`;
            } else {
                botonesAcciones += `<button type="button" class="btn btn-outline-secondary" disabled title="No se puede cancelar"><i class="bi bi-x-circle"></i> Cancelar</button>`;
            }

            botonesAcciones += `</div>`;

            tr.innerHTML = `
            <td>${factura.fecha_emision_formatted}</td>
            <td>${factura.folio_completo}</td>
            <td>${factura.rfc_receptor}</td>
            <td>${factura.razon_social_receptor}</td>
            <td>${factura.total_formatted}</td>
            <td>${estadoBadge}</td>
            <td>${botonesAcciones}</td>
        `;
            tbody.appendChild(tr);
        });
    }

    function obtenerBadgeEstado(estatus) {
        const estados = {
            'timbrada': '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Timbrada</span>',
            'pendiente': '<span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pendiente</span>',
            'cancelada': '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Cancelada</span>'
        };
        return estados[estatus] || '<span class="badge bg-secondary">Desconocido</span>';
    }

    function mostrarPaginacion(paginacion) {
        const ul = document.getElementById('paginacion');
        ul.innerHTML = '';

        // Botón anterior
        const liPrev = document.createElement('li');
        liPrev.className = 'page-item' + (paginacion.pagina_actual === 1 ? ' disabled' : '');
        liPrev.innerHTML = paginacion.pagina_actual === 1 ?
            '<span class="page-link">Anterior</span>' :
            `<a class="page-link" href="#" onclick="cargarFacturas(${paginacion.pagina_actual - 1}); return false;">Anterior</a>`;
        ul.appendChild(liPrev);

        // Páginas
        const maxPages = 5;
        let startPage = Math.max(1, paginacion.pagina_actual - Math.floor(maxPages / 2));
        let endPage = Math.min(paginacion.total_paginas, startPage + maxPages - 1);

        if (endPage - startPage < maxPages - 1) {
            startPage = Math.max(1, endPage - maxPages + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            li.className = 'page-item' + (i === paginacion.pagina_actual ? ' active' : '');
            li.innerHTML = i === paginacion.pagina_actual ?
                `<span class="page-link">${i}</span>` :
                `<a class="page-link" href="#" onclick="cargarFacturas(${i}); return false;">${i}</a>`;
            ul.appendChild(li);
        }

        // Botón siguiente
        const liNext = document.createElement('li');
        liNext.className = 'page-item' + (paginacion.pagina_actual === paginacion.total_paginas ? ' disabled' : '');
        liNext.innerHTML = paginacion.pagina_actual === paginacion.total_paginas ?
            '<span class="page-link">Siguiente</span>' :
            `<a class="page-link" href="#" onclick="cargarFacturas(${paginacion.pagina_actual + 1}); return false;">Siguiente</a>`;
        ul.appendChild(liNext);
    }

    function aplicarFiltros() {
        cargarFacturas(1);
    }

    function verDetalle(idFactura) {
        currentIdFactura = idFactura;

        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modalDetalleFactura'));
        modal.show();

        // Mostrar loading
        document.getElementById('detalleLoading').style.display = 'block';
        document.getElementById('detalleContent').style.display = 'none';

        // Cargar detalle
        fetch(`core/obtener-detalle-factura.php?id_factura=${idFactura}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('detalleLoading').style.display = 'none';

                if (data.success) {
                    mostrarDetalle(data.factura, data.detalles);
                    document.getElementById('detalleContent').style.display = 'block';

                    // Mostrar/ocultar botones de descarga
                    document.getElementById('btnDescargarPdfModal').style.display = data.factura.tiene_pdf ? 'inline-block' : 'none';
                    document.getElementById('btnDescargarXmlModal').style.display = data.factura.tiene_xml ? 'inline-block' : 'none';
                } else {
                    alert('Error al cargar detalle: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('detalleLoading').style.display = 'none';
                alert('Error al cargar el detalle de la factura');
            });
    }

    function mostrarDetalle(factura, detalles) {
        // Información general
        document.getElementById('detalle_folio').textContent = factura.folio_completo;
        document.getElementById('detalle_fecha_emision').textContent = factura.fecha_emision_formatted;
        document.getElementById('detalle_fecha_timbrado').textContent = factura.fecha_timbrado_formatted;
        document.getElementById('detalle_uuid').textContent = factura.uuid || 'N/A';
        document.getElementById('detalle_estado').innerHTML = obtenerBadgeEstado(factura.estatus);

        // Receptor
        document.getElementById('detalle_rfc').textContent = factura.rfc_receptor;
        document.getElementById('detalle_razon_social').textContent = factura.razon_social_receptor;
        document.getElementById('detalle_correo').textContent = factura.correo_receptor || 'N/A';
        document.getElementById('detalle_uso_cfdi').textContent = factura.uso_cfdi;
        document.getElementById('detalle_regimen_fiscal').textContent = factura.regimen_fiscal_receptor;

        // Conceptos
        const tbody = document.getElementById('detalleConceptos');
        tbody.innerHTML = '';

        detalles.forEach(detalle => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
            <td>${detalle.clave_prod_serv}</td>
            <td>${detalle.descripcion}</td>
            <td class="text-end">${detalle.cantidad_formatted}</td>
            <td>${detalle.unidad}</td>
            <td class="text-end">${detalle.valor_unitario_formatted}</td>
            <td class="text-end">${detalle.importe_formatted}</td>
            <td class="text-end">${detalle.impuesto_importe_formatted}</td>
        `;
            tbody.appendChild(tr);
        });

        // Totales
        document.getElementById('detalle_subtotal').textContent = factura.subtotal_formatted;
        document.getElementById('detalle_iva').textContent = factura.impuestos_trasladados_formatted;
        document.getElementById('detalle_retenciones').textContent = factura.impuestos_retenidos_formatted;
        document.getElementById('detalle_total').textContent = factura.total_formatted;
    }

    function descargarArchivo(tipo) {
        if (currentIdFactura) {
            window.open(`core/descargar-factura.php?id_factura=${currentIdFactura}&tipo=${tipo}`, '_blank');
        }
    }

    function solicitarCancelacion(idFactura, folioFactura) {
        const motivosCancelacion = [{
                id: '01',
                nombre: 'Comprobante emitido con errores'
            },
            {
                id: '02',
                nombre: 'Comprobante emitido por error de operación'
            },
            {
                id: '03',
                nombre: 'No se realizó la operación'
            },
            {
                id: '04',
                nombre: 'Operación nominada en relación única'
            }
        ];

        let htmlMotivos = '<div class="text-start">';
        motivosCancelacion.forEach(motivo => {
            htmlMotivos += `
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="motivoCancelacion" id="motivo${motivo.id}" value="${motivo.nombre}" required>
                <label class="form-check-label" for="motivo${motivo.id}">
                    ${motivo.nombre}
                </label>
            </div>
        `;
        });
        htmlMotivos += '</div>';

        Swal.fire({
            title: '¿Solicitar cancelación?',
            html: `
            <p class="text-muted mb-3">Selecciona el motivo de cancelación para la factura <strong>${folioFactura}</strong></p>
            ${htmlMotivos}
        `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Enviar solicitud',
            cancelButtonText: 'Cancelar',
            didOpen: () => {
                // Agregar validación al confirmar
                const confirmButton = Swal.getConfirmButton();
                confirmButton.addEventListener('click', () => {
                    const motivoSeleccionado = document.querySelector('input[name="motivoCancelacion"]:checked');
                    if (!motivoSeleccionado) {
                        Swal.showValidationMessage('Debes seleccionar un motivo de cancelación');
                        return false;
                    }
                });
            },
            preConfirm: () => {
                const motivoSeleccionado = document.querySelector('input[name="motivoCancelacion"]:checked');
                if (!motivoSeleccionado) {
                    Swal.showValidationMessage('Debes seleccionar un motivo de cancelación');
                    return false;
                }
                return motivoSeleccionado.value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const motivo = result.value;

                // Enviar solicitud al backend
                fetch('core/solicitar-cancelacion.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id_factura: idFactura,
                            motivo: motivo
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Solicitud enviada!',
                                text: 'Tu solicitud de cancelación ha sido enviada al administrador.',
                                icon: 'success',
                                confirmButtonColor: '#198754'
                            }).then(() => {
                                cargarFacturas(currentPage);
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'No se pudo enviar la solicitud',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al enviar la solicitud',
                            icon: 'error'
                        });
                    });
            }
        });
    }
</script>