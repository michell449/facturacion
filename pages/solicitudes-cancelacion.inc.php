<!-- Página de Solicitudes de Cancelación -->
<div class="content-wrapper bg-light">
    <div class="container-fluid py-4">
        <!-- Título de la página -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-primary fw-bold">
                    <i class="bi bi-bell me-2"></i>
                    Solicitudes de Cancelación
                </h2>
                <p class="text-muted">Administra las solicitudes de cancelación de facturas</p>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="filtroEstado" class="form-label">Estado</label>
                                <select class="form-select" id="filtroEstado">
                                    <option value="pendiente" selected>Pendientes</option>
                                    <option value="aprobada">Aprobadas</option>
                                    <option value="rechazada">Rechazadas</option>
                                </select>
                            </div>
                            <div class="col-md-8 text-end">
                                <span class="badge bg-warning text-dark fs-6" id="contadorPendientes">
                                    <i class="bi bi-clock me-1"></i>
                                    <span id="numeroPendientes">0</span> pendientes
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div id="loadingSolicitudes" class="text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando solicitudes...</p>
        </div>

        <!-- Sin datos -->
        <div id="noDataSolicitudes" class="text-center py-5" style="display: none;">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
            <p class="mt-3 text-muted">No hay solicitudes en este estado</p>
        </div>

        <!-- Lista de solicitudes -->
        <div class="row" id="listaSolicitudes">
            <!-- Solicitudes dinámicas -->
        </div>

        <!-- Paginación -->
        <nav aria-label="Paginación" id="paginacionContainer" style="display: none;">
            <ul class="pagination justify-content-center" id="paginacion"></ul>
        </nav>
    </div>
</div>

<!-- Modal de detalle y respuesta -->
<div class="modal fade" id="modalRespuestaSolicitud" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-file-text me-2"></i>
                    Responder Solicitud
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idSolicitudRespuesta">
                
                <div class="alert alert-info">
                    <strong>Factura:</strong> <span id="modalFolio"></span><br>
                    <strong>Cliente:</strong> <span id="modalCliente"></span><br>
                    <strong>Total:</strong> <span id="modalTotal"></span>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Motivo del cliente:</label>
                    <p class="border p-3 bg-light" id="modalMotivo"></p>
                </div>
                
                <div class="mb-3">
                    <label for="respuestaAdmin" class="form-label fw-bold">Respuesta del administrador (opcional):</label>
                    <textarea class="form-control" id="respuestaAdmin" rows="3" placeholder="Ingresa una respuesta o comentario..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="responderSolicitud('rechazada')">
                    <i class="bi bi-x-circle me-1"></i>Rechazar
                </button>
                <button type="button" class="btn btn-success" onclick="responderSolicitud('aprobada')">
                    <i class="bi bi-check-circle me-1"></i>Aprobar Cancelación
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentEstado = 'pendiente';

document.addEventListener('DOMContentLoaded', function() {
    cargarSolicitudes();
    actualizarContador();
    
    // Event listener para filtro de estado
    document.getElementById('filtroEstado').addEventListener('change', function() {
        currentEstado = this.value;
        cargarSolicitudes(1);
    });
});

function actualizarContador() {
    fetch('core/contar-solicitudes-pendientes.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('numeroPendientes').textContent = data.total;
            }
        })
        .catch(error => console.error('Error al contar solicitudes:', error));
}

function cargarSolicitudes(pagina = 1) {
    currentPage = pagina;
    
    document.getElementById('loadingSolicitudes').style.display = 'block';
    document.getElementById('noDataSolicitudes').style.display = 'none';
    document.getElementById('listaSolicitudes').style.display = 'none';
    document.getElementById('paginacionContainer').style.display = 'none';
    
    const url = `core/listar-solicitudes-cancelacion.php?estado=${currentEstado}&pagina=${pagina}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            document.getElementById('loadingSolicitudes').style.display = 'none';
            
            if (data.success && data.solicitudes.length > 0) {
                mostrarSolicitudes(data.solicitudes);
                mostrarPaginacion(data.paginacion);
                document.getElementById('listaSolicitudes').style.display = 'block';
                document.getElementById('paginacionContainer').style.display = 'block';
            } else {
                document.getElementById('noDataSolicitudes').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadingSolicitudes').style.display = 'none';
            document.getElementById('noDataSolicitudes').style.display = 'block';
        });
}

function mostrarSolicitudes(solicitudes) {
    const container = document.getElementById('listaSolicitudes');
    container.innerHTML = '';
    
    solicitudes.forEach(solicitud => {
        const estadoBadge = obtenerBadgeEstado(solicitud.estado);
        
        const card = document.createElement('div');
        card.className = 'col-12 mb-3';
        card.innerHTML = `
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h5 class="mb-1">${solicitud.folio_completo}</h5>
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>${solicitud.fecha_solicitud_formatted}
                            </small>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Receptor:</strong> ${solicitud.razon_social_receptor}</p>
                            <p class="mb-1"><strong>RFC:</strong> ${solicitud.rfc_receptor}</p>
                            <small class="text-muted">${solicitud.correo_receptor || 'Sin correo'}</small>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-1"><strong>Total:</strong></p>
                            <h5 class="text-primary mb-0">${solicitud.total_formatted}</h5>
                        </div>
                        <div class="col-md-2 text-center">
                            ${estadoBadge}
                        </div>
                        <div class="col-md-1 text-end">
                            ${solicitud.estado === 'pendiente' ? `
                                <button class="btn btn-sm btn-primary" onclick="abrirModalRespuesta(${solicitud.id_solicitud}, '${solicitud.folio_completo}', '${solicitud.razon_social_receptor}', '${solicitud.total_formatted}', \`${solicitud.motivo.replace(/`/g, '\\`')}\`)">
                                    <i class="bi bi-check-square"></i>
                                </button>
                            ` : `
                                <button class="btn btn-sm btn-outline-secondary" onclick="verDetalleSolicitud(${solicitud.id_solicitud})">
                                    <i class="bi bi-eye"></i>
                                </button>
                            `}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Motivo:</strong>
                            <p class="text-muted mb-0">${solicitud.motivo}</p>
                        </div>
                    </div>
                    ${solicitud.respuesta_admin ? `
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <strong>Respuesta del admin:</strong> ${solicitud.respuesta_admin}
                                </div>
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

function obtenerBadgeEstado(estado) {
    const estados = {
        'pendiente': '<span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i>Pendiente</span>',
        'aprobada': '<span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Aprobada</span>',
        'rechazada': '<span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Rechazada</span>'
    };
    return estados[estado] || '<span class="badge bg-secondary">Desconocido</span>';
}

function mostrarPaginacion(paginacion) {
    const ul = document.getElementById('paginacion');
    ul.innerHTML = '';
    
    // Botón anterior
    const liPrev = document.createElement('li');
    liPrev.className = 'page-item' + (paginacion.pagina_actual === 1 ? ' disabled' : '');
    liPrev.innerHTML = paginacion.pagina_actual === 1 
        ? '<span class="page-link">Anterior</span>'
        : `<a class="page-link" href="#" onclick="cargarSolicitudes(${paginacion.pagina_actual - 1}); return false;">Anterior</a>`;
    ul.appendChild(liPrev);
    
    // Páginas
    for (let i = 1; i <= paginacion.total_paginas; i++) {
        const li = document.createElement('li');
        li.className = 'page-item' + (i === paginacion.pagina_actual ? ' active' : '');
        li.innerHTML = i === paginacion.pagina_actual
            ? `<span class="page-link">${i}</span>`
            : `<a class="page-link" href="#" onclick="cargarSolicitudes(${i}); return false;">${i}</a>`;
        ul.appendChild(li);
    }
    
    // Botón siguiente
    const liNext = document.createElement('li');
    liNext.className = 'page-item' + (paginacion.pagina_actual === paginacion.total_paginas ? ' disabled' : '');
    liNext.innerHTML = paginacion.pagina_actual === paginacion.total_paginas
        ? '<span class="page-link">Siguiente</span>'
        : `<a class="page-link" href="#" onclick="cargarSolicitudes(${paginacion.pagina_actual + 1}); return false;">Siguiente</a>`;
    ul.appendChild(liNext);
}

function abrirModalRespuesta(idSolicitud, folio, cliente, total, motivo) {
    document.getElementById('idSolicitudRespuesta').value = idSolicitud;
    document.getElementById('modalFolio').textContent = folio;
    document.getElementById('modalCliente').textContent = cliente;
    document.getElementById('modalTotal').textContent = total;
    document.getElementById('modalMotivo').textContent = motivo;
    document.getElementById('respuestaAdmin').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('modalRespuestaSolicitud'));
    modal.show();
}

function responderSolicitud(nuevoEstado) {
    const idSolicitud = document.getElementById('idSolicitudRespuesta').value;
    const respuestaAdmin = document.getElementById('respuestaAdmin').value;
    
    const textoAccion = nuevoEstado === 'aprobada' ? 'aprobar' : 'rechazar';
    
    Swal.fire({
        title: `¿${nuevoEstado === 'aprobada' ? 'Aprobar' : 'Rechazar'} solicitud?`,
        text: `¿Estás seguro de ${textoAccion} esta solicitud de cancelación?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: nuevoEstado === 'aprobada' ? '#198754' : '#dc3545',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('core/responder-solicitud-cancelacion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_solicitud: idSolicitud,
                    estado: nuevoEstado,
                    respuesta_admin: respuestaAdmin
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Actualizado!',
                        text: `La solicitud ha sido ${nuevoEstado === 'aprobada' ? 'aprobada' : 'rechazada'}`,
                        icon: 'success'
                    }).then(() => {
                        bootstrap.Modal.getInstance(document.getElementById('modalRespuestaSolicitud')).hide();
                        cargarSolicitudes(currentPage);
                        actualizarContador();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Ocurrió un error al procesar la solicitud', 'error');
            });
        }
    });
}
</script>
