<?php
// pages/config-folios.inc.php
?>
<div class="bg-warning text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                        <i class="bi bi-hash display-6"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-2">Configuración de Folios y Series</h1>
                        <p class="lead mb-0 opacity-90">Administra la numeración y series para tus facturas electrónicas</p>
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
                        <i class="bi bi-list-ol me-2 text-warning"></i>
                        Configuración de Series
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Series Existentes -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-warning mb-0">
                                    <i class="bi bi-collection me-2"></i>Series Registradas
                                </h6>
                                <button class="btn btn-warning btn-sm" onclick="mostrarModalNuevaSerie()">
                                    <i class="bi bi-plus-circle me-2"></i>Nueva Serie
                                </button>
                            </div>
                            
                            <div id="listaSeries" class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-warning">
                                        <tr>
                                            <th>Serie</th>
                                            <th>Descripción</th>
                                            <th>Folio Actual</th>
                                            <th>Siguiente</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Las series se cargarán dinámicamente -->
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox display-6"></i>
                                                <p class="mb-0">Cargando series...</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración Global -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold text-warning mb-3">
                                <i class="bi bi-gear me-2"></i>Configuración Global de Folios
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="formatoFolio" class="form-label fw-semibold">Formato de Folio</label>
                                    <select class="form-select" id="formatoFolio">
                                        <option value="SERIE-000000" selected>SERIE-000000</option>
                                        <option value="SERIE000000">SERIE000000</option>
                                        <option value="000000-SERIE">000000-SERIE</option>
                                        <option value="000000">Solo Número</option>
                                    </select>
                                    <small class="text-muted">Ejemplo: A-000001, A000001, 000001-A</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="longitudFolio" class="form-label fw-semibold">Longitud del Folio</label>
                                    <select class="form-select" id="longitudFolio">
                                        <option value="4">4 dígitos (0001)</option>
                                        <option value="5">5 dígitos (00001)</option>
                                        <option value="6" selected>6 dígitos (000001)</option>
                                        <option value="7">7 dígitos (0000001)</option>
                                        <option value="8">8 dígitos (00000001)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="reinicioAnual" class="form-label fw-semibold">Reinicio de Numeración</label>
                                    <select class="form-select" id="reinicioAnual">
                                        <option value="no" selected>No reiniciar</option>
                                        <option value="anual">Reiniciar cada año</option>
                                        <option value="mensual">Reiniciar cada mes</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="prefijo" class="form-label fw-semibold">Prefijo por Año</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="prefijoAno">
                                        <label class="form-check-label" for="prefijoAno">
                                            Incluir año en el folio (Ej: 2025-A-000001)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vista Previa del Formato -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold text-warning mb-3">
                                <i class="bi bi-eye me-2"></i>Vista Previa del Formato
                            </h6>
                            <div class="alert alert-warning border-0">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-info-circle me-3 fs-4"></i>
                                    <div>
                                        <strong>Formato actual:</strong>
                                        <span id="previewFormato" class="ms-2 font-monospace">A-000001</span>
                                        <br>
                                        <small class="text-muted">
                                            Próximo folio: <span id="previewProximo" class="font-monospace">A-000002</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="d-flex gap-3 justify-content-end">
                        <button type="button" class="btn btn-outline-warning" onclick="resetearNumeracion()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Resetear Numeración
                        </button>
                        <button type="button" class="btn btn-warning" onclick="guardarConfigFolios()">
                            <i class="bi bi-check-circle me-2"></i>Guardar Configuración
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Estadísticas -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-graph-up me-2 text-info"></i>
                        Estadísticas de Folios
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-file-text text-primary fs-3"></i>
                                <h6 class="fw-bold text-primary mb-1" id="totalFacturas">0</h6>
                                <small class="text-muted">Total Facturas</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-calendar-day text-success fs-3"></i>
                                <h6 class="fw-bold text-success mb-1" id="facturasHoy">0</h6>
                                <small class="text-muted">Facturas Hoy</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-calendar-month text-warning fs-3"></i>
                                <h6 class="fw-bold text-warning mb-1" id="facturasMes">0</h6>
                                <small class="text-muted">Este Mes</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-calendar-year text-info fs-3"></i>
                                <h6 class="fw-bold text-info mb-1" id="facturasAno">0</h6>
                                <small class="text-muted">Este Año</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 py-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-lightbulb me-2 text-success"></i>
                        Recomendaciones
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-success border-0 mb-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <small><strong>Serie por Sucursal:</strong> Usa series diferentes para cada ubicación (A, B, C)</small>
                    </div>
                    <div class="alert alert-info border-0 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <small><strong>Numeración Continua:</strong> Mantén secuencia sin saltos para cumplir con el SAT</small>
                    </div>
                    <div class="alert alert-warning border-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small><strong>Respaldo:</strong> Respalda tu configuración antes de hacer cambios importantes</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Serie -->
<div class="modal fade" id="modalNuevaSerie" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2 text-warning"></i>Nueva Serie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaSerie">
                    <div class="mb-3">
                        <label for="nuevaSerie" class="form-label fw-semibold">Serie</label>
                        <input type="text" class="form-control" id="nuevaSerie" placeholder="A, B, FAC, etc." maxlength="10">
                        <small class="text-muted">Máximo 10 caracteres, sin espacios</small>
                    </div>
                    <div class="mb-3">
                        <label for="nuevaDescripcion" class="form-label fw-semibold">Descripción</label>
                        <input type="text" class="form-control" id="nuevaDescripcion" placeholder="Descripción de la serie">
                    </div>
                    <div class="mb-3">
                        <label for="nuevoFolioInicial" class="form-label fw-semibold">Folio Inicial</label>
                        <input type="number" class="form-control" id="nuevoFolioInicial" value="1" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="nuevaSucursal" class="form-label fw-semibold">Sucursal Asignada</label>
                        <select class="form-select" id="nuevaSucursal">
                            <option value="">Todas las sucursales</option>
                            <!-- Se cargarán las sucursales dinámicamente -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="crearNuevaSerie()">
                    <i class="bi bi-check-circle me-2"></i>Crear Serie
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Serie -->
<div class="modal fade" id="modalEditarSerie" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2 text-primary"></i>Editar Serie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarSerie">
                    <input type="hidden" id="editarSerieId">
                    <div class="mb-3">
                        <label for="editarSerie" class="form-label fw-semibold">Serie</label>
                        <input type="text" class="form-control" id="editarSerie" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editarDescripcion" class="form-label fw-semibold">Descripción</label>
                        <input type="text" class="form-control" id="editarDescripcion">
                    </div>
                    <div class="mb-3">
                        <label for="editarFolioActual" class="form-label fw-semibold">Folio Actual</label>
                        <input type="number" class="form-control" id="editarFolioActual" min="1">
                        <small class="text-danger">Cuidado: Cambiar el folio puede causar problemas de secuencia</small>
                    </div>
                    <div class="mb-3">
                        <label for="editarEstado" class="form-label fw-semibold">Estado</label>
                        <select class="form-select" id="editarEstado">
                            <option value="activa">Activa</option>
                            <option value="inactiva">Inactiva</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="actualizarSerie()">
                    <i class="bi bi-check-circle me-2"></i>Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarSeries();
    cargarEstadisticas();
    cargarSucursales();
    actualizarPreview();
    
    // Event listeners para actualizar preview
    document.getElementById('formatoFolio').addEventListener('change', actualizarPreview);
    document.getElementById('longitudFolio').addEventListener('change', actualizarPreview);
    document.getElementById('prefijoAno').addEventListener('change', actualizarPreview);
});

function cargarSeries() {
    fetch('./core/obtener-series.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#listaSeries tbody');
            if (data.success && data.series.length > 0) {
                tbody.innerHTML = data.series.map(serie => `
                    <tr>
                        <td><span class="badge bg-warning text-dark fw-bold">${serie.serie}</span></td>
                        <td>${serie.descripcion || '-'}</td>
                        <td><span class="font-monospace">${serie.folio_actual.toString().padStart(6, '0')}</span></td>
                        <td><span class="font-monospace text-primary">${(parseInt(serie.folio_actual) + 1).toString().padStart(6, '0')}</span></td>
                        <td>
                            <span class="badge ${serie.estado === 'activa' ? 'bg-success' : 'bg-secondary'}">
                                ${serie.estado === 'activa' ? 'Activa' : 'Inactiva'}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editarSerie(${serie.id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="eliminarSerie(${serie.id}, '${serie.serie}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                            No hay series configuradas
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error cargando series:', error);
        });
}

function cargarEstadisticas() {
    fetch('./core/estadisticas-folios.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalFacturas').textContent = data.total || '0';
                document.getElementById('facturasHoy').textContent = data.hoy || '0';
                document.getElementById('facturasMes').textContent = data.mes || '0';
                document.getElementById('facturasAno').textContent = data.ano || '0';
            }
        })
        .catch(error => console.error('Error cargando estadísticas:', error));
}

function cargarSucursales() {
    fetch('./core/obtener-sucursales.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('nuevaSucursal');
            if (data.success && data.sucursales) {
                data.sucursales.forEach(sucursal => {
                    const option = document.createElement('option');
                    option.value = sucursal.id;
                    option.textContent = sucursal.nombre;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error cargando sucursales:', error));
}

function actualizarPreview() {
    const formato = document.getElementById('formatoFolio').value;
    const longitud = parseInt(document.getElementById('longitudFolio').value);
    const prefijoAno = document.getElementById('prefijoAno').checked;
    
    let preview = formato.replace('SERIE', 'A').replace('000000', '0'.repeat(longitud));
    let proximo = formato.replace('SERIE', 'A').replace('000000', '0'.repeat(longitud));
    
    // Aplicar numeración
    if (formato.includes('000000')) {
        preview = preview.replace('0'.repeat(longitud), '1'.padStart(longitud, '0'));
        proximo = proximo.replace('0'.repeat(longitud), '2'.padStart(longitud, '0'));
    }
    
    // Agregar prefijo de año si está habilitado
    if (prefijoAno) {
        const year = new Date().getFullYear();
        preview = `${year}-${preview}`;
        proximo = `${year}-${proximo}`;
    }
    
    document.getElementById('previewFormato').textContent = preview;
    document.getElementById('previewProximo').textContent = proximo;
}

function mostrarModalNuevaSerie() {
    const modal = new bootstrap.Modal(document.getElementById('modalNuevaSerie'));
    document.getElementById('formNuevaSerie').reset();
    document.getElementById('nuevoFolioInicial').value = '1';
    modal.show();
}

function crearNuevaSerie() {
    const serie = document.getElementById('nuevaSerie').value.trim().toUpperCase();
    const descripcion = document.getElementById('nuevaDescripcion').value.trim();
    const folioInicial = parseInt(document.getElementById('nuevoFolioInicial').value);
    const sucursal = document.getElementById('nuevaSucursal').value;
    
    if (!serie) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo Requerido',
            text: 'La serie es obligatoria'
        });
        return;
    }
    
    if (!/^[A-Z0-9]+$/.test(serie)) {
        Swal.fire({
            icon: 'warning',
            title: 'Formato Inválido',
            text: 'La serie solo puede contener letras y números sin espacios'
        });
        return;
    }
    
    const datos = {
        serie: serie,
        descripcion: descripcion,
        folio_inicial: folioInicial,
        id_sucursal: sucursal || null
    };
    
    fetch('./core/crear-serie.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Serie Creada',
                text: `La serie "${serie}" se creó correctamente`,
                timer: 2000,
                showConfirmButton: false
            });
            bootstrap.Modal.getInstance(document.getElementById('modalNuevaSerie')).hide();
            cargarSeries();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al crear la serie'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de Conexión',
            text: 'No se pudo conectar con el servidor'
        });
    });
}

function editarSerie(id) {
    fetch(`./core/obtener-serie.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const serie = data.serie;
                document.getElementById('editarSerieId').value = serie.id;
                document.getElementById('editarSerie').value = serie.serie;
                document.getElementById('editarDescripcion').value = serie.descripcion || '';
                document.getElementById('editarFolioActual').value = serie.folio_actual;
                document.getElementById('editarEstado').value = serie.estado;
                
                const modal = new bootstrap.Modal(document.getElementById('modalEditarSerie'));
                modal.show();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al cargar los datos de la serie'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de Conexión',
                text: 'No se pudo cargar la información de la serie'
            });
        });
}

function actualizarSerie() {
    const datos = {
        id: document.getElementById('editarSerieId').value,
        descripcion: document.getElementById('editarDescripcion').value.trim(),
        folio_actual: parseInt(document.getElementById('editarFolioActual').value),
        estado: document.getElementById('editarEstado').value
    };
    
    fetch('./core/actualizar-serie.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Serie Actualizada',
                text: 'Los datos de la serie se actualizaron correctamente',
                timer: 2000,
                showConfirmButton: false
            });
            bootstrap.Modal.getInstance(document.getElementById('modalEditarSerie')).hide();
            cargarSeries();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al actualizar la serie'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de Conexión',
            text: 'No se pudo conectar con el servidor'
        });
    });
}

function eliminarSerie(id, serie) {
    Swal.fire({
        title: '¿Eliminar Serie?',
        text: `¿Está seguro que desea eliminar la serie "${serie}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('./core/eliminar-serie.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Serie Eliminada',
                        text: `La serie "${serie}" se eliminó correctamente`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    cargarSeries();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar la serie'
                    });
                }
            })
            .catch(error => {
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

function resetearNumeracion() {
    Swal.fire({
        title: '¿Resetear Numeración?',
        text: 'Esta acción reiniciará todos los folios a 1. ¿Está seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, resetear',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('./core/resetear-folios.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Numeración Reseteada',
                        text: 'Todos los folios se han reiniciado correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    cargarSeries();
                    cargarEstadisticas();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al resetear la numeración'
                    });
                }
            })
            .catch(error => {
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

function guardarConfigFolios() {
    const datos = {
        formato_folio: document.getElementById('formatoFolio').value,
        longitud_folio: document.getElementById('longitudFolio').value,
        reinicio_anual: document.getElementById('reinicioAnual').value,
        prefijo_ano: document.getElementById('prefijoAno').checked ? '1' : '0'
    };
    
    fetch('./core/guardar-config-folios.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Configuración Guardada',
                text: 'La configuración de folios se actualizó correctamente',
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
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de Conexión',
            text: 'No se pudo conectar con el servidor'
        });
    });
}
</script>