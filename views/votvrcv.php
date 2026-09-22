<?php
// Vista Oficial: Resultados Vocero (ID 1213)
// Responsable: Yeison Stiven Molina Balceras
// Cronograma SAGI: views/votvrcv.php | Controlador: controllers/votcrcv.php | Modelo: models/votmrcv.php

require_once 'controllers/votcrcv.php';
?>

<div class="conte">
    <?php echo titulo2("<i class='" . (isset($icono) ? $icono : 'fa-users') . "'></i> Resultados Electorales - Voceros", 2); ?>
</div>

<!-- Selector de Jornada y Ficha en Cascada -->
<div class="conte mb-4">
    <form action="home.php?pg=<?= $pg ?>" method="POST" id="fichaForm" class="card shadow-sm border-0 p-3 bg-light">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="idjor" class="form-label fw-semibold text-secondary">
                    <i class="fa-solid fa-clock me-1"></i> Filtrar por Jornada
                </label>
                <select name="fidjor" id="idjor" class="form-select" onchange="document.getElementById('idfic').value=''; this.form.submit();">
                    <option value="">-- Todas las jornadas --</option>
                    <?php if (isset($jornadas) && $jornadas): ?>
                        <?php foreach ($jornadas as $jor): ?>
                            <option value="<?= $jor['idval']; ?>" <?= (isset($fidjor) && $fidjor == $jor['idval']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($jor['nomval']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-7">
                <label for="idfic" class="form-label fw-semibold text-secondary">
                    <i class="fa-solid fa-hashtag me-1"></i> Seleccionar Ficha de Formación
                </label>
                <select name="fidfic" id="idfic" class="form-select ficha-select" required onchange="this.form.submit();">
                    <option value="">-- Seleccione una ficha --</option>
                    <?php if (isset($fichas) && $fichas): ?>
                        <?php foreach ($fichas as $ficha): ?>
                            <option value="<?= $ficha['idfic']; ?>" <?= (isset($fidfic) && $fidfic == $ficha['idfic']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($ficha['idfic']); ?> - <?= htmlspecialchars($ficha['nomfic']); ?> (<?= htmlspecialchars($ficha['nomval']); ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function () {
    if ($.fn.select2) {
        $('.ficha-select').select2({
            placeholder: "-- Seleccione una ficha --",
            allowClear: true,
            width: '100%'
        });
    }
});

function descargarPDFVocero() {
    var idfic = document.getElementById('idfic').value;
    if (idfic) {
        window.open('views/pdfrev.php?pdf=ok&idfic=' + idfic, '_blank');
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Por favor seleccione una ficha primero',
            confirmButtonText: 'Aceptar'
        });
    }
}
</script>

<?php if (!empty($fidfic) && !empty($fichaActual)): ?>
    <!-- Cabecera de la Ficha Seleccionada -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #117f09; color: white;">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="fa-solid fa-graduation-cap me-2"></i>Ficha: <?= htmlspecialchars($fichaActual['idfic']); ?> - <?= htmlspecialchars($fichaActual['nomfic']); ?>
                </h5>
                <small class="text-white-50">Jornada: <?= htmlspecialchars($fichaActual['nomval']); ?></small>
            </div>
            <div>
                <button type="button" class="btn btn-light text-success d-flex align-items-center fw-bold shadow-sm" onclick="descargarPDFVocero()" title="Imprimir Acta de Vocero">
                    <i class="fas fa-print fa-lg me-2"></i> Imprimir Acta
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de Candidatos a Vocero Ordenados por Votos -->
    <div class="table-responsive">
        <table id="example" class="table table-striped table-hover align-middle shadow-sm" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th style="text-align: center; width: 15%;">Puesto</th>
                    <th style="text-align: center; width: 15%;">Votos</th>
                    <th style="text-align: center; width: 15%;">Foto</th>
                    <th>Candidato a Vocero</th>
                    <th style="text-align: center; width: 20%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totm = 0;
                if (!empty($dat)) {
                    // Cálculo de votos y orden descendente
                    foreach ($dat as &$candidato_ref) {
                        $nvo_calc = isset($votmrcv) ? $votmrcv->nvoCan($candidato_ref['idusu']) : 0;
                        $candidato_ref['nvo_exacto'] = isset($nvo_calc[0]['nvo']) ? (int)$nvo_calc[0]['nvo'] : 0;
                    }
                    unset($candidato_ref);

                    usort($dat, function($a, $b) {
                        return $b['nvo_exacto'] - $a['nvo_exacto'];
                    });

                    $posicion = 0;
                    foreach ($dat as $d) {
                        $nvo = $d['nvo_exacto'];
                        $totm += $nvo;
                        $foto = (!empty($d['fotcan']) && file_exists($d['fotcan'])) ? $d['fotcan'] : "img/user.jpg";

                        $puesto_badge = "";
                        $row_class = "";
                        if ($posicion == 0 && $nvo > 0) {
                            $puesto_badge = "<span class='badge bg-success fs-6'><i class='fa-solid fa-crown me-1'></i> Vocero</span>";
                            $row_class = "table-success";
                        } elseif ($posicion == 1 && $nvo > 0) {
                            $puesto_badge = "<span class='badge bg-info text-dark fs-6'><i class='fa-solid fa-award me-1'></i> Suplente</span>";
                            $row_class = "table-info";
                        } else {
                            $puesto_badge = "<span class='badge bg-secondary'>" . ($posicion + 1) . "° Lugar</span>";
                        }
                        $posicion++;
                ?>
                        <tr class="<?= $row_class; ?>">
                            <td style="text-align: center; vertical-align: middle;" data-order="<?= $nvo; ?>" data-sort="<?= $nvo; ?>">
                                <?= $puesto_badge; ?>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <span class="fs-4 fw-bold text-dark"><?= number_format($nvo, 0, ',', '.'); ?></span>
                            </td>
                            <td style="text-align: center;">
                                <img src="<?= $foto; ?>" alt="<?= htmlspecialchars($d['nomusu']); ?>" style="width: 75px; height: 75px; object-fit: cover; border-radius: 50%; border: 2px solid #ccc;">
                            </td>
                            <td>
                                <div class="fw-bold text-uppercase fs-6">
                                    <?= htmlspecialchars($d['nomusu']); ?>
                                </div>
                                <small class="text-muted">Documento: <?= htmlspecialchars($d['ndocusu']); ?></small>
                            </td>
                            <td style="text-align: center;">
                                <a href="home.php?pg=1204&idusu=<?= $d['idusu']; ?>" class="btn btn-sm btn-outline-success" title="Ver Propuesta" target="_blank">
                                    <i class="fa-solid fa-file-lines me-1"></i> Propuesta
                                </a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-users-slash fa-2x mb-2 d-block"></i>
                            No hay candidatos a vocero inscritos para esta ficha.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <?php if ($totm > 0): ?>
            <tfoot class="table-light">
                <tr>
                    <th style="text-align: center;">Total</th>
                    <th style="text-align: center; font-size: 18px;"><?= number_format($totm, 0, ',', '.'); ?></th>
                    <th colspan="3" class="text-muted small align-middle">Votos totales registrados para la ficha</th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info shadow-sm d-flex align-items-center" role="alert">
        <i class="fa-solid fa-circle-info fa-2x me-3 text-info"></i>
        <div>
            <h6 class="alert-heading mb-1 fw-bold">Selección requerida</h6>
            Por favor, seleccione una jornada y una ficha para visualizar los resultados de la elección de voceros.
        </div>
    </div>
<?php endif; ?>
