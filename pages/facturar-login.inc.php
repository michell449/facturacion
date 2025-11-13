<!-- Contenedor principal -->
<div class="content-wrapper  bg-light">
    <!-- Sección Hero -->
    <div class="bg-primary text-white py-4">
        <div class="container h-100 d-flex align-items-center">
            <div class="row w-100 align-items-center">
                <div class="col-lg-6">
                    <h1 class=" fw-bold mb-4">Facturación Electrónica <i class="bi bi-receipt-cutoff m-2 opacity-75"></i> </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="text-primary fw-bold">Facturación electrónica</h2>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary mb-3"><i class="bi bi-check-circle me-2"></i>Crea una cuenta en nuestro portal </h5>
                            <p>Con tu cuenta podrás llevar un registro de tus compras y facturas, usar perfiles predeterminados para facturar de forma rápida y segura.</p>
                            <h5 class="text-primary mb-3"><i class="bi bi-check-circle me-2"></i>Ingresa como invitado</h5>
                            <p>Si deseas facturar por única vez, puedes acceder como invitado para generar una factura sin registrarte, solo registra tus datos para la factura.</p>
                        </div>

                        <div class="alert alert-info border-0" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: 15px;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill text-primary me-2"></i>
                                <small><strong>Importante:</strong> Solo puedes facturar dentro del periodo habil de facturación posterior a la fecha de compra.</small>
                            </div>
                        </div>

                        <div class="card bg-light border-0" style="border-radius: 15px;">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-clipboard-check me-2"></i>Datos necesarios para facturar:</h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <small><i class="bi bi-receipt text-primary me-1"></i> Número de venta</small>
                                    </div>
                                    <div class="col-6">
                                        <small><i class="bi bi-calendar text-primary me-1"></i> Fecha de compra</small>
                                    </div>
                                    <div class="col-6">
                                        <small><i class="bi bi-geo-alt text-primary me-1"></i> Lugar de compra</small>
                                    </div>
                                    <div class="col-6">
                                        <small><i class="bi bi-currency-dollar text-primary me-1"></i> Monto total</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p class="mb-2"><strong>¿Necesitas ayuda?</strong></p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="tel:+8004559524" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="bi bi-telephone me-1"></i> 800 455 9524
                                </a>
                                <a href="mailto:admin@despacho.com" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="bi bi-envelope me-1"></i> Email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de login mejorada -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-person-check fs-4"></i>
                            </div>
                            <h3 class="text-primary fw-bold">Iniciar Sesión</h3>
                        </div>

                        <form>
                            <!-- Campo de usuario -->
                            <div class="mb-3">
                                <label for="inputEmail" class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-1 text-primary"></i> Correo Electrónico
                                </label>
                                <input type="email" class="form-control form-control-lg rounded-3" id="inputEmail"
                                    placeholder="ejemplo@correo.com" required>
                            </div>

                            <!-- Campo de contraseña -->
                            <div class="mb-4">
                                <label for="inputPassword" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-1 text-primary"></i> Contraseña
                                </label>
                                <input type="password" class="form-control form-control-lg rounded-3" id="inputPassword"
                                    placeholder="Tu contraseña" required>
                            </div>

                            <!-- Botones -->
                            <div class="d-grid gap-3">
                                <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-semibold">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sesión
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-lg rounded-3 fw-semibold"
                                    data-bs-toggle="modal" data-bs-target="#crearCuentaModal">
                                    <i class="bi bi-person-plus me-2"></i> Crear Cuenta
                                </button>
                            </div>
                        </form>

                        <!-- Enlace de recuperación de contraseña -->
                        <div class="text-center mt-4">
                            <a href="#" class="text-decoration-none text-primary">
                                <i class="bi bi-question-circle me-1"></i> ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sección de "Ingresar como invitado" mejorada -->
                <div class="mt-4">
                    <div class="card shadow-lg border-0 rounded-4 bg-info">
                        <div class="card-body text-center p-4">
                            <p class="text-white fw-bold mb-3">Puedes facturar sin registrarte</p>
                            <a href="panel?pg=facturar-invitado" class="btn btn-light btn-lg w-100 rounded-3 fw-semibold">
                                <i class="bi bi-arrow-right-circle me-2"></i> Continuar como invitado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal de creación de cuenta -->
<div class="modal fade" id="crearCuentaModal" tabindex="-1" aria-labelledby="crearCuentaModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <div class="w-100 text-center">
                    <i class="bi bi-person-plus-fill" style="font-size: 3.5rem;"></i>
                    <h4 class="modal-title fw-bold" id="crearCuentaModalLabel">Crear Cuenta</h4>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form>
                    <div class="mb-4">
                        <label for="nuevoEmail" class="form-label fw-semibold">
                            <i class="bi bi-envelope text-primary me-2"></i>Correo Electrónico
                        </label>
                        <input type="email" class="form-control form-control-lg rounded-3" id="nuevoEmail"
                            placeholder="ejemplo@correo.com" required>
                        <div class="form-text">Te enviaremos un código de verificación a este correo</div>
                    </div>

                    <div class="mb-4">
                        <label for="nuevaContraseña" class="form-label fw-semibold">
                            <i class="bi bi-lock text-primary me-2"></i>Contraseña
                        </label>
                        <input type="password" id="nuevaContraseña" class="form-control form-control-lg rounded-3"
                            placeholder="Mínimo 8 caracteres" required>
                        <div class="form-text">Debe contener al menos 8 caracteres, una letra y un número</div>
                    </div>

                    <div class="mb-4">
                        <label for="confirmarContraseña" class="form-label fw-semibold">
                            <i class="bi bi-lock-fill text-primary me-2"></i>Confirmar Contraseña
                        </label>
                        <input type="password" id="confirmarContraseña" class="form-control form-control-lg rounded-3"
                            placeholder="ingresa nuevamente tu contraseña" required>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <!-- boton para ingresar como administrador y abrir modal de creación de cuenta administrativa-->
                        <button type="button" class="btn" id="btnIngresarAdmin" data-bs-toggle="modal" data-bs-target="#crearCuentaAdminModal">
                            Ingresar como administrador
                        </button>
                        <button type="button" class="btn btn-primary btn-lg rounded-3 fw-semibold" id="btnCrearCuenta">
                            Crear Cuenta
                        </button>
                        <button type="button" class="btn btn-outline-secondary rounded-3 fw-semibold" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de creación de cuenta administrativa -->
<div class="modal fade" id="crearCuentaAdminModal" tabindex="-1" aria-labelledby="crearCuentaAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <div class="w-100 text-center">
                    <i class="bi bi-person-gear" style="font-size: 3.5rem;"></i>
                    <h4 class="modal-title fw-bold" id="crearCuentaAdminModalLabel">Crear Cuenta Administrativa</h4>
                    <small class="opacity-75">Solo para personal autorizado</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-primary border-0 rounded-3 mb-4">
                    <div class="d-flex">
                        <i class="bi bi-exclamation-triangle-fill text-primary me-3 fs-5"></i>
                        <div>
                            <h6 class="mb-1">Cuenta Administrativa</h6>
                            <small>Esta cuenta tendrá acceso completo al sistema. Solo crear si tienes autorización.</small>
                        </div>
                    </div>
                </div>

                <form>
                    <div class="mb-4">
                        <label for="nuevoEmailAdmin" class="form-label fw-semibold">
                            Correo Electrónico
                        </label>
                        <input type="email" class="form-control form-control-lg rounded-3" id="nuevoEmailAdmin"
                            placeholder="admin@empresa.com" required>
                        <div class="form-text">
                            Se enviará un código de verificación a este correo
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="nuevaContraseñaAdmin" class="form-label fw-semibold">Contraseña
                        </label>
                        <input type="password" id="nuevaContraseñaAdmin" class="form-control form-control-lg rounded-3"
                            placeholder="Mínimo 8 caracteres" required>
                        <div class="form-text">
                            <i class="bi bi-shield-check me-1"></i>
                            Debe contener: Al menos 8 caracteres, mayúsculas, minúsculas, números y símbolos
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="confirmarContraseñaAdmin" class="form-label fw-semibold">
                            Confirmar Contraseña
                        </label>
                        <input type="password" id="confirmarContraseñaAdmin" class="form-control form-control-lg rounded-3"
                            placeholder="Confirma la contraseña" required>
                    </div>

                    <div class="mb-4">
                        <label for="claveAutorizacion" class="form-label fw-semibold">
                            Clave de Autorización
                        </label>
                        <input type="password" id="claveAutorizacion" class="form-control form-control-lg rounded-3"
                            placeholder="Clave proporcionada por IT" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-lg rounded-3" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-primary btn-lg rounded-3 fw-semibold" id="btnCrearCuentaAdmin">
                            Crear Cuenta Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de verificación de correo -->
<div class="modal fade" id="verificacionCorreoModal" tabindex="-1" aria-labelledby="verificacionCorreoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <div class="w-100 text-center">
                    <h4 class="modal-title fw-bold" id="verificacionCorreoModalLabel">Verificar Correo</h4>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-4">
                    <i class="bi bi-envelope-check-fill text-primary" style="font-size: 4rem;"></i>
                </div>

                <h5 class="mb-3">¡Código enviado!</h5>
                <p class="text-muted mb-4">Hemos enviado un código de verificación a tu correo electrónico. Revisa tu bandeja de entrada o carpeta de spam.</p>

                <form id="formVerificacionCorreo">
                    <div class="mb-4">
                        <label for="codigoVerificacion" class="form-label fw-semibold">Código de verificación</label>
                        <input type="text" class="form-control form-control-lg text-center fs-4 rounded-3" id="codigoVerificacion"
                            placeholder="000000" required style="letter-spacing: 0.5rem;" maxlength="6" pattern="\d{6}" title="Ingresa exactamente 6 dígitos">
                        <div class="form-text">Ingresa los 6 dígitos que recibiste por correo</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" id="btnVerificarToken" class="btn btn-primary btn-lg rounded-3 fw-semibold">
                            Verificar
                        </button>
                    </div>
                </form>

                <div class="mt-3">
                    <small class="text-muted">¿No recibiste el código? <a href="#" class="text-primary">Reenviar</a></small>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // conectar html con php registrar usuarios facturacion
    document.getElementById('btnCrearCuenta').addEventListener('click', async function() {
        const email = document.getElementById('nuevoEmail').value;
        const password = document.getElementById('nuevaContraseña').value;
        const confirmPassword = document.getElementById('confirmarContraseña').value;
        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden.');
            return;
        }
        //Validar contraseña (mínimo 8 caracteres, al menos una letra y un número)
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (!passwordRegex.test(password)) {
            alert('La contraseña debe tener al menos 8 caracteres, incluyendo al menos una letra y un número.');
            return;
        }
        const res = await fetch('core/registro-usuarios-facturacion.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: email,
                password: password,
                confirmPassword: confirmPassword
            })
        });
        if (!res.ok) {
            throw new Error(`Error del servidor: ${res.status} ${res.statusText}`);
        }

        const responseText = await res.text();

        let data;
        try {
            data = JSON.parse(responseText);
        } catch (parseError) {
            console.error('Respuesta del servidor no es JSON válido:', responseText);
            throw new Error('El servidor devolvió una respuesta inválida');
        }

        console.log('Respuesta registro:', data);

        if (data.success) {
            const crearCuentaModal = bootstrap.Modal.getInstance(document.getElementById('crearCuentaModal'));
            crearCuentaModal.hide();

            Swal.fire({
                icon: 'success',
                title: '¡Cuenta creada!',
                text: 'Revisa tu correo para el código de verificación.',
                confirmButtonText: 'Continuar'
            }).then(() => {
                const verificacionCorreoModal = new bootstrap.Modal(document.getElementById('verificacionCorreoModal'));
                verificacionCorreoModal.show();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al crear cuenta',
                text: data.message || 'Ocurrió un error inesperado'
            });
        }
    });

    // Función para validar entrada solo números en el código de verificación
    document.getElementById('codigoVerificacion').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        if (e.target.value.length > 6) {
            e.target.value = e.target.value.slice(0, 6);
        }
    });
    //función para validar token de verificación enviado al correo
    document.getElementById('formVerificacionCorreo').addEventListener('submit', async function(event) {
        event.preventDefault();
        const token = document.getElementById('codigoVerificacion').value.trim();
        Swal.fire({
            title: 'Verificando código...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const res = await fetch('core/verificacion-correo-usuario-fact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Cache-Control': 'no-cache'
                },
                body: JSON.stringify({
                    token: token
                })
            });

            if (!res.ok) {
                throw new Error(`Error del servidor: ${res.status} ${res.statusText}`);
            }
            const responseText = await res.text();

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Respuesta del servidor no es JSON válido:', responseText);
                throw new Error('El servidor devolvió una respuesta inválida');
            }
            console.log('Respuesta verificación:', data);
            Swal.close();

            if (data.success) {
                // Cerrar modal de verificación
                const verificacionCorreoModal = bootstrap.Modal.getInstance(document.getElementById('verificacionCorreoModal'));
                verificacionCorreoModal.hide();
                Swal.fire({
                    icon: 'success',
                    title: '¡Cuenta verificada!',
                    text: data.message,
                    confirmButtonText: 'Continuar',
                    allowOutsideClick: false
                }).then(() => {
                    // Redirigir a la página de registro de información de usuarios
                    window.location.href = 'panel?pg=registro-info-usuarios';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de verificación',
                    text: data.message,
                    footer: 'Verifica que el código sea correcto o solicita uno nuevo.'
                });
            }
        } catch (error) {
            console.error('Error en verificación:', error);
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo verificar el código. Inténtalo de nuevo.',
                footer: error.message
            });
        }
    });
</script>

<script>
    // Conectar el botón de crear cuenta administrativa
    document.getElementById('btnCrearCuentaAdmin').addEventListener('click', async function() {
        const email = document.getElementById('nuevoEmailAdmin').value;
        const password = document.getElementById('nuevaContraseñaAdmin').value;
        const confirmPassword = document.getElementById('confirmarContraseñaAdmin').value;
        const claveAutorizacion = document.getElementById('claveAutorizacion').value;

        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden.');
            return;
        }

        // Validar contraseña (mínimo 8 caracteres, mayúsculas, minúsculas y números)
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (!passwordRegex.test(password)) {
            alert('La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas y números.');
            return;
        }

        const res = await fetch('core/registro-usuarios-admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: email,
                password: password,
                confirmPassword: confirmPassword,
                claveAutorizacion: claveAutorizacion
            })
        });
        if (!res.ok) {
            throw new Error(`Error del servidor: ${res.status} ${res.statusText}`);
        }

        const responseText = await res.text();

        let data;
        try {
            data = JSON.parse(responseText);
        } catch (parseError) {
            console.error('Respuesta del servidor no es JSON válido:', responseText);
            throw new Error('El servidor devolvió una respuesta inválida');
        }

        console.log('Respuesta registro admin:', data);

        if (data.success) {
            const crearCuentaAdminModal = bootstrap.Modal.getInstance(document.getElementById('crearCuentaAdminModal'));
            crearCuentaAdminModal.hide();

            Swal.fire({
                icon: 'success',
                title: '¡Cuenta administrativa creada!',
                text: 'Revisa tu correo para el código de verificación.',
                confirmButtonText: 'Continuar'
            }).then(() => {
                const verificacionCorreoModal = new bootstrap.Modal(document.getElementById('verificacionCorreoModal'));
                verificacionCorreoModal.show();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al crear cuenta administrativa',
                text: data.message || 'Ocurrió un error inesperado'
            });
        }
    });
    
</script>