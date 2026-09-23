<?php
require_once('controllers/votcvot.php');
?>
<?php echo titulo2("<i class='" . $icono . "'></i> Votación de Representantes", 2); ?>

<?php if (!empty($_SESSION['votmsg'])):
    $votmsg = $_SESSION['votmsg'];
    unset($_SESSION['votmsg']);
?>
<div class="alert alert-<?= htmlspecialchars($votmsg['tipo']); ?> w-100 mb-3" role="alert">
    <i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($votmsg['texto']); ?>
</div>
<?php endif; ?>

<?php if ($yaVoto): ?>
<div class="votacion-banner banner-votado-rep">
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
                            <img src="img/user.jpg" alt="<?= htmlspecialchars($nombre); ?>">
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
                    action="home.php?pg=<?= $pg; ?>" method="POST">

                    <?php if ($esBlanco) { ?>
                        <div class="candidato-foto placeholder">
                            <div class="icono-blanca"><i class="fa-solid fa-ban"></i></div>
                        </div>
                    <?php } else { ?>
                        <div class="candidato-foto">
                            <span class="candidato-num"><?= $numCard; ?></span>
                            <img src="img/user.jpg" alt="<?= htmlspecialchars($nombre); ?>">
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
        <div class="alert alert-warning text-center w-100">
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
