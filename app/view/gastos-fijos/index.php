
<?php require_once __DIR__ . "/../layouts/header.php"; ?>

<?php require_once __DIR__ . "/../layouts/sidebar.php";  ?>
        <!-- CONTENIDO -->
        <main class="content">
            <!-- TOPBAR -->
            <div class="topbar">
                <button id="toggleMenu" class="menu-btn">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <i class="bi bi-person-circle" style="font-size:22px"></i>
                </div>
            </div>


            <!-- FILTRO -->
            <div class="row mb-2">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div>
                        <h3 class="mb-0">Gastos fijos</h3>
                        <div class="text-muted">Suscripciones y servicios mensuales.</div>
                    </div>
                    <form class="d-flex align-items-end gap-2">
                        <div>
                            <label>Desde</label>
                            <input type="date" id="fechaInicio" class="form-control">
                        </div>
                        <div>
                            <label>Hasta</label>
                            <input type="date" id="fechaFin" class="form-control">
                        </div>
                        <button class="btn btn-dark">Ver</button>
                    </form>
                </div>
            </div>

            <!-- Formulario -->
            <div class="row g-4">

                <!-- FORMULARIO -->
                <div class="col-lg-5">
                    <div class="card card-kpi p-4 h-100">

                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-plus-circle me-2 text-primary"></i>
                            <h6 class="mb-0">Agregar gasto fijo</h6>
                        </div>

                        <form method="post" class="vstack gap-3">

                            <div>
                                <label class="form-label">Nombre</label>
                                <input class="form-control input-dashboard" name="nombre" placeholder="Ej. Netflix"
                                    required>
                            </div>

                            <div>
                                <label class="form-label">Monto mensual</label>
                                <input class="form-control input-dashboard" name="monto" type="number" step="0.01"
                                    min="0" placeholder="$0.00" required>
                            </div>

                            <div>
                                <label class="form-label">Método de pago</label>
                                <select class="form-select input-dashboard" name="metodo" required>
                                    <option value="">Seleccionar</option>
                                    <option>TDC BBVA</option>
                                    <option>TDC Nu</option>
                                    <option>Débito BBVA</option>
                                    <option>Efectivo</option>
                                    <option>Transferencia</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-1">

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="activo" id="activo" checked>

                                    <label class="form-check-label" for="activo">
                                        Activo
                                    </label>
                                </div>

                                <button class="btn btn-dashboard">
                                    <i class="bi bi-save me-1"></i>
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- TABLA -->
                <div class="col-lg-7">
                    <div class="card card-kpi p-0 overflow-hidden">

                        <div class="card-header-dashboard">
                            <i class="bi bi-wallet2 me-2"></i>
                            Lista de gastos fijos
                        </div>

                        <div class="table-responsive">

                            <table class="table table-dashboard mb-0 align-middle">

                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Método</th>
                                        <th class="text-end">Monto</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="gasFtabl">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
<?php require_once __DIR__ . "/../layouts/footer.php"; ?>