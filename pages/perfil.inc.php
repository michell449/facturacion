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
            <div class="col-lg-12">
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
                                <di class="col-12">
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
            </div>
        </div>
    </div>
</div>
</div>