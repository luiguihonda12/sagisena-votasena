<?php
// Vista Oficial y Plantilla Única Institucional: Acta Resultados Votaciones (ID 1217)
// Responsable: Yeison Stiven Molina Balceras
// Cronograma SAGI: views/votract.php | Controlador: controllers/votract.php | Modelo: models/votmact.php

// ==================================================================================
// MOTOR DE PLANTILLA ÚNICA INSTITUCIONAL SENA (REPRESENTANTE Y VOCERO)
// ==================================================================================
if (!function_exists('renderizarActaInstitucionalSena')) {
    function renderizarActaInstitucionalSena($cfg) {
        $ancho        = isset($cfg['ancho']) ? $cfg['ancho'] : 750;
        $titulo       = isset($cfg['titulo']) ? $cfg['titulo'] : 'ACTA DE VOTACIÓN SENA';
        $numeroActa   = isset($cfg['numero_acta']) ? $cfg['numero_acta'] : '001';
        $fecha        = isset($cfg['fecha']) ? $cfg['fecha'] : date('d/m/Y');
        $horaInicio   = isset($cfg['hora_inicio']) ? $cfg['hora_inicio'] : '08:00 Hrs';
        $horaFin      = isset($cfg['hora_fin']) ? $cfg['hora_fin'] : '18:00 Hrs';
        $lugar        = isset($cfg['lugar']) ? $cfg['lugar'] : 'Centro de Desarrollo Agroempresarial - Chía';
        $regional     = isset($cfg['regional']) ? $cfg['regional'] : 'Regional Cundinamarca, Centro de Desarrollo Agroempresarial';
        $tema         = isset($cfg['tema']) ? $cfg['tema'] : 'Votaciones SENA';
        $objetivo     = isset($cfg['objetivo']) ? $cfg['objetivo'] : '';
        $desarrollo   = isset($cfg['desarrollo']) ? $cfg['desarrollo'] : '';
        $secciones    = isset($cfg['secciones']) ? $cfg['secciones'] : [];
        $esVocero     = (isset($cfg['tipo']) && $cfg['tipo'] === 'vocero');

        // Función auxiliar para imágenes seguras
        $logoSena = function_exists('urlimg') ? urlimg('image/sena.png') : '';
        if (empty($logoSena) && file_exists('image/sena.png')) {
            $logoSena = 'data:image/png;base64,' . base64_encode(file_get_contents('image/sena.png'));
        }

        $h = '';
        $h .= '<body>';
        
        // Encabezado con Logo Oficial
        $h .= '<table width="'.$ancho.'px" style="margin: 0 auto; font-family: Arial, sans-serif;">';
        $h .= '<tr><td style="text-align: center;">';
        if ($logoSena) {
            $h .= '<img src="' . $logoSena . '" width="80px"><br><br>';
        } else {
            $h .= '<h2 style="color: #39a900; margin: 0;">SENA</h2><br>';
        }
        $h .= '</td></tr>';
        $h .= '</table>';

        // Estructura Principal del Acta
        $h .= '<table width="'.$ancho.'px" cellpadding="5px" cellspacing="0" style="margin: 0 auto; font-family: Arial, sans-serif; border-collapse: collapse;">';
        
        // Número y Título
        $h .= '<tr>';
        $h .= '<td style="text-align: center; border: 1px solid #000;" colspan="3"><strong>ACTA No. ' . htmlspecialchars($numeroActa) . '</strong></td>';
        $h .= '</tr>';
        $h .= '<tr>';
        $h .= '<th style="text-align: center; border: 1px solid #000; background-color: #f2f2f2; padding: 10px;" colspan="3">' . mb_strtoupper($titulo, 'UTF-8') . '</th>';
        $h .= '</tr>';

        // Metadatos de la Sesión
        $h .= '<tr>';
        $h .= '<td style="border: 1px solid #000; width: 34%;"><strong>CIUDAD Y FECHA</strong><br>' . htmlspecialchars($fecha) . '</td>';
        $h .= '<td style="border: 1px solid #000; width: 33%;"><strong>HORA INICIO</strong><br>' . htmlspecialchars($horaInicio) . '</td>';
        $h .= '<td style="border: 1px solid #000; width: 33%;"><strong>HORA FIN</strong><br>' . htmlspecialchars($horaFin) . '</td>';
        $h .= '</tr>';

        $h .= '<tr>';
        $h .= '<td style="border: 1px solid #000;"><strong>LUGAR Y/O ENLACE</strong><br>' . htmlspecialchars($lugar) . '</td>';
        $h .= '<td colspan="2" style="border: 1px solid #000;"><strong>DIRECCIÓN GENERAL / REGIONAL / CENTRO</strong><br>' . htmlspecialchars($regional) . '</td>';
        $h .= '</tr>';

        $h .= '<tr>';
        $h .= '<td colspan="3" style="border: 1px solid #000;"><strong>TEMA</strong><br>' . $tema . '</td>';
        $h .= '</tr>';

        $h .= '<tr>';
        $h .= '<td colspan="3" style="border: 1px solid #000;"><strong>OBJETIVOS</strong><br>' . $objetivo . '</td>';
        $h .= '</tr>';

        $h .= '<tr>';
        $h .= '<td colspan="3" style="border: 1px solid #000; text-align: justify;"><strong>DESARROLLO</strong><br>' . $desarrollo . '<br><br></td>';
        $h .= '</tr>';

        // Secciones Dinámicas de Resultados (Por Jornada para Representante o Por Ficha para Vocero)
        foreach ($secciones as $sec) {
            $subtitulo  = isset($sec['subtitulo']) ? $sec['subtitulo'] : 'RESULTADOS';
            $candidatos = isset($sec['candidatos']) ? $sec['candidatos'] : [];
            $totalVotos = isset($sec['total_votos']) ? (int)$sec['total_votos'] : 0;

            $h .= '<tr>';
            $h .= '<td colspan="3" style="border: 1px solid #000; text-align: center; background-color: #e9ecef; font-weight: bold; padding: 8px;">' . mb_strtoupper($subtitulo, 'UTF-8') . '</td>';
            $h .= '</tr>';

            $h .= '<tr>';
            $h .= '<td colspan="3" style="padding: 0;">';
            $h .= '<table width="100%" cellpadding="6px" cellspacing="0" border="1" style="border-collapse: collapse; margin: 0; font-family: Arial, sans-serif;">';
            $h .= '<tr style="background-color: #d1cfcf;">';
            $h .= '<th style="border: 1px solid #000; width: 45%; text-align: center;">CANDIDATO</th>';
            $h .= '<th style="border: 1px solid #000; width: 25%; text-align: center;">N° VOTOS</th>';
            $h .= '<th style="border: 1px solid #000; width: 30%; text-align: center;">PORCENTAJE</th>';
            $h .= '</tr>';

            if (!empty($candidatos)) {
                foreach ($candidatos as $idx => $can) {
                    $nom = htmlspecialchars($can['nomusu']);
                    $v   = (int)$can['total_votos'];
                    $pct = ($totalVotos > 0) ? round(($v / $totalVotos) * 100, 1) . '%' : '0%';

                    // Distinción de Rol: En Vocero 1° Vocero y 2° Suplente. En Representante solo 1° Representante Electo.
                    $badgeRol = '';
                    if ($idx === 0 && $v > 0) {
                        $badgeRol = $esVocero ? '<span style="color: #117f09; font-weight: bold;">(VOCERO ELECTO)</span><br>' : '<span style="color: #117f09; font-weight: bold;">(REPRESENTANTE ELECTO)</span><br>';
                    } elseif ($idx === 1 && $v > 0 && $esVocero) {
                        $badgeRol = '<span style="color: #0d6efd; font-weight: bold;">(SUPLENTE)</span><br>';
                    }

                    // Foto segura
                    $fotoCan = '';
                    $rutaFoto = !empty($can['fotcan']) ? $can['fotcan'] : 'img/user.jpg';
                    if (function_exists('imagenes')) {
                        $fotoCan = imagenes($rutaFoto);
                    } elseif (file_exists($rutaFoto)) {
                        $fotoCan = 'data:image/png;base64,' . base64_encode(file_get_contents($rutaFoto));
                    }

                    $h .= '<tr>';
                    $h .= '<td style="border: 1px solid #000; text-align: center; vertical-align: middle; padding: 10px;">';
                    $h .= $badgeRol . '<strong>' . $nom . '</strong><br>';
                    if ($fotoCan) {
                        $h .= '<img src="' . $fotoCan . '" width="80px" height="80px" style="border-radius: 50%; border: 1px solid #999; margin-top: 5px;"><br>';
                    }
                    $h .= '</td>';
                    $h .= '<td style="border: 1px solid #000; text-align: center; vertical-align: middle; font-size: 18px; font-weight: bold;">' . $v . '</td>';
                    $h .= '<td style="border: 1px solid #000; text-align: center; vertical-align: middle; font-size: 16px;">' . $pct . '</td>';
                    $h .= '</tr>';
                }
            } else {
                $h .= '<tr><td colspan="3" style="text-align: center; padding: 15px; border: 1px solid #000;">No se registraron candidatos ni votos.</td></tr>';
            }

            $h .= '<tr style="background-color: #f8f9fa;">';
            $h .= '<td style="border: 1px solid #000; text-align: right; font-weight: bold;">TOTAL VOTACIÓN:</td>';
            $h .= '<td style="border: 1px solid #000; text-align: center; font-weight: bold; font-size: 18px;">' . $totalVotos . '</td>';
            $h .= '<td style="border: 1px solid #000; text-align: center; font-weight: bold;">100%</td>';
            $h .= '</tr>';
            $h .= '</table>';
            $h .= '</td>';
            $h .= '</tr>';
        }

        // Sección de Conclusiones y Compromisos
        $h .= '<tr>';
        $h .= '<td colspan="3" style="border: 1px solid #000; text-align: center; background-color: #e9ecef; font-weight: bold;">CONCLUSIONES Y COMPROMISOS</td>';
        $h .= '</tr>';
        $h .= '<tr>';
        $h .= '<td colspan="3" style="border: 1px solid #000; padding: 8px;">';
        $h .= '<table width="100%" cellpadding="5px" cellspacing="0" border="1" style="border-collapse: collapse; margin: 0; font-family: Arial, sans-serif;">';
        $h .= '<tr style="background-color: #d1cfcf;">';
        $h .= '<th style="border: 1px solid #000; width: 50%;">ACTIVIDAD / COMPROMISO</th>';
        $h .= '<th style="border: 1px solid #000; width: 30%;">RESPONSABLE</th>';
        $h .= '<th style="border: 1px solid #000; width: 20%;">FECHA</th>';
        $h .= '</tr>';
        $h .= '<tr>';
        $h .= '<td style="border: 1px solid #000;">Posesión oficial y firma de actas de compromiso institucional</td>';
        $h .= '<td style="border: 1px solid #000;">Candidatos electos / Coordinación Académica</td>';
        $h .= '<td style="border: 1px solid #000; text-align: center;">Inmediata</td>';
        $h .= '</tr>';
        $h .= '<tr>';
        $h .= '<td style="border: 1px solid #000;">Notificación y archivo formal del proceso electoral en SAGISENA</td>';
        $h .= '<td style="border: 1px solid #000;">Comité Electoral SENA</td>';
        $h .= '<td style="border: 1px solid #000; text-align: center;">' . htmlspecialchars($fecha) . '</td>';
        $h .= '</tr>';
        $h .= '</table>';
        $h .= '</td>';
        $h .= '</tr>';

        // Tabla Oficial de Asistentes y Firmas
        $h .= '<tr>';
        $h .= '<td colspan="3" style="border: 1px solid #000; text-align: center; background-color: #e9ecef; font-weight: bold;">ASISTENCIA Y FIRMAS DE CONFORMIDAD</td>';
        $h .= '</tr>';
        $h .= '<tr>';
        $h .= '<td colspan="3" style="padding: 0;">';
        $h .= '<table width="100%" cellpadding="15px" cellspacing="0" border="1" style="border-collapse: collapse; margin: 0; font-family: Arial, sans-serif;">';
        $h .= '<tr>';
        $h .= '<td style="width: 50%; border: 1px solid #000; text-align: center; vertical-align: bottom; height: 75px;"><br><br>____________________________________<br><strong>Subdirector(a) de Centro / Delegado</strong></td>';
        $h .= '<td style="width: 50%; border: 1px solid #000; text-align: center; vertical-align: bottom; height: 75px;"><br><br>____________________________________<br><strong>Coordinador(a) Misional / Académico</strong></td>';
        $h .= '</tr>';
        $h .= '<tr>';
        $h .= '<td style="width: 50%; border: 1px solid #000; text-align: center; vertical-align: bottom; height: 75px;"><br><br>____________________________________<br><strong>Líder de Bienestar al Aprendiz</strong></td>';
        $h .= '<td style="width: 50%; border: 1px solid #000; text-align: center; vertical-align: bottom; height: 75px;"><br><br>____________________________________<br><strong>Representante de Aprendices / Testigo</strong></td>';
        $h .= '</tr>';
        $h .= '</table>';
        $h .= '</td>';
        $h .= '</tr>';

        $h .= '</table>';
        $h .= '</body>';

        return $h;
    }
}

// ==================================================================================
// MODO 1: Si es invocado por controllers/votract.php para compilar el PDF Representante:
// ==================================================================================
if (isset($ancho) && isset($fecha) && isset($dat)) {
    // Agrupar candidatos por jornada
    $candidatosPorJornada = [];
    $totalVotosPorJornada = [];
    foreach ($dat as $d) {
        $jorId = $d['jornada'];
        if (!isset($candidatosPorJornada[$jorId])) {
            $candidatosPorJornada[$jorId] = [];
            $totalVotosPorJornada[$jorId] = 0;
        }
        $candidatosPorJornada[$jorId][] = $d;
        $totalVotosPorJornada[$jorId] += (int)$d['total_votos'];
    }

    $secciones = [];
    if (isset($djor) && $djor) {
        foreach ($djor as $dj) {
            $jorId = $dj['idval'];
            if (isset($candidatosPorJornada[$jorId])) {
                $secciones[] = [
                    'subtitulo'   => 'RESULTADOS JORNADA ' . $dj['nomval'],
                    'candidatos'  => $candidatosPorJornada[$jorId],
                    'total_votos' => $totalVotosPorJornada[$jorId]
                ];
            }
        }
    }

    $cfg = [
        'tipo'        => 'representante',
        'ancho'       => $ancho,
        'titulo'      => 'ACTA DE VOTACIÓN DE REPRESENTANTES DE APRENDICES SENA',
        'numero_acta' => '001',
        'fecha'       => $fecha,
        'hora_inicio' => $hora_inicio,
        'hora_fin'    => $hora_fin,
        'lugar'       => 'Bienestar al Aprendiz - Centro de Desarrollo Agroempresarial',
        'regional'    => 'Regional Cundinamarca, Centro de Desarrollo Agroempresarial',
        'tema'        => '<strong>1.</strong> Acta de escrutinio y elección de Representantes de Aprendices SENA período ' . $año . ' - ' . $fcan . '.',
        'objetivo'    => 'Consolidar y formalizar los resultados de las votaciones para Representante de Aprendices SENA del año ' . $año . ', declarando de forma transparente al ganador por cada jornada de formación.',
        'desarrollo'  => 'En sesión oficial de escrutinio electoral para la elección de Representantes de Aprendices SENA año ' . $año . ', se detallan los votos escrutados por candidato para las diferentes jornadas formativas (Mañana, Tarde, Noche, Fin de Semana, Virtual), declarando al candidato electo por mayoría de votos.',
        'secciones'   => $secciones
    ];

    $html = renderizarActaInstitucionalSena($cfg);
    return;
}

// ==================================================================================
// MODO 2: Vista Web Interactiva (home.php?pg=1217)
// ==================================================================================
$tab    = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'representante';
$fidcen = isset($_REQUEST['fidcen']) ? $_REQUEST['fidcen'] : NULL;
$fidjor = isset($_REQUEST['fidjor']) ? $_REQUEST['fidjor'] : NULL;
$fidfic = isset($_REQUEST['fidfic']) ? $_REQUEST['fidfic'] : NULL;
?>

<div class="conte">
    <?php echo titulo2("<i class='" . (isset($icono) ? $icono : 'fa-file-signature') . "'></i> Generación de Actas de Votación", 2); ?>
</div>

<ul class="nav nav-tabs mb-4" style="border-bottom: 2px solid #117f09;">
  <li class="nav-item">
    <a class="nav-link <?= ($tab == 'representante') ? 'active' : '' ?>" href="home.php?pg=<?= $pg ?>&tab=representante" style="<?= ($tab == 'representante') ? 'font-weight: bold; color: #117f09; border-bottom: 2px solid #117f09;' : 'color: #555;' ?>">
        <i class="fa-solid fa-users me-1"></i> Acta de Representante
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= ($tab == 'vocero') ? 'active' : '' ?>" href="home.php?pg=<?= $pg ?>&tab=vocero" style="<?= ($tab == 'vocero') ? 'font-weight: bold; color: #117f09; border-bottom: 2px solid #117f09;' : 'color: #555;' ?>">
        <i class="fa-solid fa-bullhorn me-1"></i> Acta de Vocero
    </a>
  </li>
</ul>

<div class="tab-content mt-4">
<?php if ($tab == 'representante'): ?>
    <?php 
        require_once 'models/votmact.php';
        $votmact = new Votmact();
        
        require_once("models/conexion.php");
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sqlCen = "SELECT idcen, nomcen FROM centro";
        $stmtCen = $conexion->prepare($sqlCen);
        $stmtCen->execute();
        $dcen = $stmtCen->fetchAll(PDO::FETCH_ASSOC);
        
        $djor = $votmact->getJor();
    ?>
    <div class="card shadow-sm border-0 p-3 mb-4 bg-light">
        <form class="form-default-pages" action="home.php?pg=<?= $pg ?>&tab=representante" method="POST">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="fidjor" class="form-label fw-semibold text-secondary">
                        <i class="fa-solid fa-clock me-1"></i> Filtrar por Jornada
                    </label>
                    <select name="fidjor" id="fidjor" class="form-select" onchange="this.form.submit();">
                        <option value="">-- Todas las jornadas (Escrutinio General) --</option>
                        <?php if ($djor): ?>
                            <?php foreach ($djor as $dj): ?>
                                <option value="<?= $dj['idval']; ?>" <?= ($fidjor == $dj['idval']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($dj['nomval']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="fidcen" class="form-label fw-semibold text-secondary">
                        <i class="fa-solid fa-building me-1"></i> Centro de Formación
                    </label>
                    <select name="fidcen" id="fidcen" class="form-select" onchange="this.form.submit();">
                        <option value="">-- Todos los centros --</option>
                        <?php if ($dcen): ?>
                            <?php foreach ($dcen as $dc): ?>
                                <option value="<?= $dc['idcen']; ?>" <?= ($fidcen == $dc['idcen']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($dc['nomcen']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <a href="views/pdfact.php?pdf=ok&fidcen=<?= $fidcen; ?>&fidjor=<?= $fidjor; ?>" target="_blank" class="btn btn-success fw-bold shadow-sm w-100" title="Imprimir Acta de Representante">
                        <i class="fas fa-print me-1"></i> Emitir PDF
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Vista Previa en Pantalla del Acta -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
            <span><i class="fa-solid fa-file-pdf me-2 text-danger"></i>Vista Previa Oficial del Acta de Representantes</span>
            <small class="text-white-50">Documento oficial SENA</small>
        </div>
        <div class="card-body p-4 bg-white" style="overflow-x: auto;">
            <?php require_once 'controllers/votract.php'; ?>
        </div>
    </div>

<?php elseif ($tab == 'vocero'): ?>
    <?php require_once 'controllers/votcrcv.php'; ?>
    <div class="card shadow-sm border-0 p-3 mb-4 bg-light">
        <form action="home.php?pg=<?= $pg ?>&tab=vocero" method="POST" id="fichaForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="idjor_vocero" class="form-label fw-semibold text-secondary">
                        <i class="fa-solid fa-clock me-1"></i> Filtrar Jornada
                    </label>
                    <select name="fidjor" id="idjor_vocero" class="form-select" onchange="document.getElementById('idfic_vocero').value=''; this.form.submit();">
                        <option value="">-- Todas las jornadas --</option>
                        <?php if (isset($jornadas) && $jornadas): ?>
                            <?php foreach ($jornadas as $jor): ?>
                                <option value="<?= $jor['idval']; ?>" <?= ($fidjor == $jor['idval']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($jor['nomval']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="idfic_vocero" class="form-label fw-semibold text-secondary">
                        <i class="fa-solid fa-hashtag me-1"></i> Seleccionar Ficha
                    </label>
                    <select name="fidfic" id="idfic_vocero" class="form-select ficha-select" onchange="this.form.submit();">
                        <option value="">-- Seleccione una ficha --</option>
                        <?php if (isset($fichas) && $fichas): ?>
                            <?php foreach ($fichas as $ficha): ?>
                                <option value="<?= $ficha['idfic']; ?>" <?= ($fidfic == $ficha['idfic']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($ficha['idfic']); ?> - <?= htmlspecialchars($ficha['nomfic']); ?> (<?= htmlspecialchars($ficha['nomval']); ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <?php if ($fidfic): ?>
                        <a href="javascript:void(0);" onclick="descargarPDFVocero()" class="btn btn-success fw-bold shadow-sm w-100" title="Imprimir Acta de Vocero">
                            <i class="fas fa-print me-1"></i> Emitir PDF
                        </a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary w-100" disabled>
                            <i class="fas fa-print me-1"></i> Emitir PDF
                        </button>
                    <?php endif; ?>
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
        var idfic = document.getElementById('idfic_vocero').value;
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

    <?php if ($fidfic): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-file-pdf me-2 text-danger"></i>Vista Previa Oficial del Acta de Voceros</span>
                <small class="text-white-50">Documento oficial SENA</small>
            </div>
            <div class="card-body p-4 bg-white" style="overflow-x: auto;">
                <?php require 'controllers/votractvoc.php'; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info shadow-sm d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-info fa-2x me-3 text-info"></i>
            <div>
                <h6 class="alert-heading mb-1 fw-bold">Selección requerida</h6>
                Por favor, elija una jornada y ficha para generar y previsualizar el acta de elección de voceros.
            </div>
        </div>
    <?php endif; ?>

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
