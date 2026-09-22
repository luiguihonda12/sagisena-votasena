<?php 
require_once 'controllers/votcnvot.php'; 
echo titulo2("<i class='fa " . $icono . "'></i> No Votantes", 2); 
?>

<!-- Sección de Gráfico y Resumen Estadístico -->
<div class="row g-4 mb-4 align-items-stretch">
    <!-- Gráfico Highcharts -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center">
                <div id="graficoVotos" style="width: 100%; min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Tabla Resumen de Votos -->
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold text-secondary">
                    <i class="fa-solid fa-chart-pie me-2 text-success"></i> Resumen de Votación
                </h5>
            </div>
            <div class="card-body p-3 d-flex flex-column justify-content-center">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Categoría</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span style="display: inline-block; width: 12px; height: 12px; border-radius: 50%; background-color: #dc3545; margin-right: 8px; vertical-align: middle;"></span>
                                    No votaron
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger fs-6 px-3 py-1 rounded-pill">
                                        <?= number_format($no_votaron, 0, ',', '.'); ?>
                                    </span>
                                </td>
                                <td class="text-end fw-semibold text-danger">
                                    <?= $porc_no_votaron; ?>%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span style="display: inline-block; width: 12px; height: 12px; border-radius: 50%; background-color: #28a745; margin-right: 8px; vertical-align: middle;"></span>
                                    Votaron
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success fs-6 px-3 py-1 rounded-pill">
                                        <?= number_format($votaron, 0, ',', '.'); ?>
                                    </span>
                                </td>
                                <td class="text-end fw-semibold text-success">
                                    <?= $porc_votaron; ?>%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span style="display: inline-block; width: 12px; height: 12px; border-radius: 50%; background-color: #6c757d; margin-right: 8px; vertical-align: middle;"></span>
                                    Votos en blanco
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary fs-6 px-3 py-1 rounded-pill">
                                        <?= number_format($votos_blanco, 0, ',', '.'); ?>
                                    </span>
                                </td>
                                <td class="text-end fw-semibold text-muted">
                                    <?= $porc_blanco; ?>%
                                </td>
                            </tr>
                            <tr class="table-light fw-bold">
                                <td class="text-dark">
                                    TOTAL PERSONAS
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark fs-6 px-3 py-1 rounded-pill">
                                        <?= number_format($total_personas, 0, ',', '.'); ?>
                                    </span>
                                </td>
                                <td class="text-end text-dark">
                                    100%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Listado Detallado con DataTables -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold text-secondary">
            <i class="fa-solid fa-users me-2 text-success"></i> Listado General de Votantes
        </h5>
        <span class="badge bg-secondary rounded-pill">
            <?= count($dat); ?> registros
        </span>
    </div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th style="width: 20%;">Perfil</th>
                        <th style="width: 60%;">Usuario</th>
                        <th class="text-center" style="width: 20%;">Estado Voto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dat)): ?>
                        <?php foreach ($dat as $d): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <?= htmlspecialchars($d['nomper']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-uppercase text-dark">
                                        <?= htmlspecialchars($d['nomusu']); ?>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <span><strong>Doc:</strong> <?= htmlspecialchars($d['ndocusu']); ?></span>
                                        <?php if (!empty($d['idfic'])): ?>
                                            <span class="ms-2">| <strong>Ficha:</strong> <?= htmlspecialchars($d['idfic']); ?> - <?= htmlspecialchars($d['nomfic']); ?> (<?= htmlspecialchars($d['nomval']); ?>)</span>
                                        <?php endif; ?>
                                        <?php if (!empty($d['nomcen'])): ?>
                                            <span class="ms-2">| <i class="fa-solid fa-building me-1"></i><?= htmlspecialchars($d['nomcen']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $votado = isset($d['votado']) ? (bool)$d['votado'] : $mnvot->getVotU($d['idusu']);
                                    if ($votado) {
                                    ?>
                                        <a href="home.php?pg=<?= $pg; ?>&idusu=<?= $d['idusu']; ?>" class="text-decoration-none" title="Votó">
                                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                                <i class="fa-solid fa-thumbs-up me-1"></i> Votó
                                            </span>
                                        </a>
                                    <?php } else { ?>
                                        <a href="home.php?pg=<?= $pg; ?>&idusu=<?= $d['idusu']; ?>" class="text-decoration-none" title="No votó">
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                <i class="fa-solid fa-thumbs-down me-1"></i> No votó
                                            </span>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th>Perfil</th>
                        <th>Usuario</th>
                        <th class="text-center">Estado Voto</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Scripts de Highcharts -->
<script src="./js/highcharts.js"></script>
<script src="./js/exporting.js"></script>
<script src="./js/accessibility.js"></script>

<script>
Highcharts.setOptions({
    lang: {
        decimalPoint: ',',
        thousandsSep: '.',
        loading: 'Cargando...',
        noData: 'No hay datos',
        printChart: 'Imprimir gráfico',
        downloadPNG: 'Descargar en PNG',
        downloadJPEG: 'Descargar en JPEG',
        downloadPDF: 'Descargar en PDF',
        downloadSVG: 'Descargar en SVG',
        resetZoom: 'Restablecer zoom',
        viewFullscreen: 'Ver a pantalla completa',
        exitFullscreen: 'Salir de pantalla completa'
    }
});

Highcharts.chart('graficoVotos', {
    chart: {
        type: 'pie',
        height: 350
    },
    title: {
        text: 'Porcentaje de Votación',
        style: {
            fontSize: '18px',
            fontWeight: 'bold',
            color: '#333333'
        }
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b> personas ({point.percentage:.1f}%)'
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                style: {
                    fontSize: '13px'
                }
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Total',
        colorByPoint: true,
        data: [
            {
                name: 'No votaron',
                y: <?= $no_votaron; ?>,
                color: '#dc3545'
            },
            {
                name: 'Votaron',
                y: <?= $votaron; ?>,
                color: '#28a745'
            },
            {
                name: 'Votos en blanco',
                y: <?= $votos_blanco; ?>,
                color: '#6c757d'
            }
        ]
    }]
});
</script>