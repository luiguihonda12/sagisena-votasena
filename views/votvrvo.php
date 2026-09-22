<?php
// Vista Oficial: Resultados Generales (ID 1208)
// Responsable: Yeison Stiven Molina Balceras
// Cronograma SAGI: views/votvrvo.php | Controlador: controllers/votcrvo.php | Modelo: models/votmrvo.php

$tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'representante';
?>
<div class="conte">
    <?php echo titulo2("<i class='" . (isset($icono) ? $icono : 'fa-chart-pie') . "'></i> Resultados Electorales", 2); ?>
</div>

<ul class="nav nav-tabs mb-4" style="border-bottom: 2px solid #117f09;">
  <li class="nav-item">
    <a class="nav-link <?= ($tab == 'representante') ? 'active' : '' ?>" href="home.php?pg=<?= $pg ?>&tab=representante" style="<?= ($tab == 'representante') ? 'font-weight: bold; color: #117f09; border-bottom: 2px solid #117f09;' : 'color: #555;' ?>">Resultados Representante</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= ($tab == 'vocero') ? 'active' : '' ?>" href="home.php?pg=1213" style="<?= ($tab == 'vocero') ? 'font-weight: bold; color: #117f09; border-bottom: 2px solid #117f09;' : 'color: #555;' ?>">Resultados Vocero <i class="fa-solid fa-arrow-up-right-from-square ms-1 small"></i></a>
  </li>
</ul>

<div class="tab-content mt-4">
<?php if ($tab == 'representante'): ?>
    <?php require_once 'controllers/votcrvo.php'; ?>
    <div class="row">
        <div class="form-group col-md-10">
            <form class="form-default-pages" action="home.php?pg=<?= $pg ?>&tab=representante" method="POST">
                <div class="row">
                    <div class="form-group col-md-5">
                        <label for="idfic" class="fw-semibold">Jornada</label>
                        <select name="fidjor" id="idfic" class="form-select form--input-default" onchange="this.form.submit();">
                            <?php
                            if (isset($djor) && $djor) {
                                foreach ($djor as $dj) {
                            ?>
                                    <option value="<?= $dj['idval']; ?>" <?php if (isset($fidjor) && $fidjor == $dj['idval']) echo " selected "; ?>><?= $dj['nomval']; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-7">
                        <label for="idcen" class="fw-semibold">Centro de Formación</label>
                        <select name="fidcen" id="idcen" class="form-select form--input-default" onchange="this.form.submit();">
                            <?php
                            if (isset($dcen) && $dcen) {
                                foreach ($dcen as $dj) {
                            ?>
                                    <option value="<?= $dj['idcen']; ?>" <?php if (isset($fidcen) && $fidcen == $dj['idcen']) echo " selected "; ?>><?= $dj['nomcen']; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="form-group col-md-1" style="text-align: center;">
            <br>
            <a href="views/pdfact.php?pdf=ok&fidcen=<?= isset($fidcen) ? $fidcen : ''; ?>&fidjor=<?= isset($fidjor) ? $fidjor : ''; ?>" target="_blank" title="Imprimir Acta de Representante">
                <i class="fas fa-print fa-2x text-success"></i>
            </a>
        </div>
        <div class="form-group col-md-1" style="text-align: center;">
            <br>
            <a href="views/vrfivt.php" title="Listado de Votantes" target="_blank">
                <i class="fa-regular fa-file-lines fa-2x text-primary"></i>
            </a>
        </div>
    </div>

    <!-- Tabla de Candidatos Ordenados por Votos -->
    <div class="table-responsive mt-4">
        <table id="example" class="table table-striped table-hover align-middle" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th style="text-align: center; width: 15%;">Puesto / Votos</th>
                    <th style="text-align: center; width: 15%;">Foto</th>
                    <th>Candidato a Representante</th>
                    <th style="text-align: center; width: 20%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totm = 0;
                if (isset($dat) && $dat) {
                    $candidatos = [];
                    foreach ($dat as $d) {
                        $nvo = isset($votmrvo) ? $votmrvo->nvoCan($d['idusu']) : 0;
                        $nvo = isset($nvo[0]['nvo']) ? (int)$nvo[0]['nvo'] : 0;
                        $d['votos_obtenidos'] = $nvo;
                        $candidatos[] = $d;
                        $totm += $nvo;
                    }
                    
                    // Orden descendente estricto por número de votos
                    usort($candidatos, function($a, $b) {
                        return $b['votos_obtenidos'] <=> $a['votos_obtenidos'];
                    });

                    foreach ($candidatos as $puesto => $d) {
                        $foto = (!empty($d['fotcan']) && file_exists($d['fotcan'])) ? $d['fotcan'] : "img/user.jpg";
                        $esGanador = ($puesto === 0 && $d['votos_obtenidos'] > 0);
                ?>
                        <tr class="<?= $esGanador ? 'table-success' : ''; ?>">
                            <td style="text-align: center;" data-order="<?= (int)$d['votos_obtenidos']; ?>" data-sort="<?= (int)$d['votos_obtenidos']; ?>">
                                <?php if ($esGanador): ?>
                                    <span class="badge bg-warning text-dark mb-1"><i class="fa-solid fa-crown me-1"></i>1° Electo</span><br>
                                <?php else: ?>
                                    <span class="badge bg-secondary mb-1"><?= ($puesto + 1); ?>° Lugar</span><br>
                                <?php endif; ?>
                                <span class="fs-4 fw-bold <?= $esGanador ? 'text-success' : 'text-dark'; ?>">
                                    <?= number_format($d['votos_obtenidos'], 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <img src="<?= $foto; ?>" alt="<?= htmlspecialchars($d['nomusu']); ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 3px solid <?= $esGanador ? '#28a745' : '#dee2e6'; ?>;">
                            </td>
                            <td>
                                <div class="fw-bold text-uppercase fs-5">
                                    <?= htmlspecialchars($d['nomusu']); ?>
                                </div>
                                <div class="text-muted small">
                                    <span><strong>Doc:</strong> <?= htmlspecialchars($d['ndocusu']); ?></span>
                                    <?php if (!empty($d['noca'])): ?>
                                        <span class="ms-2 badge bg-light text-dark border">Tarjetón #<?= htmlspecialchars($d['noca']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <a href="home.php?pg=1204&idusu=<?= $d['idusu']; ?>" class="btn btn-sm btn-outline-success" title="Visualizar Propuestas">
                                    <i class="fa-solid fa-eye me-1"></i> Propuesta
                                </a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-users-slash fa-2x mb-2 d-block"></i>
                            No hay candidatos registrados para la jornada y centro seleccionados.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <?php if ($totm > 0): ?>
            <tfoot class="table-light">
                <tr>
                    <th style="text-align: center;">Total Votos: <?= number_format($totm, 0, ',', '.'); ?></th>
                    <th colspan="3"></th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>

<?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    let h1 = document.querySelector(".title-page");
    if (h1) {
        document.title = h1.textContent.trim();
    }
});
</script>
