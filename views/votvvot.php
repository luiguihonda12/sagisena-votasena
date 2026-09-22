<?php
require_once('controllers/votcvot.php');

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
<?php echo titulo2("<i class='" . (isset($icono) ? $icono : 'fa-check-to-slot') . "'></i> Votación de Representantes", 2); ?>

<style>
    :root {
        --vot-verde: #117f09;
        --vot-verde-claro: #00af00;
        --vot-azul: #123a1f;
        --vot-dorado: #ffc800;
        --vot-sombra: 0 18px 40px rgba(17, 127, 9, .22);
        --vot-border: 18px;
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
        transform: translateY(-8px);
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
        transition: transform .35s ease;
    }
    .candidato-card:hover .candidato-foto img {
        transform: scale(1.06);
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

    .btn-votar-candidato {
        width: 100%;
        border: none;
        border-radius: 10px;
        padding: 12px;
        background: linear-gradient(135deg, var(--vot-verde-claro), var(--vot-verde));
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: .8px;
        text-transform: uppercase;
        box-shadow: 0 4px 12px rgba(17, 127, 9, .28);
        transition: box-shadow .2s ease, transform .2s ease, filter .2s ease;
        cursor: pointer;
    }
    .btn-votar-candidato i {
        margin-right: 6px;
    }
    .btn-votar-candidato:hover {
        filter: brightness(1.07);
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(17, 127, 9, .38);
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
    .btn-votar-blanca {
        width: 100%;
        border: 1px solid #cfd6cf;
        border-radius: 10px;
        padding: 12px;
        background: #fff;
        color: #5b645b;
        font-weight: 700;
        font-size: 12.5px;
        letter-spacing: .8px;
        text-transform: uppercase;
        transition: all .2s ease;
        cursor: pointer;
    }
    .btn-votar-blanca i {
        margin-right: 6px;
    }
    .btn-votar-blanca:hover {
        background: var(--vot-verde);
        border-color: var(--vot-verde);
        color: #fff;
        box-shadow: 0 8px 18px rgba(17, 127, 9, .3);
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

    /* Estilos cuando el aprendiz ya votó */
    .candidato-card.card-votada {
        border: 3px solid #117f09 !important;
        box-shadow: 0 12px 30px rgba(17, 127, 9, 0.35) !important;
        transform: translateY(-4px);
    }
    .candidato-card.card-no-votada {
        opacity: 0.72;
        border-color: #e2e8f0;
    }
    .candidato-card.card-no-votada:hover {
        transform: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
    }
    .badge-voto-emitido {
        background: #117f09;
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 8px;
        box-shadow: 0 2px 6px rgba(17, 127, 9, 0.3);
    }
    .btn-voto-confirmado {
        width: 100%;
        border: none;
        border-radius: 10px;
        padding: 12px;
        background: #117f09;
        color: #fff;
        font-weight: 800;
        font-size: 13px;
        letter-spacing: .5px;
        text-transform: uppercase;
        cursor: default;
    }
    .btn-voto-bloqueado {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 12px;
        background: #f3f4f6;
        color: #9ca3af;
        font-weight: 600;
        font-size: 13px;
        cursor: not-allowed;
    }
</style>

<?php if ($yaVoto): ?>
<div class="votacion-banner" style="background: linear-gradient(135deg, #0a3d1a 0%, #155724 100%); border-left-color: #ffc800;">
    <div class="votacion-banner-info">
        <h4><i class="fa-solid fa-circle-check text-warning"></i> Tu voto ya ha sido registrado</h4>
        <p>Ya has ejercido tu derecho al voto para <strong>Representante de Aprendices</strong>. A continuación puedes ver los candidatos y la opción por la que votaste. <strong>Tu voto es definitivo y no se puede modificar.</strong></p>
    </div>
    <div class="votacion-badge-total">
        <i class="fa-solid fa-shield-halved"></i> Voto Consignado
    </div>
</div>

<div class="votacion-intro">
    <p class="votacion-sub">
        <i class="fa-solid fa-circle-check text-success"></i>
        Tu voto está <strong>registrado y protegido</strong>. Puedes verificar a continuación tu elección:
    </p>
</div>
<?php else: ?>
<div class="votacion-banner">
    <div class="votacion-banner-info">
        <h4><i class="fa-solid fa-vote-yea"></i> Elección de Representante de Aprendices</h4>
        <p>Selecciona la tarjeta de tu candidato de preferencia o elige el <strong>Voto en Blanco</strong>.</p>
    </div>
    <div class="votacion-badge-total">
        <i class="fa-solid fa-users"></i> <?= count($dat); ?> Opciones
    </div>
</div>

<div class="votacion-intro">
    <p class="votacion-sub">
        <i class="fa-solid fa-circle-info"></i>
        Recuerda que tu voto es personal, secreto e <strong>irreversible</strong>.
        Solo podrás votar <strong>una única vez</strong> por tu representante.
    </p>
</div>
<?php endif; ?>

<div class="votacion-grid">
    <?php
    if (!empty($dat)) {
        foreach ($dat as $d) {
            $esBlanco   = !empty($d['esblanco']);
            $nombre     = isset($d['nomusu']) ? $d['nomusu'] : 'SIN NOMBRE';
            $numCard    = $esBlanco ? 'BLANCO' : (isset($d['noca']) ? htmlspecialchars($d['noca']) : '???');
            $ficha      = isset($d['idfic']) && !empty($d['idfic']) ? htmlspecialchars($d['idfic']) : '';
            $programa   = isset($d['nomfic']) && !empty($d['nomfic']) ? htmlspecialchars($d['nomfic']) : '';
            $lema       = isset($d['lema']) && !empty($d['lema']) ? $d['lema'] : ($esBlanco ? 'No hay inclinación por ningún candidato.' : 'Compromiso y liderazgo al servicio de los aprendices.');
            $fueVotado  = ($yaVoto && $candVotadoId && $d['idusu'] == $candVotadoId);

            if ($yaVoto) {
                // Modo visualización de solo lectura tras haber votado
                ?>
                <div class="candidato-card<?= $esBlanco ? ' card-blanca' : ''; ?><?= $fueVotado ? ' card-votada' : ' card-no-votada'; ?>">
                    <?php if ($esBlanco) { ?>
                        <div class="candidato-foto placeholder">
                            <div class="icono-blanca"><i class="fa-solid fa-ban"></i></div>
                        </div>
                    <?php } else { ?>
                        <div class="candidato-foto">
                            <span class="candidato-num"><?= $numCard; ?></span>
                            <img src="img/user.jpg" alt="<?= htmlspecialchars($nombre); ?>"
                                 onerror="this.src='img/user.jpg';">
                        </div>
                    <?php } ?>

                    <div class="candidato-info">
                        <?php if ($fueVotado): ?>
                            <div>
                                <span class="badge-voto-emitido">
                                    <i class="fa-solid fa-circle-check"></i> Tu Voto Registrado
                                </span>
                            </div>
                        <?php endif; ?>

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

                        <?php if ($fueVotado): ?>
                            <button type="button" class="btn-voto-confirmado" disabled>
                                <i class="fa-solid fa-circle-check"></i> Votaste por esta opción
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn-voto-bloqueado" disabled>
                                <i class="fa-solid fa-lock"></i> No seleccionado
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            } else {
                // Modo interactivo para emitir el voto
                ?>
                <form class="candidato-card<?= $esBlanco ? ' card-blanca' : ''; ?>"
                    action="home.php?pg=<?= $pg; ?>" method="POST"
                    onsubmit="return confirmarVotacion(event, this, '<?= strtoupper(addslashes($nombre)); ?>');">

                    <?php if ($esBlanco) { ?>
                        <div class="candidato-foto placeholder">
                            <div class="icono-blanca"><i class="fa-solid fa-ban"></i></div>
                        </div>
                    <?php } else { ?>
                        <div class="candidato-foto">
                            <span class="candidato-num"><?= $numCard; ?></span>
                            <img src="img/user.jpg" alt="<?= htmlspecialchars($nombre); ?>"
                                 onerror="this.src='img/user.jpg';">
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
                        <input type="hidden" name="canusu" value="<?= $d['idusu']; ?>">
                        <input type="hidden" name="opera" value="save">
                        <button type="submit" class="<?= $esBlanco ? 'btn-votar-blanca' : 'btn-votar-candidato'; ?>">
                            <?php if ($esBlanco) { ?>
                                <i class="fa-solid fa-table-cells-large"></i> Votar en blanco
                            <?php } else { ?>
                                <i class="fa-solid fa-square-check"></i> Votar
                            <?php } ?>
                        </button>
                    </div>
                </form>
                <?php
            }
        }
    } else {
        ?>
        <div class="alert alert-warning text-center" style="width:100%;">
            <i class="fa-solid fa-triangle-exclamation"></i> No hay candidatos disponibles para votación.
        </div>
        <?php
    }
    ?>
</div>

<p class="votacion-nota">
    <i class="fa-solid fa-shield-halved"></i>
    Tu voto está protegido y será registrado de forma confidencial.
</p>

<script>
    function confirmarVotacion(ev, form, nombre) {
        ev.preventDefault();

        Swal.fire({
            title: '¿Está seguro?',
            text: "Está a punto de votar por " + nombre,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, votar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#117f09'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false;
    }
</script>