<!-- Facturas Generadas -->
<div class="content-wrapper bg-light loaded">
    <!-- Header -->
    <div class="container py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-8">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="bi bi-receipt-cutoff display-6 text-primary me-2"></i>
                    Facturas Generadas
                </h2>
                <p class="text-muted mb-0">Gestiona las facturas generadas por los usuarios.</p>
            </div>
            <div class="col-4 text-end">
                <button type="button" class="btn btn-outline-primary btn-lg rounded-3" onclick="window.history.back()">
                    <i class="bi bi-arrow-left me-2"></i>Regresar
                </button>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-12">
                <!-- Filtros y Búsqueda -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 rounded-end-3"
                                placeholder="Buscar por folio, cliente, RFC..." id="buscarFactura">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-lg rounded-3" id="filtroSucursal">
                            <option value="">Todas las sucursales</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-lg rounded-3" id="filtroEstado">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="timbrada">Timbrada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control form-control-lg rounded-3" id="fechaDesde">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control form-control-lg rounded-3" id="fechaHasta">
                    </div>
                </div>
                <!-- Listado de Facturas-->
                <div id="vistaFacturas" class="p-4">
                    <ul class="list-group list-group-flush" id="listaFacturas">
                        <li class="list-group-item text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-3 text-muted">Cargando facturas...</p>
                        </li>
                    </ul>

                    <!-- Paginación -->
                    <div id="paginacionContainer" class="d-flex justify-content-between align-items-center mt-4" style="display: none !important;">
                        <small class="text-muted" id="infoRegistros">Mostrando facturas...</small>
                        <nav>
                            <ul class="pagination pagination-lg mb-0" id="paginacion">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Filtros Avanzados -->
<div class="modal fade" id="filtrosAvanzadosModal" tabindex="-1" aria-labelledby="filtrosAvanzadosModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="filtrosAvanzadosModalLabel">
                    <i class="bi bi-funnel me-2"></i>Filtros Avanzados
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formFiltrosAvanzados">    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rango de Fechas</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="date" class="form-control" id="fechaDesdeAvanzado">
                                </div>
                                <div class="col-6">
                                    <input type="date" class="form-control" id="fechaHastaAvanzado">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rango de Montos</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control" placeholder="Desde $" id="montoDesde">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" placeholder="Hasta $" id="montoHasta">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Estados</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="estadoVigente" checked>
                                <label class="form-check-label" for="estadoVigente">Vigente</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="estadoPagada" checked>
                                <label class="form-check-label" for="estadoPagada">Pagada</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="estadoPendiente" checked>
                                <label class="form-check-label" for="estadoPendiente">Pendiente</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="estadoCancelada">
                                <label class="form-check-label" for="estadoCancelada">Cancelada</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sucursales</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sucursalCentro" checked>
                                <label class="form-check-label" for="sucursalCentro">Centro</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sucursalNorte" checked>
                                <label class="form-check-label" for="sucursalNorte">Norte</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sucursalSur" checked>
                                <label class="form-check-label" for="sucursalSur">Sur</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sucursalOriente" checked>
                                <label class="form-check-label" for="sucursalOriente">Oriente</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                    Limpiar Filtros
                </button>
                <button type="button" class="btn btn-primary btn-lg fw-semibold" onclick="aplicarFiltros()">
                    <i class="bi bi-funnel me-2"></i>Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let paginaActual = 1;
    let filtrosActuales = {};

    document.addEventListener('DOMContentLoaded', function() {
        // Cargar sucursales en filtro
        cargarSucursales();
        
        // Cargar facturas inicial
        cargarFacturas();

        // Búsqueda en tiempo real con debounce
        let timeoutBusqueda;
        document.getElementById('buscarFactura').addEventListener('input', function() {
            clearTimeout(timeoutBusqueda);
            timeoutBusqueda = setTimeout(() => {
                paginaActual = 1;
                cargarFacturas();
            }, 500);
        });

        // Filtros simples
        ['filtroSucursal', 'filtroEstado', 'fechaDesde', 'fechaHasta'].forEach(id => {
            document.getElementById(id).addEventListener('change', function() {
                paginaActual = 1;
                cargarFacturas();
            });
        });
    });

    async function cargarSucursales() {
        try {
            const response = await fetch('core/consultar-sucursales.php');
            const result = await response.json();
            
            if (result.success && result.data) {
                const select = document.getElementById('filtroSucursal');
                result.data.forEach(suc => {
                    const option = document.createElement('option');
                    option.value = suc.id_empresa;
                    option.textContent = suc.razon_social || suc.nombre;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error al cargar sucursales:', error);
        }
    }

    async function cargarFacturas() {
        const listaFacturas = document.getElementById('listaFacturas');
        listaFacturas.innerHTML = `
            <li class="list-group-item text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando facturas...</p>
            </li>
        `;

        try {
            // Construir parámetros de búsqueda
            const params = new URLSearchParams({
                pagina: paginaActual,
                busqueda: document.getElementById('buscarFactura').value || '',
                sucursal: document.getElementById('filtroSucursal').value || '',
                estado: document.getElementById('filtroEstado').value || '',
                fecha_desde: document.getElementById('fechaDesde').value || '',
                fecha_hasta: document.getElementById('fechaHasta').value || ''
            });

            const response = await fetch(`core/listar-facturas.php?${params}`);
            const result = await response.json();

            if (result.success) {
                if (result.facturas.length === 0) {
                    listaFacturas.innerHTML = `
                        <li class="list-group-item text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="mt-3 text-muted">No se encontraron facturas</p>
                        </li>
                    `;
                    document.getElementById('paginacionContainer').style.display = 'none';
                    return;
                }

                // Renderizar facturas
                listaFacturas.innerHTML = result.facturas.map(factura => renderFactura(factura)).join('');
                
                // Actualizar paginación
                actualizarPaginacion(result);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error al cargar facturas:', error);
            listaFacturas.innerHTML = `
                <li class="list-group-item text-center py-5 text-danger">
                    <i class="bi bi-exclamation-triangle display-1"></i>
                    <p class="mt-3">Error al cargar facturas: ${error.message}</p>
                </li>
            `;
        }
    }

    function renderFactura(factura) {
        // Debug: mostrar datos de la factura
        console.log('Factura:', factura.id_factura, 'PDF:', factura.pdf_path, 'XML:', factura.xml_path);
        
        const folio = `${factura.serie_interno || ''}-${factura.folio_interno}`;
        const fecha = new Date(factura.fecha_emision).toLocaleString('es-MX', {
            year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
        });
        const total = parseFloat(factura.total).toLocaleString('es-MX', {
            style: 'currency', currency: 'MXN'
        });

        let estatusHtml = '';
        let clasesCard = 'list-group-item py-4 border-bottom factura-card';
        
        if (factura.estatus === 'timbrada') {
            estatusHtml = `
                <div class="bg-success bg-opacity-10 rounded-3 p-3 my-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <div>
                            <small class="text-success d-block fw-semibold">Timbrada</small>
                            <small class="text-muted">${factura.fecha_timbrado ? new Date(factura.fecha_timbrado).toLocaleString('es-MX') : ''}</small>
                            ${factura.uuid ? `<br><small class="text-muted">UUID: ${factura.uuid}</small>` : ''}
                        </div>
                    </div>
                </div>
            `;
        } else if (factura.estatus === 'cancelada') {
            estatusHtml = `
                <div class="bg-danger bg-opacity-10 rounded-3 p-3 my-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-x-circle text-danger me-2"></i>
                        <div>
                            <small class="text-danger d-block fw-semibold">Cancelada</small>
                        </div>
                    </div>
                </div>
            `;
            clasesCard += ' opacity-75';
        } else {
            estatusHtml = `
                <div class="bg-warning bg-opacity-10 rounded-3 p-3 my-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock text-warning me-2"></i>
                        <div>
                            <small class="text-warning d-block fw-semibold">Pendiente</small>
                        </div>
                    </div>
                </div>
            `;
        }

        const botones = factura.estatus === 'cancelada' ? `
            <button type="button" class="btn btn-outline-secondary" onclick="verFactura(${factura.id_factura})">
                <i class="bi bi-eye me-1"></i>Ver
            </button>
            <button type="button" class="btn btn-outline-danger" disabled>
                <i class="bi bi-x-circle me-1"></i>Cancelada
            </button>
        ` : `
            ${(factura.pdf_path && factura.pdf_path !== '') ? `
                <button type="button" class="btn btn-outline-success" onclick="descargarPDF('${factura.pdf_path.replace(/'/g, "\\'")}')"
                        title="Descargar PDF">
                    <i class="bi bi-file-pdf me-1"></i>PDF
                </button>
            ` : factura.estatus === 'timbrada' ? `
                <button type="button" class="btn btn-outline-success" onclick="generarPDF(${factura.id_factura})"
                        title="Generar y descargar PDF">
                    <i class="bi bi-file-pdf me-1"></i>Generar PDF
                </button>
            ` : ''}
            ${(factura.xml_path && factura.xml_path !== '') ? `
                <button type="button" class="btn btn-outline-info" onclick="descargarXML('${factura.xml_path.replace(/'/g, "\\'")}')"
                        title="Descargar XML">
                    <i class="bi bi-file-earmark-code me-1"></i>XML
                </button>
            ` : ''}
            ${factura.estatus === 'timbrada' ? `
                <button type="button" class="btn btn-outline-danger" onclick="cancelarFactura(${factura.id_factura})">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
            ` : ''}
        `;

        return `
            <li class="${clasesCard}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold ${factura.estatus === 'cancelada' ? 'text-muted' : 'text-primary'} mb-1">${folio}</h6>
                        <small class="text-muted">${fecha}</small>
                        <div class="mt-2 small text-muted">
                            <span class="me-3"><strong>Cliente:</strong> ${factura.razon_social_receptor}</span>
                            <span class="me-3"><strong>RFC:</strong> ${factura.rfc_receptor}</span>
                            <span class="me-3"><strong>Sucursal:</strong> ${factura.sucursal}</span>
                            <span><strong>Total:</strong> <span class="text-success">${total}</span></span>
                        </div>
                    </div>
                </div>

                ${estatusHtml}

                <div class="text-end">
                    <div class="btn-group" role="group">
                        ${botones}
                    </div>
                </div>
            </li>
        `;
    }

    function actualizarPaginacion(result) {
        const paginacionContainer = document.getElementById('paginacionContainer');
        const infoRegistros = document.getElementById('infoRegistros');
        const paginacion = document.getElementById('paginacion');

        const desde = ((result.pagina - 1) * result.porPagina) + 1;
        const hasta = Math.min(result.pagina * result.porPagina, result.total);
        
        infoRegistros.textContent = `Mostrando ${desde}-${hasta} de ${result.total} facturas`;
        
        if (result.totalPaginas <= 1) {
            paginacionContainer.style.display = 'none';
            return;
        }
        
        paginacionContainer.style.display = 'flex';
        
        let html = '';
        
        // Botón anterior
        html += `
            <li class="page-item ${result.pagina === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="cargarPagina(${result.pagina - 1}); return false;">Anterior</a>
            </li>
        `;
        
        // Páginas
        const maxPaginas = 5;
        let inicio = Math.max(1, result.pagina - Math.floor(maxPaginas / 2));
        let fin = Math.min(result.totalPaginas, inicio + maxPaginas - 1);
        
        if (fin - inicio < maxPaginas - 1) {
            inicio = Math.max(1, fin - maxPaginas + 1);
        }
        
        if (inicio > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="cargarPagina(1); return false;">1</a></li>`;
            if (inicio > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        
        for (let i = inicio; i <= fin; i++) {
            html += `
                <li class="page-item ${i === result.pagina ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="cargarPagina(${i}); return false;">${i}</a>
                </li>
            `;
        }
        
        if (fin < result.totalPaginas) {
            if (fin < result.totalPaginas - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            html += `<li class="page-item"><a class="page-link" href="#" onclick="cargarPagina(${result.totalPaginas}); return false;">${result.totalPaginas}</a></li>`;
        }
        
        // Botón siguiente
        html += `
            <li class="page-item ${result.pagina === result.totalPaginas ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="cargarPagina(${result.pagina + 1}); return false;">Siguiente</a>
            </li>
        `;
        
        paginacion.innerHTML = html;
    }

    function cargarPagina(pagina) {
        paginaActual = pagina;
        cargarFacturas();
        window.scrollTo(0, 0);
    }

    function verFactura(idFactura) {
        alert('Próximamente: Ver detalles de factura ' + idFactura);
    }

    function descargarPDF(rutaPdf) {
        console.log('Descargando PDF:', rutaPdf);
        if (!rutaPdf || rutaPdf === '') {
            Swal.fire('Error', 'No hay archivo PDF disponible', 'error');
            return;
        }
        
        // Crear un fetch para descargar con el nombre correcto
        fetch(rutaPdf)
            .then(response => {
                if (!response.ok) throw new Error('Archivo no encontrado');
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = rutaPdf.split('/').pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                console.error('Error al descargar PDF:', error);
                Swal.fire('Error', 'No se pudo descargar el archivo PDF', 'error');
            });
    }

    function descargarXML(rutaXml) {
        console.log('Descargando XML:', rutaXml);
        if (!rutaXml || rutaXml === '') {
            Swal.fire('Error', 'No hay archivo XML disponible', 'error');
            return;
        }
        
        // Construir la ruta completa
        let rutaCompleta = rutaXml;
        if (!rutaXml.startsWith('http') && !rutaXml.startsWith('/') && !rutaXml.includes('uploads/')) {
            rutaCompleta = 'uploads/xml_timbrados/' + rutaXml;
        }
        
        console.log('Ruta completa XML:', rutaCompleta);
        
        // Crear un fetch para descargar con el nombre correcto
        fetch(rutaCompleta)
            .then(response => {
                if (!response.ok) throw new Error('Archivo no encontrado');
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = rutaXml.split('/').pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                console.error('Error al descargar XML:', error);
                Swal.fire('Error', 'No se pudo descargar el archivo XML: ' + error.message, 'error');
            });
    }

    async function cancelarFactura(idFactura) {
        // Primer paso: Seleccionar motivo de cancelación
        const resultMotivo = await Swal.fire({
            title: '¿Cancelar Factura?',
            text: 'Esta acción cancelará la factura ante el SAT',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar',
            input: 'select',
            inputLabel: 'Motivo de cancelación',
            inputOptions: {
                '01': '01 - Comprobante emitido con errores sin relación',
                '02': '02 - Comprobante emitido con errores con relación',
                '03': '03 - No se llevó a cabo la operación',
                '04': '04 - Operación nominativa relacionada en una factura global'
            },
            inputPlaceholder: 'Selecciona un motivo',
            inputValidator: (value) => {
                if (!value) {
                    return 'Debes seleccionar un motivo de cancelación'
                }
            }
        });

        if (!resultMotivo.isConfirmed) return;

        const motivoSeleccionado = resultMotivo.value;
        let uuidSustitucion = null;

        // Si el motivo es 01, solicitar UUID de sustitución
        if (motivoSeleccionado === '01') {
            const resultUuid = await Swal.fire({
                title: 'UUID de Sustitución',
                text: 'El motivo 01 requiere el UUID de la factura que sustituye a la cancelada',
                icon: 'info',
                input: 'text',
                inputLabel: 'UUID de la factura de sustitución',
                inputPlaceholder: 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Cancelar Factura',
                cancelButtonText: 'Volver',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debes ingresar el UUID de sustitución';
                    }
                    // Validar formato UUID (8-4-4-4-12)
                    const uuidPattern = /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/;
                    if (!uuidPattern.test(value)) {
                        return 'El formato del UUID no es válido';
                    }
                }
            });

            if (!resultUuid.isConfirmed) return;
            uuidSustitucion = resultUuid.value;
        }

        // Mostrar indicador de carga
        Swal.fire({
            title: 'Cancelando Factura...',
            html: `
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p>Enviando solicitud de cancelación al SAT</p>
                    <small class="text-muted">Este proceso puede tardar unos segundos...</small>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false
        });

        try {
            const response = await fetch('core/cancelar-factura.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_factura: idFactura,
                    motivo: motivoSeleccionado,
                    uuid_sustitucion: uuidSustitucion
                })
            });

            const resultado = await response.json();

            if (resultado.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Factura Cancelada!',
                    html: `
                        <div class="text-start">
                            <p><strong>${resultado.message}</strong></p>
                            ${resultado.detalle ? `<p class="text-muted small">${resultado.detalle}</p>` : ''}
                            ${resultado.status_code ? `<p class="small"><strong>Código SAT:</strong> ${resultado.status_code}</p>` : ''}
                        </div>
                    `,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    cargarFacturas(); // Recargar lista de facturas
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo cancelar',
                    html: `
                        <div class="text-start">
                            <p><strong>${resultado.message}</strong></p>
                            ${resultado.detalle ? `<p class="text-muted small">${resultado.detalle}</p>` : ''}
                            ${resultado.status_code ? `<p class="small"><strong>Código:</strong> ${resultado.status_code}</p>` : ''}
                            ${resultado.fault_code ? `<p class="small"><strong>Código de error:</strong> ${resultado.fault_code}</p>` : ''}
                            ${resultado.debug_structure ? `<details class="mt-3"><summary class="small text-muted">Ver detalles técnicos</summary><pre class="text-start small mt-2" style="max-height: 200px; overflow: auto;">${resultado.debug_structure}</pre></details>` : ''}
                        </div>
                    `,
                    confirmButtonText: 'Entendido',
                    footer: '<small>Consulta la documentación de Finkok para más detalles sobre el error</small>'
                });
                
                // Log en consola para debugging
                console.error('Error detallado de cancelación:', resultado);
            }
        } catch (error) {
            console.error('Error al cancelar factura:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de Conexión',
                text: 'No se pudo conectar con el servidor. Por favor intenta nuevamente.',
                confirmButtonText: 'Aceptar'
            });
        }
    }

    function aplicarFiltrosSimples() {
        paginaActual = 1;
        cargarFacturas();
    }

    function toggleVista(tipo) {
        console.log('Cambiando vista a:', tipo);
    }

    function exportarFacturas() {
        alert('Iniciando exportación de facturas...');
    }

    function aplicarFiltros() {
        const filtros = {
            fechaDesde: document.getElementById('fechaDesdeAvanzado').value,
            fechaHasta: document.getElementById('fechaHastaAvanzado').value,
            montoDesde: document.getElementById('montoDesde').value,
            montoHasta: document.getElementById('montoHasta').value,
            estados: {
                vigente: document.getElementById('estadoVigente').checked,
                pagada: document.getElementById('estadoPagada').checked,
                pendiente: document.getElementById('estadoPendiente').checked,
                cancelada: document.getElementById('estadoCancelada').checked
            },
            sucursales: {
                centro: document.getElementById('sucursalCentro').checked,
                norte: document.getElementById('sucursalNorte').checked,
                sur: document.getElementById('sucursalSur').checked,
                oriente: document.getElementById('sucursalOriente').checked
            }
        };

        console.log('Aplicando filtros avanzados:', filtros);
        bootstrap.Modal.getInstance(document.getElementById('filtrosAvanzadosModal')).hide();
        alert('Filtros aplicados exitosamente');
    }

    function limpiarFiltros() {
        document.getElementById('formFiltrosAvanzados').reset();
        ['estadoVigente', 'estadoPagada', 'estadoPendiente', 'sucursalCentro', 'sucursalNorte', 'sucursalSur', 'sucursalOriente'].forEach(id => {
            document.getElementById(id).checked = true;
        });
    }

    /**
     * Generar PDF de una factura
     */
    function generarPDF(idFactura) {
        Swal.fire({
            title: 'Generando PDF...',
            text: 'Por favor espera mientras se genera el PDF de la factura',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Abrir el PDF en una nueva ventana/pestaña
        const url = `core/generar-pdf-factura.php?id_factura=${idFactura}&guardar=1`;
        const ventana = window.open(url, '_blank');
        
        // Cerrar el loading después de un momento
        setTimeout(() => {
            Swal.close();
            if (!ventana) {
                Swal.fire('Advertencia', 'El navegador bloqueó la ventana emergente. Por favor, permite las ventanas emergentes para este sitio.', 'warning');
            } else {
                // Recargar las facturas para actualizar el botón
                cargarFacturas();
            }
        }, 1500);
    }

    /**
     * Descargar PDF existente
     */
    function descargarPDF(rutaPdf) {
        if (!rutaPdf || rutaPdf === '') {
            Swal.fire('Error', 'No se encontró la ruta del archivo PDF', 'error');
            return;
        }

        // Construir la ruta completa
        const rutaCompleta = rutaPdf.startsWith('http') ? rutaPdf : `${rutaPdf}`;
        
        // Crear un enlace temporal y hacer clic en él
        fetch(rutaCompleta)
            .then(response => {
                if (!response.ok) throw new Error('Archivo no encontrado');
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = rutaPdf.split('/').pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                console.error('Error al descargar PDF:', error);
                Swal.fire('Error', 'No se pudo descargar el archivo PDF: ' + error.message, 'error');
            });
    }

</script>