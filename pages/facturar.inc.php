<!-- pagina facturar compras usurios-->
<div class="content-wrapper bg-light">
    <div class="container py-4">
        <!-- Título de la página -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="bi bi-card-heading me-2"></i>
                    Facturar
                </h2>
                <p class="text-muted mb-0">Aquí puedes generar las facturas de tus compras.</p>
            </div>
        </div>
    </div>

    <!-- buscar ticket o compra-->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="card-header bg-primary text-white text-center rounded-top-4 py-4">
                <h3 class="mb-0 fw-bold">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Encuentra tu compra
                </h3>
                <p class="mb-0 mt-2 opacity-75">Ingresa los datos de tu compra para localizarla</p>
            </div>
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <form id="formBuscarTicket">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="numeroTicket" class="form-label fw-semibold">
                                    Número de Venta (Folio)
                                </label>
                                <input type="text" class="form-control form-control-lg" id="numeroTicket"
                                    placeholder="Ej: 123456789" required>
                                <div class="form-text">
                                    Encuentra este número en tu ticket de compra
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="montoTotal" class="form-label fw-semibold">
                                    Monto Total
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="montoTotal"
                                        placeholder="0.00" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="fechaCompra" class="form-label fw-semibold">
                                    Fecha de Compra
                                </label>
                                <input type="date" class="form-control form-control-lg" id="fechaCompra" required>
                            </div>

                            <div class="col-12">
                                <label for="lugarCompraInput" class="form-label fw-semibold">
                                    Lugar de Compra
                                </label>
                                <input type="text" class="form-control form-control-lg" id="lugarCompraInput"
                                    placeholder="Haz clic para seleccionar la sucursal" readonly
                                    data-bs-toggle="modal" data-bs-target="#modalSucursales"
                                    style="cursor:pointer;">
                                <div class="form-text">
                                    Selecciona la sucursal donde realizaste tu compra
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary btn-lg py-3 fw-semibold" id="btnBuscarTicket">
                                <i class="bi bi-search me-2"></i>
                                Buscar Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>