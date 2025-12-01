<?php
// pages/config-productos.inc.php
?>
<div class="bg-info text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-4">
                        <i class="bi bi-boxes display-6"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-2">Configuración de Agrupación de Productos</h1>
                        <p class="lead mb-0 opacity-90">Define cómo se mostrarán los productos en las facturas</p>
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
                        <i class="bi bi-gear me-2 text-info"></i>
                        Configuración de Agrupación
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="configProductosForm">
                        <!-- Modo de Agrupación -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-info mb-3">
                                    <i class="bi bi-layers me-2"></i>Modo de Agrupación
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card border-2 agrupacion-card" data-mode="desglosado">
                                            <div class="card-body text-center p-4">
                                                <input type="radio" name="modoAgrupacion" id="desglosado" value="desglosado" class="form-check-input mb-3" checked>
                                                <i class="bi bi-list-ul display-4 text-info d-block mb-3"></i>
                                                <h6 class="fw-bold">Desglosado</h6>
                                                <p class="text-muted mb-0">Cada producto aparece en una línea separada con sus detalles completos</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-2 agrupacion-card" data-mode="agrupado">
                                            <div class="card-body text-center p-4">
                                                <input type="radio" name="modoAgrupacion" id="agrupado" value="agrupado" class="form-check-input mb-3">
                                                <i class="bi bi-collection display-4 text-info d-block mb-3"></i>
                                                <h6 class="fw-bold">Agrupado</h6>
                                                <p class="text-muted mb-0">Productos similares se combinan mostrando solo la cantidad total</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Criterios de Agrupación -->
                        <div class="row mb-4" id="criteriosAgrupacion" style="display: none;">
                            <div class="col-12">
                                <h6 class="fw-bold text-info mb-3">
                                    <i class="bi bi-funnel me-2"></i>Criterios de Agrupación
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="agruparPorNombre" checked>
                                            <label class="form-check-label fw-semibold" for="agruparPorNombre">
                                                Agrupar por Nombre del Producto
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="agruparPorPrecio" checked>
                                            <label class="form-check-label fw-semibold" for="agruparPorPrecio">
                                                Agrupar por Precio Unitario
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="agruparPorCategoria">
                                            <label class="form-check-label fw-semibold" for="agruparPorCategoria">
                                                Agrupar por Categoría
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="agruparPorSKU">
                                            <label class="form-check-label fw-semibold" for="agruparPorSKU">
                                                Agrupar por SKU/Código
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Configuración de Visualización -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-info mb-3">
                                    <i class="bi bi-eye me-2"></i>Configuración de Visualización
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="formatoCantidad" class="form-label fw-semibold">Formato de Cantidad</label>
                                        <select class="form-select" id="formatoCantidad">
                                            <option value="decimal" selected>Decimal (1.50)</option>
                                            <option value="entero">Entero (2)</option>
                                            <option value="fraccion">Fracción (1 1/2)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="decimalesCantidad" class="form-label fw-semibold">Decimales en Cantidad</label>
                                        <select class="form-select" id="decimalesCantidad">
                                            <option value="0">0 decimales</option>
                                            <option value="1">1 decimal</option>
                                            <option value="2" selected>2 decimales</option>
                                            <option value="3">3 decimales</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ordenProductos" class="form-label fw-semibold">Orden de Productos</label>
                                        <select class="form-select" id="ordenProductos">
                                            <option value="orden_ticket" selected>Orden del Ticket</option>
                                            <option value="alfabetico">Orden Alfabético</option>
                                            <option value="precio_asc">Precio Ascendente</option>
                                            <option value="precio_desc">Precio Descendente</option>
                                            <option value="categoria">Por Categoría</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="separadorMiles" class="form-label fw-semibold">Separador de Miles</label>
                                        <select class="form-select" id="separadorMiles">
                                            <option value="," selected>Coma (1,000.00)</option>
                                            <option value=".">Punto (1.000,00)</option>
                                            <option value=" ">Espacio (1 000.00)</option>
                                            <option value="">Sin separador (1000.00)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Adicional -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-info mb-3">
                                    <i class="bi bi-info-square me-2"></i>Información Adicional en Productos
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarSKU" checked>
                                            <label class="form-check-label fw-semibold" for="mostrarSKU">
                                                Mostrar SKU/Código
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarCategoria">
                                            <label class="form-check-label fw-semibold" for="mostrarCategoria">
                                                Mostrar Categoría
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarDescripcionLarga">
                                            <label class="form-check-label fw-semibold" for="mostrarDescripcionLarga">
                                                Mostrar Descripción Larga
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarUnidadMedida" checked>
                                            <label class="form-check-label fw-semibold" for="mostrarUnidadMedida">
                                                Mostrar Unidad de Medida
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarDescuentos" checked>
                                            <label class="form-check-label fw-semibold" for="mostrarDescuentos">
                                                Mostrar Descuentos Aplicados
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="mostrarImpuestos" checked>
                                            <label class="form-check-label fw-semibold" for="mostrarImpuestos">
                                                Desglosar Impuestos por Producto
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vista Previa -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-info mb-3">
                                    <i class="bi bi-eye me-2"></i>Vista Previa
                                </h6>
                                <div class="border rounded-3 p-4 bg-light">
                                    <div id="vistaPrevia" class="bg-white p-4 rounded shadow-sm">
                                        <h6 class="fw-bold mb-3">Productos en Factura</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered" id="tablaPreview">
                                                <thead class="table-info">
                                                    <tr>
                                                        <th>Descripción</th>
                                                        <th class="text-end">Cantidad</th>
                                                        <th class="text-end">Precio Unit.</th>
                                                        <th class="text-end">Importe</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="productosPreview">
                                                    <!-- Los productos se cargarán dinámicamente -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Configuración por Sucursal -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-info mb-3">
                                    <i class="bi bi-building me-2"></i>Configuración por Sucursal
                                </h6>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="configuracionGlobal" checked>
                                    <label class="form-check-label fw-semibold" for="configuracionGlobal">
                                        Aplicar configuración a todas las sucursales
                                    </label>
                                </div>
                                <div id="sucursalesEspecificas" style="display: none;">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Sucursal</th>
                                                    <th>Modo</th>
                                                    <th>Configuración Específica</th>
                                                </tr>
                                            </thead>
                                            <tbody id="listaSucursales">
                                                <!-- Las sucursales se cargarán dinámicamente -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex gap-3 justify-content-end">
                            <button type="button" class="btn btn-outline-info" onclick="previsualizarConfiguracion()">
                                <i class="bi bi-eye me-2"></i>Vista Previa Completa
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetearConfiguracion()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Valores por Defecto
                            </button>
                            <button type="button" class="btn btn-info" onclick="guardarConfigProductos()">
                                <i class="bi bi-check-circle me-2"></i>Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Ayuda y Ejemplos -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-lightbulb me-2 text-warning"></i>
                        Ejemplos de Agrupación
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <strong class="text-info">Modo Desglosado:</strong>
                        <div class="border rounded mt-2 p-2 bg-light">
                            <small class="d-block">• Coca Cola 600ml - 2 pzas</small>
                            <small class="d-block">• Coca Cola 600ml - 1 pza</small>
                            <small class="d-block">• Pepsi 500ml - 3 pzas</small>
                        </div>
                    </div>
                    <div class="mb-4">
                        <strong class="text-info">Modo Agrupado:</strong>
                        <div class="border rounded mt-2 p-2 bg-light">
                            <small class="d-block">• Coca Cola 600ml - 3 pzas</small>
                            <small class="d-block">• Pepsi 500ml - 3 pzas</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-graph-up me-2 text-success"></i>
                        Estadísticas
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 text-center">
                        <div class="col-12">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3 mb-3">
                                <i class="bi bi-receipt text-primary fs-3"></i>
                                <h6 class="fw-bold text-primary mb-1" id="promProductos">0</h6>
                                <small class="text-muted">Productos promedio por factura</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-box text-success fs-4"></i>
                                <h6 class="fw-bold text-success mb-1" id="totalProductos">0</h6>
                                <small class="text-muted">Productos únicos</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-layers text-warning fs-4"></i>
                                <h6 class="fw-bold text-warning mb-1" id="factorAgrupacion">0%</h6>
                                <small class="text-muted">Factor agrupación</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-white border-0 py-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-question-circle me-2 text-primary"></i>
                        Recomendaciones
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <small><strong>Tickets Grandes:</strong> Use agrupación para simplificar facturas con muchos productos</small>
                    </div>
                    <div class="alert alert-success border-0 mb-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <small><strong>Tickets Pequeños:</strong> El modo desglosado ofrece mayor detalle</small>
                    </div>
                    <div class="alert alert-warning border-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small><strong>SAT:</strong> Ambos formatos son válidos fiscalmente</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Datos de ejemplo para la vista previa
const productosEjemplo = [
    { nombre: 'Coca Cola 600ml', sku: 'COC001', cantidad: 2, precio: 25.00, categoria: 'Bebidas' },
    { nombre: 'Coca Cola 600ml', sku: 'COC001', cantidad: 1, precio: 25.00, categoria: 'Bebidas' },
    { nombre: 'Pepsi 500ml', sku: 'PEP001', cantidad: 3, precio: 22.00, categoria: 'Bebidas' },
    { nombre: 'Agua Natural 1L', sku: 'AGU001', cantidad: 1, precio: 15.00, categoria: 'Bebidas' },
    { nombre: 'Papas Fritas', sku: 'PAP001', cantidad: 2, precio: 35.00, categoria: 'Snacks' }
];

document.addEventListener('DOMContentLoaded', function() {
    cargarConfiguracionActual();
    cargarEstadisticas();
    cargarSucursales();
    actualizarVistaPrevia();
    
    // Event listeners
    document.querySelectorAll('input[name="modoAgrupacion"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleCriteriosAgrupacion();
            actualizarVistaPrevia();
            actualizarCardSelection();
        });
    });
    
    // Event listeners para actualizar vista previa
    const campos = ['formatoCantidad', 'decimalesCantidad', 'ordenProductos', 'separadorMiles'];
    campos.forEach(campo => {
        document.getElementById(campo).addEventListener('change', actualizarVistaPrevia);
    });
    
    const checkboxes = ['agruparPorNombre', 'agruparPorPrecio', 'mostrarSKU', 'mostrarCategoria', 'mostrarDescripcionLarga', 'mostrarUnidadMedida', 'mostrarDescuentos', 'mostrarImpuestos'];
    checkboxes.forEach(checkbox => {
        document.getElementById(checkbox).addEventListener('change', actualizarVistaPrevia);
    });
    
    // Configuración por sucursal
    document.getElementById('configuracionGlobal').addEventListener('change', function() {
        const sucursalesDiv = document.getElementById('sucursalesEspecificas');
        sucursalesDiv.style.display = this.checked ? 'none' : 'block';
    });
    
    // Selección visual de cards
    document.querySelectorAll('.agrupacion-card').forEach(card => {
        card.addEventListener('click', function() {
            const mode = this.dataset.mode;
            document.getElementById(mode).checked = true;
            toggleCriteriosAgrupacion();
            actualizarVistaPrevia();
            actualizarCardSelection();
        });
    });
    
    actualizarCardSelection();
});

function cargarConfiguracionActual() {
    fetch('./core/obtener-config-productos.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const config = data.config;
                document.getElementById(config.modo_agrupacion || 'desglosado').checked = true;
                document.getElementById('formatoCantidad').value = config.formato_cantidad || 'decimal';
                document.getElementById('decimalesCantidad').value = config.decimales_cantidad || '2';
                document.getElementById('ordenProductos').value = config.orden_productos || 'orden_ticket';
                document.getElementById('separadorMiles').value = config.separador_miles || ',';
                
                // Checkboxes
                const checkboxes = {
                    'agruparPorNombre': config.agrupar_por_nombre,
                    'agruparPorPrecio': config.agrupar_por_precio,
                    'mostrarSKU': config.mostrar_sku,
                    'mostrarCategoria': config.mostrar_categoria,
                    'mostrarDescripcionLarga': config.mostrar_descripcion_larga,
                    'mostrarUnidadMedida': config.mostrar_unidad_medida,
                    'mostrarDescuentos': config.mostrar_descuentos,
                    'mostrarImpuestos': config.mostrar_impuestos
                };
                
                Object.keys(checkboxes).forEach(id => {
                    document.getElementById(id).checked = checkboxes[id] === '1';
                });
                
                toggleCriteriosAgrupacion();
                actualizarCardSelection();
                actualizarVistaPrevia();
            }
        })
        .catch(error => console.error('Error cargando configuración:', error));
}

function cargarEstadisticas() {
    fetch('./core/estadisticas-productos.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('promProductos').textContent = data.promedio_productos || '0';
                document.getElementById('totalProductos').textContent = data.total_productos || '0';
                document.getElementById('factorAgrupacion').textContent = (data.factor_agrupacion || '0') + '%';
            }
        })
        .catch(error => console.error('Error cargando estadísticas:', error));
}

function cargarSucursales() {
    fetch('./core/obtener-sucursales.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.sucursales) {
                const tbody = document.getElementById('listaSucursales');
                tbody.innerHTML = data.sucursales.map(sucursal => `
                    <tr>
                        <td>${sucursal.nombre}</td>
                        <td>
                            <select class="form-select form-select-sm" data-sucursal="${sucursal.id}">
                                <option value="global">Usar configuración global</option>
                                <option value="desglosado">Desglosado</option>
                                <option value="agrupado">Agrupado</option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-outline-primary btn-sm" onclick="configurarSucursal(${sucursal.id}, '${sucursal.nombre}')">
                                <i class="bi bi-gear"></i> Configurar
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
        })
        .catch(error => console.error('Error cargando sucursales:', error));
}

function toggleCriteriosAgrupacion() {
    const agrupado = document.getElementById('agrupado').checked;
    const criteriosDiv = document.getElementById('criteriosAgrupacion');
    criteriosDiv.style.display = agrupado ? 'block' : 'none';
}

function actualizarCardSelection() {
    document.querySelectorAll('.agrupacion-card').forEach(card => {
        const mode = card.dataset.mode;
        const radio = document.getElementById(mode);
        
        if (radio.checked) {
            card.classList.add('border-info');
            card.classList.add('bg-info');
            card.classList.add('bg-opacity-10');
        } else {
            card.classList.remove('border-info');
            card.classList.remove('bg-info');
            card.classList.remove('bg-opacity-10');
        }
    });
}

function actualizarVistaPrevia() {
    const modoAgrupacion = document.querySelector('input[name="modoAgrupacion"]:checked').value;
    const formatoCantidad = document.getElementById('formatoCantidad').value;
    const decimalesCantidad = parseInt(document.getElementById('decimalesCantidad').value);
    const mostrarSKU = document.getElementById('mostrarSKU').checked;
    const mostrarCategoria = document.getElementById('mostrarCategoria').checked;
    
    let productos = [...productosEjemplo];
    
    // Agrupar si es necesario
    if (modoAgrupacion === 'agrupado') {
        productos = agruparProductos(productos);
    }
    
    // Ordenar productos
    const orden = document.getElementById('ordenProductos').value;
    switch (orden) {
        case 'alfabetico':
            productos.sort((a, b) => a.nombre.localeCompare(b.nombre));
            break;
        case 'precio_asc':
            productos.sort((a, b) => a.precio - b.precio);
            break;
        case 'precio_desc':
            productos.sort((a, b) => b.precio - a.precio);
            break;
        case 'categoria':
            productos.sort((a, b) => a.categoria.localeCompare(b.categoria));
            break;
    }
    
    const tbody = document.getElementById('productosPreview');
    tbody.innerHTML = productos.map(producto => {
        let descripcion = producto.nombre;
        
        if (mostrarSKU) {
            descripcion += `<br><small class="text-muted">SKU: ${producto.sku}</small>`;
        }
        
        if (mostrarCategoria) {
            descripcion += `<br><small class="text-muted">Categoría: ${producto.categoria}</small>`;
        }
        
        const cantidad = formatearCantidad(producto.cantidad, formatoCantidad, decimalesCantidad);
        const precio = formatearPrecio(producto.precio);
        const importe = formatearPrecio(producto.cantidad * producto.precio);
        
        return `
            <tr>
                <td>${descripcion}</td>
                <td class="text-end">${cantidad}</td>
                <td class="text-end">${precio}</td>
                <td class="text-end">${importe}</td>
            </tr>
        `;
    }).join('');
}

function agruparProductos(productos) {
    const agrupados = {};
    
    productos.forEach(producto => {
        const agruparPorNombre = document.getElementById('agruparPorNombre').checked;
        const agruparPorPrecio = document.getElementById('agruparPorPrecio').checked;
        
        let clave = '';
        if (agruparPorNombre) clave += producto.nombre;
        if (agruparPorPrecio) clave += '_' + producto.precio;
        
        if (!agrupados[clave]) {
            agrupados[clave] = { ...producto };
        } else {
            agrupados[clave].cantidad += producto.cantidad;
        }
    });
    
    return Object.values(agrupados);
}

function formatearCantidad(cantidad, formato, decimales) {
    switch (formato) {
        case 'entero':
            return Math.round(cantidad).toString();
        case 'fraccion':
            // Implementación básica de fracción
            const entero = Math.floor(cantidad);
            const decimal = cantidad - entero;
            if (decimal === 0) return entero.toString();
            if (decimal === 0.5) return entero > 0 ? `${entero} 1/2` : '1/2';
            return cantidad.toFixed(decimales);
        default: // decimal
            return cantidad.toFixed(decimales);
    }
}

function formatearPrecio(precio) {
    const separador = document.getElementById('separadorMiles').value;
    let formatted = precio.toFixed(2);
    
    if (separador) {
        const parts = formatted.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, separador);
        formatted = parts.join('.');
    }
    
    return '$' + formatted;
}

function previsualizarConfiguracion() {
    Swal.fire({
        title: 'Vista Previa de Configuración',
        html: document.getElementById('vistaPrevia').outerHTML,
        width: '90%',
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
            popup: 'swal-wide'
        }
    });
}

function resetearConfiguracion() {
    Swal.fire({
        title: '¿Restaurar Valores por Defecto?',
        text: 'Esta acción restablecerá toda la configuración a los valores predeterminados',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6c757d',
        cancelButtonColor: '#0dcaf0',
        confirmButtonText: 'Sí, resetear',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Resetear formulario a valores por defecto
            document.getElementById('desglosado').checked = true;
            document.getElementById('formatoCantidad').value = 'decimal';
            document.getElementById('decimalesCantidad').value = '2';
            document.getElementById('ordenProductos').value = 'orden_ticket';
            document.getElementById('separadorMiles').value = ',';
            document.getElementById('configuracionGlobal').checked = true;
            
            // Resetear checkboxes
            const checkboxesDefecto = {
                'agruparPorNombre': true,
                'agruparPorPrecio': true,
                'mostrarSKU': true,
                'mostrarUnidadMedida': true,
                'mostrarDescuentos': true,
                'mostrarImpuestos': true,
                'agruparPorCategoria': false,
                'agruparPorSKU': false,
                'mostrarCategoria': false,
                'mostrarDescripcionLarga': false
            };
            
            Object.keys(checkboxesDefecto).forEach(id => {
                document.getElementById(id).checked = checkboxesDefecto[id];
            });
            
            toggleCriteriosAgrupacion();
            actualizarCardSelection();
            actualizarVistaPrevia();
            
            Swal.fire({
                icon: 'success',
                title: 'Configuración Reseteada',
                text: 'Se han restablecido los valores por defecto',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

function guardarConfigProductos() {
    const datos = {
        modo_agrupacion: document.querySelector('input[name="modoAgrupacion"]:checked').value,
        formato_cantidad: document.getElementById('formatoCantidad').value,
        decimales_cantidad: document.getElementById('decimalesCantidad').value,
        orden_productos: document.getElementById('ordenProductos').value,
        separador_miles: document.getElementById('separadorMiles').value,
        configuracion_global: document.getElementById('configuracionGlobal').checked ? '1' : '0',
        
        // Criterios de agrupación
        agrupar_por_nombre: document.getElementById('agruparPorNombre').checked ? '1' : '0',
        agrupar_por_precio: document.getElementById('agruparPorPrecio').checked ? '1' : '0',
        agrupar_por_categoria: document.getElementById('agruparPorCategoria').checked ? '1' : '0',
        agrupar_por_sku: document.getElementById('agruparPorSKU').checked ? '1' : '0',
        
        // Información adicional
        mostrar_sku: document.getElementById('mostrarSKU').checked ? '1' : '0',
        mostrar_categoria: document.getElementById('mostrarCategoria').checked ? '1' : '0',
        mostrar_descripcion_larga: document.getElementById('mostrarDescripcionLarga').checked ? '1' : '0',
        mostrar_unidad_medida: document.getElementById('mostrarUnidadMedida').checked ? '1' : '0',
        mostrar_descuentos: document.getElementById('mostrarDescuentos').checked ? '1' : '0',
        mostrar_impuestos: document.getElementById('mostrarImpuestos').checked ? '1' : '0'
    };
    
    Swal.fire({
        title: 'Guardando configuración...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            
            fetch('./core/guardar-config-productos.php', {
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
                        text: 'La configuración de productos se ha actualizado correctamente.',
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

function configurarSucursal(id, nombre) {
    Swal.fire({
        title: `Configurar: ${nombre}`,
        text: 'Esta funcionalidad permitirá configurar reglas específicas para esta sucursal',
        icon: 'info',
        confirmButtonText: 'Entendido'
    });
}
</script>

<style>
.agrupacion-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.agrupacion-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.swal-wide {
    width: 90% !important;
}
</style>