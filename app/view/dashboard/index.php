<?php require_once __DIR__ . "/../layouts/header.php"; ?>

<?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

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
                        <h3 class="mb-0">Resumen del mes</h3>
                        <div class="text-muted">Periodo: 2026-03-01 a 2026-03-31 </div>
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


            <!-- CARDS -->

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="dash-card">
                        <h5>Gastos Libres</h5>
                        <p id="totalLibres">$1,250</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="dash-card">
                        <h5>Gastos Fijos</h5>
                        <p id="totalFijos">$3,400</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="dash-card">
                        <h5>Deudas</h5>
                        <p id="totalDeudas">$5,200</p>
                    </div>
                </div>

            </div>


            <!-- TABLAS -->

            <div class="row mt-4">

                <!-- GASTOS LIBRES -->

                <div class="col-lg-6">

                    <div class="panel">

                        <div class="d-flex justify-content-between mb-2">
                            <h6>Gastos Libres</h6>
                            <span>$1250 / $3000</span>
                        </div>

                        <div class="progress mb-3">
                            <div id="progressGastosLibres" class="progress-bar" style="width:41%"></div>
                        </div>

                        <table class="table table-hover">

                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>Comida</td>
                                    <td>$120</td>
                                    <td>07/03</td>
                                </tr>

                                <tr>
                                    <td>Transporte</td>
                                    <td>$50</td>
                                    <td>06/03</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>


                <!-- TARJETAS -->

                <div class="col-lg-6">

                    <div class="panel">

                        <h6 class="mb-3">Tarjetas</h6>

                        <table class="table table-hover">

                            <thead>
                                <tr>
                                    <th>Tarjeta</th>
                                    <th>Límite</th>
                                    <th>Gastado</th>
                                    <th>Uso</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>

                                    <td>BBVA</td>
                                    <td>$5000</td>
                                    <td>$3000</td>

                                    <td>

                                        <div class="progress">
                                            <div class="progress-bar bg-danger" style="width:60%"></div>
                                        </div>

                                    </td>

                                </tr>

                                <tr>

                                    <td>NU</td>
                                    <td>$2000</td>
                                    <td>$500</td>

                                    <td>

                                        <div class="progress">
                                            <div class="progress-bar bg-warning" style="width:25%"></div>
                                        </div>

                                    </td>

                                </tr>

                                <tr>

                                    <td>Mercado Pago</td>
                                    <td>$1500</td>
                                    <td>$200</td>

                                    <td>

                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width:13%"></div>
                                        </div>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <!-- GRAFICAS -->

            <div class="row mt-4">

                <div class="col-lg-6">

                    <div class="panel">

                        <h6>Gastos por mes</h6>

                        <canvas id="graficaMes"></canvas>

                    </div>

                </div>


                <div class="col-lg-6">

                    <div class="panel">

                        <h6>Tipos de gasto</h6>

                        <canvas id="graficaTipos"></canvas>

                    </div>

                </div>

            </div>

        </main>

    </div>


    <script>
    document.getElementById("toggleMenu").onclick = function() {
        document.getElementById("sidebar").classList.toggle("collapsed");
    }


    function filtrarDashboard() {

        let inicio = document.getElementById("fechaInicio").value
        let fin = document.getElementById("fechaFin").value

        console.log("Filtrar desde", inicio, "hasta", fin)

        /* aquí conectarás tu PHP */

    }


    let chartMes
    let chartTipos


    function iniciarGraficas() {

        chartMes = new Chart(

            document.getElementById("graficaMes"),

            {

                type: 'line',

                data: {

                    labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun"],

                    datasets: [{

                        label: "Gastos",

                        data: [1200, 1800, 900, 2100, 1500, 1300],

                        borderColor: "#7c3aed",

                        backgroundColor: "rgba(124,58,237,0.2)",

                        tension: .4

                    }]

                }

            })



        chartTipos = new Chart(

            document.getElementById("graficaTipos"),

            {

                type: 'doughnut',

                data: {

                    labels: ["Fijos", "Libres", "Tarjetas", "Deudas"],

                    datasets: [{

                        data: [40, 25, 20, 15],

                        backgroundColor: [
                            "#7c3aed",
                            "#6366f1",
                            "#22c55e",
                            "#ef4444"
                        ]

                    }]

                }

            })

    }

    iniciarGraficas()
    </script>

</body>

</html>
