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
                        <div class="table-responsive">
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
                                <tbody>
                                    <tr>
                                        <td>06/11/2025</td>
                                        <td>A-001</td>
                                        <td>XAXX010101000</td>
                                        <td>Empresa Ejemplo S.A. de C.V.</td>
                                        <td>$1,250.00</td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Exitosa
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-success" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                                <button class="btn btn-outline-info" title="Enviar por email">
                                                    <i class="bi bi-envelope"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>05/11/2025</td>
                                        <td>A-002</td>
                                        <td>YAYY020202000</td>
                                        <td>Cliente Ejemplo S.A.</td>
                                        <td>$850.00</td>
                                        <td>
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>Pendiente
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary" title="Reenviar">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>04/11/2025</td>
                                        <td>A-003</td>
                                        <td>ZAZZ030303000</td>
                                        <td>Proveedor Test S.A.</td>
                                        <td>$2,100.00</td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Exitosa
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-success" title="Descargar">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                                <button class="btn btn-outline-info" title="Enviar por email">
                                                    <i class="bi bi-envelope"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <nav aria-label="Paginación de facturas">
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Anterior</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Siguiente</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen estadístico -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-check fs-1 mb-2"></i>
                        <h4>25</h4>
                        <p class="mb-0">Facturas Exitosas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-clock fs-1 mb-2"></i>
                        <h4>3</h4>
                        <p class="mb-0">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-currency-dollar fs-1 mb-2"></i>
                        <h4>$15,430</h4>
                        <p class="mb-0">Total Facturado</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-month fs-1 mb-2"></i>
                        <h4>Este Mes</h4>
                        <p class="mb-0">Periodo Actual</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>