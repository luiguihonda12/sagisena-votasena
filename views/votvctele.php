<?php require_once 'controllers/votcctele.php'; ?>

<?php
if (!function_exists('inicialesNombres')) {
    function inicialesNombres($nombre) {
        $partes = preg_split('/\s+/', trim((string)$nombre));
        $ini = '';
        $cont = 0;
        foreach ($partes as $p) {
            if ($p === '' || $p === '') continue;
            $ini .= strtoupper(substr($p, 0, 1));
            $cont++;
            if ($cont >= 2) break;
        }
        return $ini !== '' ? $ini : '?';
    }
}
?>

<?php echo titulo2("<i class='" . $icono . "'></i> Cartón Electoral", 2); ?>

<style>
    :root {
        --vot-verde: #117f09;
        --vot-verde-claro: #00af00;
        --vot-azul: #123a1f;
        --vot-dorado: #ffc800;
        --vot-sombra: 0 18px 40px rgba(17, 127, 9, .22);
        --vot-border: 18px;
    }

    /* Barra de acciones (imprimir) - oculta al imprimir */
    .vot-print-toolbar {
        max-width: 1200px;
        margin: 0 auto 22px auto;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 12px;
        padding: 14px 20px;
        background: linear-gradient(135deg, #0a3d1a 0%, #117f09 55%, #00af00 100%);
        border-radius: 16px;
        box-shadow: 0 10px 28px rgba(10, 61, 26, .18);
        border-left: 6px solid var(--vot-dorado);
        position: relative;
        overflow: hidden;
    }
    .vot-print-toolbar .vot-info-periodo {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #fff;
        font-size: 13.5px;
        font-weight: 600;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .35);
        border-radius: 30px;
        padding: 7px 15px;
    }
    .vot-print-toolbar .vot-info-periodo i {
        margin-right: 4px;
    }
    .btn-vot-print {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        border: none;
        border-radius: 30px;
        padding: 11px 24px;
        background: var(--vot-dorado);
        color: #17321a;
        font-weight: 800;
        font-size: 14px;
        letter-spacing: .5px;
        text-transform: uppercase;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .25);
        cursor: pointer;
        transition: transform .2s ease, filter .2s ease;
    }
    .btn-vot-print:hover {
        filter: brightness(1.06);
        transform: translateY(-2px);
    }

    /* Núcleo imprimible */
    .vot-wrap {
        max-width: 1200px;
        margin: 0 auto 30px auto;
    }

    .votacion-intro {
        text-align: center;
        max-width: 760px;
        margin: 0 auto 30px auto;
    }
    .votacion-intro .votacion-sub {
        color: #5b635b;
        font-size: 14.5px;
        line-height: 1.65;
    }
    .votacion-intro .votacion-sub i {
        color: var(--vot-verde);
        margin-right: 6px;
    }
    .votacion-intro .votacion-sub strong {
        color: var(--vot-verde);
    }

    /* Banner institucional */
    .votacion-banner {
        background: linear-gradient(135deg, #0a3d1a 0%, #117f09 55%, #00af00 100%);
        border-radius: var(--vot-border);
        padding: 22px 28px;
        color: #fff;
        margin: 0 auto 30px auto;
        max-width: 1040px;
        box-shadow: 0 10px 28px rgba(10, 61, 26, .35);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        border-left: 6px solid var(--vot-dorado);
        position: relative;
        overflow: hidden;
    }
    .votacion-banner::after {
        content: "\f0c0";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        right: -20px;
        bottom: -34px;
        font-size: 150px;
        color: rgba(255, 255, 255, .07);
    }
    .votacion-banner.banner-voceros::after {
        content: "\f0a1";
    }
    .votacion-banner.banner-voceros {
        background: linear-gradient(135deg, #083a5e 0%, #0f5c8c 55%, #1b83bd 100%);
        border-left-color: #ffdd59;
        box-shadow: 0 10px 28px rgba(8, 58, 94, .35);
    }
    .votacion-banner-info h4 {
        margin: 0 0 5px 0;
        font-size: 1.45rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: .3px;
    }
    .votacion-banner-info p {
        margin: 0;
        font-size: 1rem;
        color: #e8f7e6;
    }
    .votacion-banner.banner-voceros .votacion-banner-info p {
        color: #dceefb;
    }
    .votacion-badge-total {
        background: rgba(255, 255, 255, .16);
        border: 1px solid rgba(255, 255, 255, .4);
        border-radius: 30px;
        padding: 9px 18px;
        font-size: 1rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(2px);
    }

    /* Rejilla de tarjetas */
    .votacion-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: stretch;
        gap: 26px;
        padding: 6px 0 26px 0;
        max-width: 1200px;
        margin: 0 auto;
    }

    .vot-separador {
        text-align: center;
        margin: 6px auto 30px auto;
        max-width: 1200px;
    }
    .vot-separador span {
        display: inline-block;
        background: #fff;
        border: 1px solid #e3e8e3;
        border-radius: 40px;
        padding: 10px 26px;
        font-weight: 800;
        font-size: 15px;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: var(--vot-azul);
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
    }
    .vot-separador span i {
        color: var(--vot-verde);
        margin-right: 8px;
    }

    /* Tarjeta candidato */
    .candidato-card {
        width: 262px;
        background: #fff;
        border: 1px solid #e3e8e3;
        border-radius: var(--vot-border);
        overflow: hidden;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .candidato-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--vot-sombra);
        border-color: #cfe8cd;
    }

    /* Foto */
    .candidato-foto {
        position: relative;
        height: 210px;
        background: #f2f5f2;
        overflow: hidden;
        flex-shrink: 0;
    }
    .candidato-foto img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .candidato-foto.placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(160deg, #e8f4e8, #d6e8d6);
        color: #83a983;
    }
    .avatar-iniciales {
        width: 104px;
        height: 104px;
        border-radius: 50%;
        background: linear-gradient(135deg, #117f09, #00af00);
        color: #fff;
        font-family: Arial, Helvetica, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: 800;
        letter-spacing: 1px;
        box-shadow: 0 10px 22px rgba(17, 127, 9, .35);
        border: 4px solid #fff;
        text-shadow: 0 2px 5px rgba(0, 0, 0, .25);
    }
    .candidato-num {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(15, 70, 8, .88);
        color: #fff;
        font-weight: 800;
        font-size: 13px;
        padding: 6px 13px;
        border-radius: 30px;
        letter-spacing: .6px;
        backdrop-filter: blur(2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .28);
        z-index: 2;
        display: inline-flex;
        align-items: center;
    }
    .candidato-num::before {
        content: "\f2bd";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        margin-right: 6px;
    }

    /* Cuerpo de la tarjeta */
    .candidato-info {
        padding: 16px 16px 18px 16px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .candidato-nombre {
        color: #17321a;
        font-size: 15px;
        font-weight: 800;
        line-height: 1.35;
        min-height: 42px;
        letter-spacing: .2px;
        margin-bottom: 5px;
    }
    .candidato-ficha {
        color: #6b756b;
        font-size: 12px;
        margin-bottom: 10px;
    }
    .candidato-ficha i {
        color: var(--vot-verde);
        margin-right: 5px;
    }
    .candidato-lema {
        background: #f6faf6;
        border-left: 4px solid var(--vot-verde);
        border-radius: 0 8px 8px 0;
        padding: 8px 11px;
        font-size: 12px;
        font-style: italic;
        color: #4c5a4c;
        text-align: left;
        line-height: 1.45;
        margin: 0 0 14px 0;
        flex-grow: 1;
    }
    .candidato-lema i {
        color: var(--vot-verde);
        margin-right: 4px;
    }

    /* Chip informativo (reemplaza el botón de votar) */
    .candidato-info-chip {
        width: 100%;
        border: 2px solid #cdd5cd;
        border-radius: 10px;
        padding: 11px;
        background: #fafbfa;
        color: #6b756b;
        font-weight: 700;
        font-size: 12px;
        letter-spacing: .8px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        cursor: default;
    }
    .candidato-info-chip i {
        color: var(--vot-verde);
    }

    /* Tarjeta voto en blanco */
    .candidato-card.card-blanca {
        border: 2px dashed #b9c2b9;
        background: #fafbfa;
    }
    .candidato-card.card-blanca:hover {
        border-color: var(--vot-verde);
        box-shadow: 0 18px 40px rgba(17, 127, 9, .14);
    }
    .card-blanca .candidato-foto {
        background: linear-gradient(160deg, #ffffff, #edf1ed);
        color: #a9b4a9;
    }
    .card-blanca .candidato-nombre {
        color: #5b645b;
    }
    .card-blanca .candidato-lema {
        border-left-color: #a9b4a9;
    }
    .icono-blanca {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        background: #fff;
        color: #a9b4a9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        box-shadow: 0 8px 18px rgba(0, 0, 0, .08);
        border: 2px dashed #cdd5cd;
    }
    .card-blanca .candidato-info-chip {
        border-style: solid;
        color: #4c5a4c;
    }

    /* Animación de entrada */
    @keyframes aparecerVot {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .candidato-card {
        animation: aparecerVot .5s ease both;
    }
    .candidato-card:nth-child(2) { animation-delay: .1s; }
    .candidato-card:nth-child(3) { animation-delay: .2s; }
    .candidato-card:nth-child(4) { animation-delay: .3s; }

    .votacion-nota {
        text-align: center;
        color: #8a938a;
        font-size: 13px;
        margin: 6px 0 18px 0;
    }
    .votacion-nota i {
        color: var(--vot-verde);
        margin-right: 6px;
    }

    @media (max-width: 768px) {
        .candidato-card { width: 100%; max-width: 300px; }
        .candidato-foto { height: 180px; }
    }

    /* ===== IMPRESIÓN: solo el cartón electoral ===== */
    @media print {
        @page {
            margin: 8mm;
        }
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .header-home,
        .main-menu,
        .footer-sena,
        .btnayu,
        #err,
        .tit,
        .vot-print-toolbar,
        .votacion-intro,
        .votacion-nota,
        .vot-section-voceros {
            display: none !important;
        }
        .contenido,
        .contenido2 {
            margin: 0 !important;
            padding: 0 !important;
        }
        .vot-wrap {
            max-width: 100%;
            margin: 0;
        }
        .votacion-banner {
            max-width: 100%;
            margin: 0 0 12px 0;
        }
        .votacion-grid {
            gap: 12px;
            padding: 4px 0;
        }
        .candidato-card,
        .votacion-banner {
            page-break-inside: avoid;
        }
        .candidato-card {
            width: 245px;
            box-shadow: none;
            animation: none;
        }
        .candidato-card:hover {
            transform: none;
            box-shadow: none;
        }
        .votacion-banner-info h4 {
            font-size: 1.2rem;
        }
        .candidato-foto {
            height: 170px;
        }
        a {
            text-decoration: none !important;
        }
    }
</style>

<!-- Barra de acciones: imprimir -->
<div class="vot-print-toolbar">
    <span class="vot-info-periodo">
        <i class="fa-solid <?= $periodoVotacion ? 'fa-door-open' : 'fa-lock'; ?>"></i>
        Periodo de votación: <strong><?= $periodoVotacion ? 'Abierto' : 'Cerrado'; ?></strong>
    </span>
    <button type="button" class="btn-vot-print" onclick="window.print();">
        <i class="fa-solid fa-print"></i> Imprimir Cartón Electoral
    </button>
</div>

<!-- Contenido imprimible: el cartón electoral -->
<div class="vot-wrap" id="carton-electoral">

    <!-- ========== CARTÓN REPRESENTANTES ========== -->
    <div class="votacion-banner">
        <div class="votacion-banner-info">
            <h4><i class="fa-solid fa-user-tie"></i> Candidatos a Representante de Aprendices</h4>
            <p>Cartón informativo de la votación para elegir representante.</p>
        </div>
        <div class="votacion-badge-total">
            <i class="fa-solid fa-users"></i> <?= $datRepTar ? count($datRepTar) : 0; ?> Opciones
        </div>
    </div>

    <div class="votacion-grid">
        <?php
        if (!empty($datRepTar)) {
            foreach ($datRepTar as $d) {
                $esBlanco = !empty($d['esblanco']);
                $nombre   = isset($d['nomusu']) ? $d['nomusu'] : 'SIN NOMBRE';
                $numCard  = $esBlanco ? 'BLANCO' : (isset($d['noca']) ? htmlspecialchars($d['noca']) : '???');
                $foto     = isset($d['fotcan']) && !empty($d['fotcan']) ? htmlspecialchars($d['fotcan']) : '';
                $ficha    = isset($d['idfic']) && !empty($d['idfic']) ? htmlspecialchars($d['idfic']) : '';
                $programa = isset($d['nomfic']) && !empty($d['nomfic']) ? htmlspecialchars($d['nomfic']) : '';
                $lema     = isset($d['lema']) && !empty($d['lema']) ? $d['lema'] : ($esBlanco ? 'No hay inclinación por ningún candidato.' : 'Compromiso y liderazgo al servicio de los aprendices.');
                ?>
                <div class="candidato-card<?= $esBlanco ? ' card-blanca' : ''; ?>">

                    <?php if ($esBlanco) { ?>
                        <div class="candidato-foto placeholder">
                            <div class="icono-blanca"><i class="fa-solid fa-ban"></i></div>
                        </div>
                    <?php } elseif ($foto && $foto != 'image/usuario.png') { ?>
                        <div class="candidato-foto">
                            <span class="candidato-num"><?= $numCard; ?></span>
                            <img src="<?= $foto; ?>" alt="<?= htmlspecialchars($nombre); ?>"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="avatar-iniciales" style="display:none;"><?= inicialesNombres($nombre); ?></div>
                        </div>
                    <?php } else { ?>
                        <div class="candidato-foto placeholder">
                            <span class="candidato-num"><?= $numCard; ?></span>
                            <div class="avatar-iniciales"><?= inicialesNombres($nombre); ?></div>
                        </div>
                    <?php } ?>

                    <div class="candidato-info">
                        <div class="candidato-nombre"><?= strtoupper(htmlspecialchars($nombre)); ?></div>
                        <div class="candidato-ficha">
                            <?php if ($esBlanco) { ?>
                                <i class="fa-solid fa-file-circle-minus"></i> Opción democrática oficial
                            <?php } else { ?>
                                <?php if ($ficha) { ?><i class="fa-solid fa-hashtag"></i>Ficha <?= $ficha; ?><?php } ?>
                                <?php if ($programa) { ?>&nbsp;·&nbsp;<i class="fa-solid fa-graduation-cap"></i><?= $programa; ?><?php } ?>
                            <?php } ?>
                        </div>
                        <div class="candidato-lema"><i class="fa-solid fa-quote-left"></i> <?= htmlspecialchars($lema); ?></div>
                        <div class="candidato-info-chip">
                            <i class="fa-solid fa-eye"></i> Consulta informativa
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-warning text-center" style="width:100%;">
                <i class="fa-solid fa-triangle-exclamation"></i> No hay candidatos a representante para mostrar.
            </div>
            <?php
        }
        ?>
    </div>

    <!-- ========== CARTÓN VOCEROS ========== -->
    <?php if (!empty($datVocTar)): ?>
    <div class="vot-section-voceros">
    <div class="vot-separador">
        <span><i class="fa-solid fa-layer-group"></i> Segunda elección</span>
    </div>

    <div class="votacion-banner banner-voceros">
        <div class="votacion-banner-info">
            <h4><i class="fa-solid fa-bullhorn"></i> Candidatos a Vocero de Ficha</h4>
            <p>Cartón informativo de la votación para elegir vocero.</p>
        </div>
        <div class="votacion-badge-total">
            <i class="fa-solid fa-users"></i> <?= count($datVocTar); ?> Opciones
        </div>
    </div>

    <div class="votacion-grid">
        <?php
        foreach ($datVocTar as $v) {
            $esBlanco = !empty($v['esblanco']);
            $nombre   = isset($v['nomusu']) ? $v['nomusu'] : 'SIN NOMBRE';
            $numCard  = $esBlanco ? 'BLANCO' : (isset($v['noca']) ? htmlspecialchars($v['noca']) : '???');
            $foto     = isset($v['fotcan']) && !empty($v['fotcan']) ? htmlspecialchars($v['fotcan']) : '';
            $ficha    = isset($v['idfic']) && !empty($v['idfic']) ? htmlspecialchars($v['idfic']) : '';
            $programa = isset($v['nomfic']) && !empty($v['nomfic']) ? htmlspecialchars($v['nomfic']) : '';
            $lema     = isset($v['lema']) && !empty($v['lema']) ? $v['lema'] : ($esBlanco ? 'No hay inclinación por ningún candidato.' : 'Compromiso y liderazgo al servicio de los aprendices.');
            ?>
            <div class="candidato-card<?= $esBlanco ? ' card-blanca' : ''; ?>">

                <?php if ($esBlanco) { ?>
                    <div class="candidato-foto placeholder">
                        <div class="icono-blanca"><i class="fa-solid fa-ban"></i></div>
                    </div>
                <?php } elseif ($foto && $foto != 'image/usuario.png') { ?>
                    <div class="candidato-foto">
                        <span class="candidato-num"><?= $numCard; ?></span>
                        <img src="<?= $foto; ?>" alt="<?= htmlspecialchars($nombre); ?>"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="avatar-iniciales" style="display:none;"><?= inicialesNombres($nombre); ?></div>
                    </div>
                <?php } else { ?>
                    <div class="candidato-foto placeholder">
                        <span class="candidato-num"><?= $numCard; ?></span>
                        <div class="avatar-iniciales"><?= inicialesNombres($nombre); ?></div>
                    </div>
                <?php } ?>

                <div class="candidato-info">
                    <div class="candidato-nombre"><?= strtoupper(htmlspecialchars($nombre)); ?></div>
                    <div class="candidato-ficha">
                        <?php if ($esBlanco) { ?>
                            <i class="fa-solid fa-file-circle-minus"></i> Opción democrática oficial
                        <?php } else { ?>
                            <?php if ($ficha) { ?><i class="fa-solid fa-hashtag"></i>Ficha <?= $ficha; ?><?php } ?>
                            <?php if ($programa) { ?>&nbsp;·&nbsp;<i class="fa-solid fa-graduation-cap"></i><?= $programa; ?><?php } ?>
                        <?php } ?>
                    </div>
                    <div class="candidato-lema"><i class="fa-solid fa-quote-left"></i> <?= htmlspecialchars($lema); ?></div>
                    <div class="candidato-info-chip">
                        <i class="fa-solid fa-eye"></i> Consulta informativa
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    </div>
    <?php endif; ?>
</div>

<div class="votacion-intro">
    <p class="votacion-sub">
        <i class="fa-solid fa-circle-info"></i>
        Esta es una vista <strong>informativa</strong> del cartón electoral. El ejercicio del voto se realiza
        desde las opciones habilitadas en cada proceso de votación.
    </p>
</div>

<p class="votacion-nota">
    <i class="fa-solid fa-shield-halved"></i>
    El voto es personal, secreto e irreversible.
</p>