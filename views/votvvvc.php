<?php
require_once('controllers/votcvvc.php');

echo titulo2("<i class='fa-solid fa-check-to-slot'></i> Votación de Voceros de Ficha", 2);
?>

<?php if (!empty($_SESSION['votmsg'])):
    $votmsg = $_SESSION['votmsg'];
    unset($_SESSION['votmsg']);
?>
<div class="alert alert-<?= htmlspecialchars($votmsg['tipo']); ?> w-100 mb-3" role="alert">
    <i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($votmsg['texto']); ?>
</div>
<?php endif; ?>

<div class="votacion-container">

    <?php if ($yaVoto): ?>
        <!-- Banner informativo institucional para voto emitido -->
        <div class="votacion-banner">
            <div class="votacion-banner-info">
                <h4><i class="fa-solid fa-circle-check text-success"></i> Tu voto ya ha sido registrado</h4>
                <p>Ya has ejercido tu derecho al voto para <strong>Vocero de Ficha</strong>. A continuación puedes ver los candidatos y la opción por la que votaste. <strong>Tu voto es definitivo y no se puede modificar.</strong></p>
            </div>
            <div class="votacion-badge-total">
                <i class="fa-solid fa-shield-halved"></i> Voto Consignado
            </div>
        </div>

        <!-- Cuadrícula de Candidatos en modo solo lectura -->
        <div class="grid-candidatos">
            <?php foreach ($dat as $i => $d): ?>
                <?php 
                    $candId = htmlspecialchars($d['idusu']);
                    $isBlanco = !empty($d['is_blanco']) && $d['is_blanco'] === true;
                    $candNum = $isBlanco ? 'BLANCO' : (!empty($d['noca']) ? str_pad($d['noca'], 2, "0", STR_PAD_LEFT) : str_pad(($i + 1), 2, "0", STR_PAD_LEFT));
                    $candNom = htmlspecialchars($d['nomusu']);
                    $candFic = !empty($d['idfic']) ? htmlspecialchars($d['idfic']) : '';
                    $candNomFic = !empty($d['nomfic']) ? htmlspecialchars($d['nomfic']) : 'Formación Titulada';
                    $candLema = !empty($d['lema']) ? htmlspecialchars($d['lema']) : 'Compromiso y liderazgo con la comunidad aprendiz.';
                    $fueVotado = ($candVotadoId && $candId == $candVotadoId);
                ?>

                <!-- Tarjeta de Solo Lectura -->
                <div class="tarjeta-candidato <?= $isBlanco ? 'tarjeta-blanco' : ''; ?><?= $fueVotado ? ' tarjeta-votada' : ' tarjeta-bloqueada'; ?>" id="tarjeta_<?= $i; ?>">
                    
                    <!-- Encabezado del Tarjetón -->
                    <div class="tarjeton-header">
                        <div class="tarjeton-numero">
                            <span class="num-badge"><?= $isBlanco ? 'VOTO' : 'N° ' . $candNum; ?></span>
                            <span><?= $isBlanco ? 'EN BLANCO' : 'TARJETÓN'; ?></span>
                        </div>
                        <div class="badge-seleccion <?= $fueVotado ? 'badge-votado' : 'badge-bloqueado'; ?>">
                            <i class="fa-solid <?= $fueVotado ? 'fa-circle-check' : 'fa-lock'; ?>"></i>
                            <span><?= $fueVotado ? 'Tu Voto' : 'No seleccionado'; ?></span>
                        </div>
                    </div>

                    <!-- Contenido Central -->
                    <div class="tarjeta-body">
                        <!-- Foto con Marco Institucional o Icono Voto en Blanco -->
                        <div class="foto-wrapper">
                            <?php if ($isBlanco): ?>
                                <div class="foto-blanco-icon">
                                    <i class="fa-solid fa-box-archive"></i>
                                </div>
                            <?php else: ?>
                                <img src="img/user.jpg" alt="<?= $candNom; ?>" class="foto-candidato">
                            <?php endif; ?>
                        </div>

                        <!-- Nombre del Candidato -->
                        <h3 class="nombre-candidato"><?= strtoupper($candNom); ?></h3>

                        <!-- Ficha y Programa -->
                        <?php if (!empty($candFic)): ?>
                            <div class="ficha-badge">
                                <i class="fa-solid fa-hashtag"></i> Ficha: <?= $candFic; ?>
                            </div>
                        <?php endif; ?>

                        <div class="programa-texto">
                            <i class="fa-solid fa-<?= $isBlanco ? 'scale-balanced' : 'graduation-cap'; ?>"></i> <?= $candNomFic; ?>
                        </div>

                        <!-- Lema de Campaña -->
                        <div class="lema-box">
                            <i class="fa-solid fa-quote-left"></i> <?= $candLema; ?>
                        </div>
                    </div>

                    <!-- Pie de Tarjeta -->
                    <div class="tarjeta-footer">
                        <button type="button" class="btn-seleccionar <?= $fueVotado ? 'btn-votado' : 'btn-bloqueado'; ?>" disabled>
                            <i class="fa-solid <?= $fueVotado ? 'fa-circle-check' : 'fa-lock'; ?>"></i> 
                            <?= $fueVotado ? 'Votaste por esta opción' : 'No seleccionado'; ?>
                        </button>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <!-- Banner informativo institucional -->
        <div class="votacion-banner">
            <div class="votacion-banner-info">
                <h4><i class="fa-solid fa-vote-yea"></i> Elección de Voceros de Ficha</h4>
                <p>Selecciona la tarjeta de tu candidato de preferencia o Voto en Blanco y haz clic en <strong>Confirmar Mi Voto</strong>.</p>
            </div>
            <div class="votacion-badge-total">
                <i class="fa-solid fa-users"></i> <?= count($dat); ?> Opciones Disponibles
            </div>
        </div>

        <!-- Formulario de Votación -->
        <form id="frmVotacion" name="frmVotacion" class="vot-form" action="home.php?pg=<?= htmlspecialchars($pg); ?>" method="POST">
            <input type="hidden" name="opera" value="save">
            
            <!-- Cuadrícula de Candidatos y Voto en Blanco -->
            <div class="grid-candidatos">
                <?php foreach ($dat as $i => $d): ?>
                    <?php 
                        $candId = htmlspecialchars($d['idusu']);
                        $isBlanco = !empty($d['is_blanco']) && $d['is_blanco'] === true;
                        $candNum = $isBlanco ? 'BLANCO' : (!empty($d['noca']) ? str_pad($d['noca'], 2, "0", STR_PAD_LEFT) : str_pad(($i + 1), 2, "0", STR_PAD_LEFT));
                        $candNom = htmlspecialchars($d['nomusu']);
                        $candFic = !empty($d['idfic']) ? htmlspecialchars($d['idfic']) : '';
                        $candNomFic = !empty($d['nomfic']) ? htmlspecialchars($d['nomfic']) : 'Formación Titulada';
                        $candLema = !empty($d['lema']) ? htmlspecialchars($d['lema']) : 'Compromiso y liderazgo con la comunidad aprendiz.';
                    ?>

                    <!-- Opción seleccionable: el label envuelve el radio y la tarjeta (sin JavaScript) -->
                    <label class="opcion-voto" for="radio_cand_<?= $i; ?>">
                    <input type="radio"
                           name="canusu"
                           id="radio_cand_<?= $i; ?>"
                           value="<?= $candId; ?>"
                           class="voto-radio"
                           required>

                    <!-- Tarjeta Interactiva -->
                    <div class="tarjeta-candidato <?= $isBlanco ? 'tarjeta-blanco' : ''; ?>" id="tarjeta_<?= $i; ?>">
                        
                        <!-- Encabezado del Tarjetón -->
                        <div class="tarjeton-header">
                            <div class="tarjeton-numero">
                                <span class="num-badge"><?= $isBlanco ? 'VOTO' : 'N° ' . $candNum; ?></span>
                                <span><?= $isBlanco ? 'EN BLANCO' : 'TARJETÓN'; ?></span>
                            </div>
                            <div class="badge-seleccion">
                                <i class="fa-regular fa-circle sel-no"></i>
                                <i class="fa-solid fa-circle-check sel-si"></i>
                                <span class="sel-no">Seleccionar</span>
                                <span class="sel-si">Seleccionado</span>
                            </div>
                        </div>

                        <!-- Contenido Central -->
                        <div class="tarjeta-body">
                            <!-- Foto con Marco Institucional o Icono Voto en Blanco -->
                            <div class="foto-wrapper">
                                <?php if ($isBlanco): ?>
                                    <div class="foto-blanco-icon">
                                        <i class="fa-solid fa-box-archive"></i>
                                    </div>
                                <?php else: ?>
                                    <img src="img/user.jpg" alt="<?= $candNom; ?>" class="foto-candidato">
                                <?php endif; ?>
                            </div>

                            <!-- Nombre del Candidato -->
                            <h3 class="nombre-candidato"><?= strtoupper($candNom); ?></h3>

                            <!-- Ficha y Programa -->
                            <?php if (!empty($candFic)): ?>
                                <div class="ficha-badge">
                                    <i class="fa-solid fa-hashtag"></i> Ficha: <?= $candFic; ?>
                                </div>
                            <?php endif; ?>

                            <div class="programa-texto">
                                <i class="fa-solid fa-<?= $isBlanco ? 'scale-balanced' : 'graduation-cap'; ?>"></i> <?= $candNomFic; ?>
                            </div>

                            <!-- Lema de Campaña -->
                            <div class="lema-box">
                                <i class="fa-solid fa-quote-left"></i> <?= $candLema; ?>
                            </div>
                        </div>

                        <!-- Pie de Tarjeta -->
                        <div class="tarjeta-footer">
                            <span class="btn-seleccionar">
                                <i class="fa-solid fa-check sel-no"></i>
                                <i class="fa-solid fa-check-double sel-si"></i>
                                <span class="sel-no"><?= $isBlanco ? 'Votar en Blanco' : 'Elegir Candidato'; ?></span>
                                <span class="sel-si"><?= $isBlanco ? 'Voto en Blanco Seleccionado' : 'Candidato Seleccionado'; ?></span>
                            </span>
                        </div>
                    </div>
                    </label>

                <?php endforeach; ?>
            </div>

            <!-- Barra Inferior de Acción y Confirmación -->
            <div class="votacion-action-bar">
                <div class="action-bar-info">
                    <div class="icon-circulo">
                        <i class="fa-solid fa-hand-pointer"></i>
                    </div>
                    <div class="action-bar-texto">
                        <span>Opción Seleccionada:</span>
                        <strong class="bar-sin-sel">Ninguna opción seleccionada</strong>
                        <strong class="bar-con-sel">Opción seleccionada, revisa el tarjetón resaltado</strong>
                    </div>
                </div>

                <button type="submit" class="btn-confirmar-voto">
                    <i class="fa-solid fa-envelope-open-text"></i> Confirmar Mi Voto
                </button>
            </div>

        </form>

    <?php endif; ?>
</div>
