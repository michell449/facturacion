<!-- SECCIÓN DE ENCABEZADO -->
<div class="container py-4">
    <!-- Fila para el título y el botón de regresar -->
    <div class="row mb-4 align-items-center">
        <div class="col-8">
            <h2 class="text-primary fw-bold mb-0">
                <i class="bi bi-building display-6 text-primary me-2"></i>
                Sucursales
            </h2>
            <p class="text-muted mb-0">Aquí puedes gestionar las sucursales de tu empresa.</p>
        </div>
        <div class="col-4 text-end">
            <button type="button" class="btn btn-outline-primary btn-lg rounded-3" onclick="window.history.back()">
                <i class="bi bi-arrow-left me-2"></i>Regresar
            </button>
        </div>
    </div>

    <!-- Estadísticas dinámicas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-info bg-opacity-10">
                <div class="card-body p-3 text-center" id="estadisticasSucursales">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-info me-2" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <span class="text-info">Cargando información de sucursales...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECCIÓN DE LISTA DE SUCURSALES-->
<div class="container py-2">
    <!-- Botón para agregar nueva sucursal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="text-secondary fw-semibold mb-0">
                    <i class="bi bi-building me-2"></i>Lista de Sucursales
                </h4>
                <a href="panel?pg=nueva-sucursal-admin" class="btn btn-primary rounded-3 fw-semibold">
                    <i class="bi bi-plus-circle me-2"></i>Nueva Sucursal
                </a>
            </div>
        </div>
    </div>
    
    <!-- Contenedor dinámico de sucursales -->
    <div class="row g-4" id="contenedorSucursales">
        <!-- Loading placeholder -->
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary me-2" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-muted mt-2">Cargando sucursales...</p>
        </div>
    </div>
</div>

<!-- Template para tarjeta de sucursal -->
<template id="templateSucursal">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4 h-100">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="col-12 text-primary">
                            <h6 class="fw-bold mb-0 sucursal-nombre">Sucursal</h6>
                            <small class="text-muted sucursal-codigo">Código</small>
                        </div>
                    </div>
                    <span class="badge sucursal-estado rounded-pill">Estado</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-geo-alt me-2"></i>Ubicación
                    </h6>
                    <p class="mb-0 sucursal-direccion">Dirección</p>
                    <small class="text-muted sucursal-ubicacion">Ciudad, Estado, CP</small>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-card-text me-2"></i>Información Fiscal
                    </h6>
                    <p class="mb-1"><strong>RFC:</strong> <span class="sucursal-rfc">RFC</span></p>
                    <p class="mb-0"><strong>Régimen:</strong> <span class="sucursal-regimen">Régimen</span></p>
                </div>

                <!-- Documentos Fiscales -->
                <div class="mb-3">
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-shield-check me-2"></i>Documentos Fiscales
                    </h6>

                    <!-- Constancia de Situación Fiscal -->
                    <div class="card bg-light border-0 rounded-3 mb-2">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0 small fw-bold">Constancia Fiscal</h6>
                                        <small class="text-muted csf-estado">No disponible</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-outline-primary btn-sm btn-csf">
                                        <i class="bi bi-upload"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sello Digital -->
                    <div class="card bg-light border-0 rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0 small fw-bold">Sello Digital</h6>
                                        <small class="text-muted sello-estado">No configurado</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-outline-info btn-sm btn-sello">
                                        <i class="bi bi-upload"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-primary btn-editar">
                        Editar
                    </button>
                    <button type="button" class="btn btn-danger btn-eliminar">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    async function cargarSucursales() {
        try {
            const response = await fetch('core/consultar-sucursales.php');
            const result = await response.json();
            
            if (result.success && result.data) {
                mostrarSucursales(result.data);
                actualizarEstadisticas(result.data);
            } else {
                mostrarError('Error al cargar sucursales: ' + (result.message || 'Respuesta inválida'));
            }
        } catch (error) {
            console.error('Error al cargar sucursales:', error);
            mostrarError('Error de conexión al cargar sucursales');
        }
    }
    
    // Mostrar las sucursales en el contenedor
    function mostrarSucursales(sucursales) {
        const contenedor = document.getElementById('contenedorSucursales');
        const template = document.getElementById('templateSucursal');
        
        if (!contenedor || !template) {
            console.error('No se encontraron elementos necesarios en el DOM');
            return;
        }
        
        // Limpiar contenedor
        contenedor.innerHTML = '';
        
        if (sucursales.length === 0) {
            mostrarSinSucursales();
            return;
        }
        
        sucursales.forEach(sucursal => {
            const clone = template.content.cloneNode(true);
            
            // Llenar datos básicos
            clone.querySelector('.sucursal-nombre').textContent = sucursal.razon_social || 'Sin nombre';
            clone.querySelector('.sucursal-codigo').textContent = sucursal.codigo_suc || 'Sin código';
            clone.querySelector('.sucursal-rfc').textContent = sucursal.rfc || 'Sin RFC';
            clone.querySelector('.sucursal-regimen').textContent = sucursal.reg_fiscal || 'Sin régimen';
            
            // Dirección
            let direccion = sucursal.calle || 'Sin dirección';
            if (sucursal.num_ext) {
                direccion += ` ${sucursal.num_ext}`;
            }
            if (sucursal.num_int) {
                direccion += ` Int. ${sucursal.num_int}`;
            }
            clone.querySelector('.sucursal-direccion').textContent = direccion;
            clone.querySelector('.sucursal-ubicacion').textContent = `${sucursal.colonia || ''}, CP ${sucursal.cp || ''}`;
            
            // Estado de la sucursal
            const estadoBadge = clone.querySelector('.sucursal-estado');
            if (sucursal.estatus == 1) {
                estadoBadge.textContent = 'Activa';
                estadoBadge.className = 'badge bg-success rounded-pill';
            } else {
                estadoBadge.textContent = 'Inactiva';
                estadoBadge.className = 'badge bg-secondary rounded-pill';
            }
            
            // Estado de documentos
            const csfEstado = clone.querySelector('.csf-estado');
            const selloEstado = clone.querySelector('.sello-estado');
            
            if (sucursal.csf) {
                csfEstado.textContent = 'Disponible';
                csfEstado.className = 'text-success';
            } else {
                csfEstado.textContent = 'No disponible';
                csfEstado.className = 'text-muted';
            }
            
            if (sucursal.sello) {
                selloEstado.textContent = 'Configurado';
                selloEstado.className = 'text-success';
            } else {
                selloEstado.textContent = 'No configurado';
                selloEstado.className = 'text-muted';
            }
            
            // Event listeners para botones
            const btnEditar = clone.querySelector('.btn-editar');
            const btnEliminar = clone.querySelector('.btn-eliminar');
            const btnCsf = clone.querySelector('.btn-csf');
            const btnSello = clone.querySelector('.btn-sello');
            
            btnEditar.addEventListener('click', () => editarSucursal(sucursal));
            btnEliminar.addEventListener('click', () => eliminarSucursal(sucursal));
            btnCsf.addEventListener('click', () => gestionarCSF(sucursal));
            btnSello.addEventListener('click', () => gestionarSello(sucursal));
            
            contenedor.appendChild(clone);
        });
    }
    
    // Actualizar estadísticas
    function actualizarEstadisticas(sucursales) {
        const estadisticas = document.getElementById('estadisticasSucursales');
        if (!estadisticas) return;
        
        const total = sucursales.length;
        const activas = sucursales.filter(s => s.estatus == 1).length;
        const inactivas = total - activas;
        
        estadisticas.innerHTML = `
            <h5 class="text-info mb-1">${total} ${total === 1 ? 'Sucursal' : 'Sucursales'}</h5>
            <small class="text-muted">${activas} ${activas === 1 ? 'Activa' : 'Activas'} • ${inactivas} ${inactivas === 1 ? 'Inactiva' : 'Inactivas'}</small>
        `;
    }
    
    // Mostrar mensaje cuando no hay sucursales
    function mostrarSinSucursales() {
        const contenedor = document.getElementById('contenedorSucursales');
        contenedor.innerHTML = `
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-building display-1 text-muted opacity-50"></i>
                        </div>
                        <h5 class="text-muted mb-3">No hay sucursales registradas</h5>
                        <p class="text-muted mb-4">Comienza registrando tu primera sucursal para gestionar la facturación</p>
                        <a href="panel?pg=nueva-sucursal-admin" class="btn btn-edit rounded-3 fw-semibold px-4 py-3">
                            <i class="bi bi-plus-circle me-2"></i>Registrar Primera Sucursal
                        </a>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Mostrar error
    function mostrarError(mensaje) {
        const contenedor = document.getElementById('contenedorSucursales');
        const estadisticas = document.getElementById('estadisticasSucursales');
        
        contenedor.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <div>${mensaje}</div>
                </div>
            </div>
        `;
        
        if (estadisticas) {
            estadisticas.innerHTML = `
                <h5 class="text-danger mb-1">Error</h5>
                <small class="text-muted">No se pudieron cargar las sucursales</small>
            `;
        }
    }
    
    // Funciones de acciones
    function editarSucursal(sucursal) {
        // Redireccionar a la página de edición con el ID de la sucursal usando el sistema de panel
        const sucursalId = sucursal.id_empresa;
        if (sucursalId) {
            window.location.href = `panel?pg=editar-sucursales&id=${sucursalId}`;
        } else {
            console.error('No se encontró el ID de la sucursal:', sucursal);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se puede editar esta sucursal porque no tiene un ID válido.',
                showConfirmButton: true
            });
        }
    }
    
    function eliminarSucursal(sucursal) {
        if (confirm(`¿Estás seguro de que quieres eliminar la sucursal "${sucursal.razon_social}"?\n\nEsta acción no se puede deshacer.`)) {
            console.log('Eliminando sucursal:', sucursal);
            alert('Funcionalidad de eliminación pendiente de implementar');
        }
    }
    
    function gestionarCSF(sucursal) {
        alert(`Gestionar Constancia de Situación Fiscal\nSucursal: ${sucursal.razon_social}\nEstado actual: ${sucursal.csf ? 'Disponible' : 'No disponible'}`);
    }
    
    function gestionarSello(sucursal) {
        alert(`Gestionar Sello Digital\nSucursal: ${sucursal.razon_social}\nEstado actual: ${sucursal.sello ? 'Configurado' : 'No configurado'}`);
    }
    
    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        cargarSucursales();
    });

    async function eliminarSucursal(sucursal) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: `Se eliminará la sucursal "${sucursal.razon_social} ${sucursal.codigo_suc}". ¡Esta acción no se puede deshacer!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            Swal.fire({
                title: 'Eliminando...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch('core/eliminar-sucursal.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id_empresa: sucursal.id_empresa
                    })
                });

                const result_api = await response.json();

                if (result_api.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminada',
                        text: result_api.message,
                        timer: 1000,
                        showConfirmButton: false
                    });
                    // Recargar la lista de sucursales
                    cargarSucursales();
                } else {
                    throw new Error(result_api.message || 'Error desconocido al eliminar.');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de eliminación',
                    text: error.message,
                    confirmButtonText: 'Entendido'
                });
            }
        }
    }
</script>