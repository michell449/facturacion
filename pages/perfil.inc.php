<!-- Página de Perfil -->
<div class="content-wrapper bg-light">
    <div class="container py-4">
        <!-- Título de la página -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Mi Perfil
                </h2>
                <p class="text-muted">Administra tu información personal y configuración de cuenta</p>
            </div>
        </div>

        <div class="row">
            <!-- Información Personal -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-fill me-2"></i>Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control" id="nombre" value="Usuario Ejemplo">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" value="usuario@ejemplo.com">
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" value="+52 555 123 4567">
                                </div>
                                <div class="col-md-6">
                                    <label for="empresa" class="form-label">Empresa</label>
                                    <input type="text" class="form-control" id="empresa" value="Mi Empresa S.A.">
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary me-2">Cancelar</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Información Fiscal -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>Información Fiscal
                        </h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="rfc" class="form-label">RFC</label>
                                    <input type="text" class="form-control" id="rfc" value="XAXX010101000">
                                </div>
                                <div class="col-md-6">
                                    <label for="regimenFiscal" class="form-label">Régimen Fiscal</label>
                                    <select class="form-select" id="regimenFiscal">
                                        <option value="601">General de Ley Personas Morales</option>
                                        <option value="612" selected>Persona Física con Actividades Empresariales</option>
                                        <option value="605">Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="razonSocial" class="form-label">Razón Social</label>
                                    <input type="text" class="form-control" id="razonSocial" value="Usuario Ejemplo">
                                </div>
                                <div class="col-12">
                                    <label for="direccion" class="form-label">Dirección Fiscal</label>
                                    <textarea class="form-control" id="direccion" rows="3">Calle Principal 123, Col. Centro, CP 12345, Ciudad de México, CDMX</textarea>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary me-2">Cancelar</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-lg me-2"></i>Actualizar Información
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Cambiar Contraseña -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-lock-fill me-2"></i>Cambiar Contraseña
                        </h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="passwordActual" class="form-label">Contraseña Actual</label>
                                    <input type="password" class="form-control" id="passwordActual">
                                </div>
                                <div class="col-md-6">
                                    <label for="passwordNueva" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="passwordNueva">
                                </div>
                                <div class="col-md-6">
                                    <label for="passwordConfirmar" class="form-label">Confirmar Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="passwordConfirmar">
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <small>
                                    <i class="bi bi-info-circle me-1"></i>
                                    La contraseña debe tener al menos 8 caracteres, incluyendo letras y números.
                                </small>
                            </div>
                            <hr class="my-4">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary me-2">Cancelar</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-shield-lock me-2"></i>Cambiar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar con información adicional -->
            <div class="col-lg-4">
                <!-- Estadísticas del usuario -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>Estadísticas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Facturas Generadas:</span>
                            <span class="badge bg-primary fs-6">28</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Total Facturado:</span>
                            <span class="badge bg-success fs-6">$15,430</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Cuenta desde:</span>
                            <span class="badge bg-info fs-6">Ene 2025</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Último acceso:</span>
                            <span class="badge bg-secondary fs-6">Hoy</span>
                        </div>
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning-fill me-2"></i>Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="window.location.href='facturar-invitado'">
                                <i class="bi bi-plus-circle me-2"></i>Nueva Factura
                            </button>
                            <button class="btn btn-outline-success" onclick="window.location.href='historial'">
                                <i class="bi bi-clock-history me-2"></i>Ver Historial
                            </button>
                            <button class="btn btn-outline-info">
                                <i class="bi bi-download me-2"></i>Descargar Certificado
                            </button>
                            <button class="btn btn-outline-warning">
                                <i class="bi bi-question-circle me-2"></i>Solicitar Soporte
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Información de la cuenta -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-check me-2"></i>Seguridad
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Tu cuenta está protegida con:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small>Verificación de email</small>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                <small>Contraseña segura</small>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-x-circle text-danger me-2"></i>
                                <small>Autenticación de dos factores</small>
                            </li>
                        </ul>
                        <button class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-shield-plus me-2"></i>Activar 2FA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>