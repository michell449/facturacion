<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba Código Postal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Prueba Código Postal</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label for="codigoPostal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control" id="codigoPostal" maxlength="5" placeholder="Ingresa 5 dígitos">
                                <div id="cpStatus" class="form-text"></div>
                            </div>

                            <div class="mb-3">
                                <label for="municipio" class="form-label">Municipio</label>
                                <input type="text" class="form-control" id="municipio" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="estado" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="colonia" class="form-label">Colonia</label>
                                <select class="form-select" id="colonia">
                                    <option value="">Ingresa primero el código postal</option>
                                </select>
                            </div>

                            <div id="infoUbicacion" class="alert alert-info" style="display: none;">
                                <h6>Ubicación:</h6>
                                <p id="ubicacionTexto"></p>
                            </div>
                        </form>

                        <hr>
                        <h6>Códigos de Prueba:</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-primary" onclick="probarCP('06000')">06000</button>
                            <button class="btn btn-sm btn-outline-primary" onclick="probarCP('03100')">03100</button>
                            <button class="btn btn-sm btn-outline-primary" onclick="probarCP('11000')">11000</button>
                            <button class="btn btn-sm btn-outline-primary" onclick="probarCP('01000')">01000</button>
                            <button class="btn btn-sm btn-outline-primary" onclick="probarCP('15000')">15000</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    // Función para cargar datos del código postal
    async function cargarDatosCP(codigoPostal) {
        const selectColonias = document.getElementById('colonia');
        const statusDiv = document.getElementById('cpStatus');
        const infoUbicacion = document.getElementById('infoUbicacion');
        const ubicacionTexto = document.getElementById('ubicacionTexto');
        const municipioInput = document.getElementById('municipio');
        const estadoInput = document.getElementById('estado');

        console.log('Validando código postal:', codigoPostal);

        if (!selectColonias) {
            console.warn('Elemento colonia no encontrado');
            return;
        }

        // Mostrar loading
        selectColonias.innerHTML = '<option value="">Cargando colonias...</option>';
        statusDiv.textContent = 'Validando código postal...';
        statusDiv.className = 'form-text text-info';

        try {
            const response = await fetch('core/obtener-colonias-cp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    codigo_postal: codigoPostal
                })
            });

            const data = await response.json();
            console.log('Respuesta del servidor:', data);

            if (data.success && data.data) {
                const { municipio, estado, colonias } = data.data;
                
                // Llenar municipio y estado
                if (municipioInput) municipioInput.value = municipio || '';
                if (estadoInput) estadoInput.value = estado || '';

                // Llenar colonias
                selectColonias.innerHTML = '<option value="">Selecciona una colonia</option>';
                
                if (colonias && colonias.length > 0) {
                    colonias.forEach(colonia => {
                        const option = document.createElement('option');
                        option.value = colonia.d_asenta;
                        option.textContent = `${colonia.d_asenta} (${colonia.tipo_asenta})`;
                        selectColonias.appendChild(option);
                    });

                    statusDiv.textContent = `✓ CP válido - ${colonias.length} colonias encontradas`;
                    statusDiv.className = 'form-text text-success';

                    // Mostrar información de ubicación
                    ubicacionTexto.textContent = `${municipio}, ${estado}`;
                    infoUbicacion.style.display = 'block';
                } else {
                    selectColonias.innerHTML = '<option value="">No se encontraron colonias</option>';
                    statusDiv.textContent = '⚠ CP encontrado pero sin colonias';
                    statusDiv.className = 'form-text text-warning';
                }

            } else {
                throw new Error(data.message || 'Error al obtener los datos del código postal');
            }

        } catch (error) {
            console.error('Error al cargar datos del CP:', error);
            selectColonias.innerHTML = '<option value="">Error al cargar colonias</option>';
            statusDiv.textContent = `✗ ${error.message}`;
            statusDiv.className = 'form-text text-danger';
            
            // Limpiar campos
            if (municipioInput) municipioInput.value = '';
            if (estadoInput) estadoInput.value = '';
            if (infoUbicacion) infoUbicacion.style.display = 'none';
        }
    }

    // Función para limpiar datos del código postal
    function limpiarDatosCP() {
        const selectColonias = document.getElementById('colonia');
        const statusDiv = document.getElementById('cpStatus');
        const infoUbicacion = document.getElementById('infoUbicacion');
        const municipioInput = document.getElementById('municipio');
        const estadoInput = document.getElementById('estado');

        statusDiv.textContent = '';
        statusDiv.className = 'form-text';
        
        if (selectColonias) {
            selectColonias.innerHTML = '<option value="">Ingresa primero el código postal</option>';
        }
        if (municipioInput) {
            municipioInput.value = '';
        }
        if (estadoInput) {
            estadoInput.value = '';
        }
        if (infoUbicacion) {
            infoUbicacion.style.display = 'none';
        }
    }

    // Event listener para código postal
    document.getElementById('codigoPostal').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 5);
        
        if (this.value.length === 5) {
            cargarDatosCP(this.value);
        } else {
            limpiarDatosCP();
        }
    });

    // Función para probar códigos postales
    function probarCP(codigo) {
        document.getElementById('codigoPostal').value = codigo;
        cargarDatosCP(codigo);
    }
</script>

</body>
</html>