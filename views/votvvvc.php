<?php
require_once('controllers/votcvvc.php');

if (!function_exists('titulo2')) {
    function titulo2($titulo = "Votación de Voceros de Ficha", $nivel = 2) {
        return "<div class='text-center my-3'><h2 style='color: #00324D; font-weight: 700;'><i class='fa-solid fa-check-to-slot text-success'></i> {$titulo}</h2></div>";
    }
}

echo titulo2("<i class='fa-solid fa-check-to-slot'></i> Votación de Voceros de Ficha", 2);
?>

<style>
    :root {
        --sena-green: #39A900;
        --sena-green-dark: #007a33;
        --sena-green-light: #e8f5e9;
        --sena-blue: #00324D;
        --sena-blue-light: #f0f7fa;
        --sena-gold: #ffc800;
        --card-border: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-bg-white: #ffffff;
    }

    .votacion-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 10px 15px 50px 15px;
    }

    /* Banner informativo */
    .votacion-banner {
        background: linear-gradient(135deg, var(--sena-blue) 0%, #034b73 100%);
        border-radius: 14px;
        padding: 20px 26px;
        color: #ffffff;
        margin-bottom: 30px;
        box-shadow: 0 6px 20px rgba(0, 50, 77, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        border-left: 6px solid var(--sena-green);
    }

    .votacion-banner-info h4 {
        margin: 0 0 6px 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .votacion-banner-info p {
        margin: 0;
        font-size: 1.05rem;
        color: #d8eefe;
    }

    .votacion-badge-total {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 25px;
        padding: 8px 18px;
        font-size: 1.05rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* Cuadrícula de tarjetas de votación */
    .grid-candidatos {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 35px;
    }

    /* Tarjeta de candidato / opción */
    .tarjeta-candidato {
        background: var(--card-bg-white);
        border: 2px solid var(--card-border);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        position: relative;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .tarjeta-candidato:hover {
        transform: translateY(-6px);
        border-color: var(--sena-green);
        box-shadow: 0 14px 30px rgba(57, 169, 0, 0.18);
    }

    /* Estado seleccionado */
    .tarjeta-candidato.seleccionado {
        border-color: var(--sena-green);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(57, 169, 0, 0.35), 0 16px 32px rgba(0, 50, 77, 0.18);
        transform: translateY(-4px);
    }

    /* Estilo especial para Voto en Blanco */
    .tarjeta-candidato.tarjeta-blanco {
        border-color: #cbd5e1;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }

    .tarjeta-candidato.tarjeta-blanco:hover {
        border-color: #0284c7;
        box-shadow: 0 14px 30px rgba(2, 132, 199, 0.18);
    }

    .tarjeta-candidato.tarjeta-blanco.seleccionado {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.35), 0 16px 32px rgba(2, 132, 199, 0.2);
    }

    /* Encabezado del tarjetón */
    .tarjeton-header {
        background: linear-gradient(90deg, #f8fafc 0%, #edf2f7 100%);
        padding: 12px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--card-border);
        transition: background 0.3s;
    }

    .tarjeta-candidato.seleccionado .tarjeton-header {
        background: linear-gradient(90deg, var(--sena-green-light) 0%, #d4edda 100%);
    }

    .tarjeta-candidato.tarjeta-blanco.seleccionado .tarjeton-header {
        background: linear-gradient(90deg, #e0f2fe 0%, #bae6fd 100%);
    }

    .tarjeton-numero {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--sena-blue);
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tarjeton-numero .num-badge {
        background: var(--sena-green);
        color: #ffffff;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 1.05rem;
        box-shadow: 0 2px 5px rgba(57, 169, 0, 0.3);
    }

    .tarjeta-blanco .tarjeton-numero .num-badge {
        background: #0284c7;
        box-shadow: 0 2px 5px rgba(2, 132, 199, 0.3);
    }

    .badge-seleccion {
        font-size: 0.95rem;
        font-weight: 700;
        border-radius: 20px;
        padding: 5px 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        background: #f1f5f9;
        transition: all 0.3s;
    }

    .tarjeta-candidato.seleccionado .badge-seleccion {
        background: var(--sena-green);
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(57, 169, 0, 0.3);
    }

    .tarjeta-candidato.tarjeta-blanco.seleccionado .badge-seleccion {
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.3);
    }

    /* Cuerpo de la tarjeta */
    .tarjeta-body {
        padding: 22px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-grow: 1;
        text-align: center;
    }

    /* Contenedor de la foto */
    .foto-wrapper {
        position: relative;
        width: 130px;
        height: 130px;
        margin: 5px auto 16px auto;
        border-radius: 50%;
        padding: 4px;
        background: linear-gradient(135deg, var(--sena-green) 0%, var(--sena-blue) 100%);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        transition: transform 0.3s;
    }

    .tarjeta-blanco .foto-wrapper {
        background: linear-gradient(135deg, #0284c7 0%, #64748b 100%);
    }

    .tarjeta-candidato:hover .foto-wrapper {
        transform: scale(1.05);
    }

    .foto-candidato {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        background-color: #ffffff;
        border: 3px solid #ffffff;
    }

    .foto-blanco-icon {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #f8fafc;
        border: 3px solid #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #64748b;
    }

    /* Datos del candidato */
    .nombre-candidato {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--sena-blue);
        margin: 0 0 8px 0;
        line-height: 1.3;
    }

    .ficha-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--sena-blue-light);
        color: var(--sena-blue);
        border: 1px solid #c7e1ee;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .programa-texto {
        font-size: 0.95rem;
        color: var(--text-muted);
        margin-bottom: 12px;
        line-height: 1.4;
        font-weight: 500;
    }

    /* Lema */
    .lema-box {
        background: #f8fafc;
        border-left: 4px solid var(--sena-green);
        border-radius: 0 8px 8px 0;
        padding: 10px 14px;
        font-size: 0.92rem;
        color: #334155;
        font-style: italic;
        margin-bottom: 16px;
        width: 100%;
        text-align: left;
    }

    .tarjeta-blanco .lema-box {
        border-left-color: #0284c7;
    }

    .lema-box i {
        color: var(--sena-green);
        margin-right: 4px;
    }

    .tarjeta-blanco .lema-box i {
        color: #0284c7;
    }

    /* Estilos cuando el aprendiz ya votó */
    .tarjeta-candidato.tarjeta-votada {
        border-color: var(--sena-green) !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(57, 169, 0, 0.4), 0 16px 32px rgba(0, 50, 77, 0.2) !important;
        transform: translateY(-4px) !important;
    }
    .tarjeta-candidato.tarjeta-bloqueada {
        opacity: 0.72;
        cursor: default !important;
    }
    .tarjeta-candidato.tarjeta-bloqueada:hover {
        transform: none !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        border-color: var(--card-border) !important;
    }
    .badge-seleccion.badge-votado {
        background: var(--sena-green) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(57, 169, 0, 0.3) !important;
    }
    .badge-seleccion.badge-bloqueado {
        background: #e2e8f0 !important;
        color: #94a3b8 !important;
    }
    .btn-seleccionar.btn-votado {
        background: var(--sena-green) !important;
        color: #ffffff !important;
        border-color: var(--sena-green) !important;
        cursor: default !important;
    }
    .btn-seleccionar.btn-bloqueado {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #cbd5e1 !important;
        cursor: not-allowed !important;
    }

    /* Pie de tarjeta con botón seleccionar */
    .tarjeta-footer {
        padding: 14px 20px;
        border-top: 1px solid var(--card-border);
        background: #ffffff;
    }

    .btn-seleccionar {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        font-size: 1.05rem;
        font-weight: 700;
        border: 2px solid var(--sena-green);
        background: transparent;
        color: var(--sena-green-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .tarjeta-blanco .btn-seleccionar {
        border-color: #0284c7;
        color: #0284c7;
    }

    .tarjeta-candidato:hover .btn-seleccionar {
        background: var(--sena-green);
        color: #ffffff;
    }

    .tarjeta-blanco:hover .btn-seleccionar {
        background: #0284c7;
        color: #ffffff;
    }

    .tarjeta-candidato.seleccionado .btn-seleccionar {
        background: var(--sena-green);
        color: #ffffff;
        border-color: var(--sena-green);
        box-shadow: 0 2px 8px rgba(57, 169, 0, 0.35);
    }

    .tarjeta-candidato.tarjeta-blanco.seleccionado .btn-seleccionar {
        background: #0284c7;
        color: #ffffff;
        border-color: #0284c7;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.35);
    }

    /* Barra de acción inferior flotante */
    .votacion-action-bar {
        position: sticky;
        bottom: 20px;
        background: #ffffff;
        border: 2px solid var(--sena-green);
        border-radius: 14px;
        padding: 16px 24px;
        box-shadow: 0 10px 30px rgba(0, 50, 77, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        z-index: 99;
    }

    .action-bar-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .action-bar-info .icon-circulo {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--sena-green-light);
        color: var(--sena-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .action-bar-texto {
        font-size: 0.95rem;
        color: var(--text-dark);
    }

    .action-bar-texto strong {
        font-size: 1.15rem;
        color: var(--sena-blue);
        display: block;
    }

    .btn-confirmar-voto {
        background: linear-gradient(135deg, var(--sena-green) 0%, var(--sena-green-dark) 100%);
        color: #ffffff;
        border: none;
        padding: 12px 28px;
        font-size: 1.15rem;
        font-weight: 800;
        border-radius: 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(57, 169, 0, 0.4);
        transition: all 0.25s;
    }

    .btn-confirmar-voto:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(57, 169, 0, 0.5);
    }

    /* Estado cuando ya votó */
    .banner-ya-voto {
        background: #ffffff;
        border: 2px solid var(--sena-green);
        border-radius: 16px;
        padding: 35px 25px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0, 50, 77, 0.08);
        max-width: 650px;
        margin: 30px auto;
    }

    .banner-ya-voto .icon-exito {
        width: 80px;
        height: 80px;
        background: var(--sena-green-light);
        color: var(--sena-green);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.8rem;
        margin-bottom: 20px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .grid-candidatos {
            grid-template-columns: 1fr;
        }

        .votacion-action-bar {
            flex-direction: column;
            text-align: center;
        }

        .action-bar-info {
            flex-direction: column;
        }

        .btn-confirmar-voto {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="votacion-container">

    <?php if ($yaVoto): ?>
        <!-- Banner informativo institucional para voto emitido -->
        <div class="votacion-banner" style="background: linear-gradient(135deg, var(--sena-blue) 0%, #034b73 100%); border-left-color: var(--sena-green);">
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
                                <img src="img/user.jpg" alt="<?= $candNom; ?>" class="foto-candidato" onerror="this.src='img/user.jpg';">
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
        <form id="frmVotacion" name="frmVotacion" action="home.php?pg=<?= htmlspecialchars($pg); ?>" method="POST">
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

                    <!-- Radio Button Oculto -->
                    <input type="radio" 
                           name="canusu" 
                           id="radio_cand_<?= $i; ?>" 
                           value="<?= $candId; ?>" 
                           class="d-none" 
                           required
                           data-nombre="<?= $candNom; ?>"
                           data-numero="<?= $candNum; ?>"
                           data-foto="img/user.jpg"
                           data-blanco="<?= $isBlanco ? '1' : '0'; ?>"
                           onchange="actualizarSeleccion(<?= $i; ?>)">

                    <!-- Tarjeta Interactiva -->
                    <div class="tarjeta-candidato <?= $isBlanco ? 'tarjeta-blanco' : ''; ?>" id="tarjeta_<?= $i; ?>" onclick="seleccionarCandidato(<?= $i; ?>)">
                        
                        <!-- Encabezado del Tarjetón -->
                        <div class="tarjeton-header">
                            <div class="tarjeton-numero">
                                <span class="num-badge"><?= $isBlanco ? 'VOTO' : 'N° ' . $candNum; ?></span>
                                <span><?= $isBlanco ? 'EN BLANCO' : 'TARJETÓN'; ?></span>
                            </div>
                            <div class="badge-seleccion" id="badge_sel_<?= $i; ?>">
                                <i class="fa-regular fa-circle" id="icon_sel_<?= $i; ?>"></i>
                                <span id="txt_sel_<?= $i; ?>">Seleccionar</span>
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
                                    <img src="img/user.jpg" alt="<?= $candNom; ?>" class="foto-candidato" onerror="this.src='img/user.jpg';">
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
                            <button type="button" class="btn-seleccionar" id="btn_card_<?= $i; ?>">
                                <i class="fa-solid fa-check"></i> <?= $isBlanco ? 'Votar en Blanco' : 'Elegir Candidato'; ?>
                            </button>
                        </div>
                    </div>

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
                        <strong id="labelCandidatoSeleccionado">Ninguna opción seleccionada</strong>
                    </div>
                </div>

                <button type="button" class="btn-confirmar-voto" id="btnConfirmarVoto" onclick="validarYConfirmarVoto()">
                    <i class="fa-solid fa-envelope-open-text"></i> Confirmar Mi Voto
                </button>
            </div>

        </form>

    <?php endif; ?>
</div>

<script>
    // Función para seleccionar la tarjeta de un candidato o voto en blanco
    function seleccionarCandidato(indice) {
        const radio = document.getElementById('radio_cand_' + indice);
        if (radio) {
            radio.checked = true;
            actualizarSeleccion(indice);
        }
    }

    // Actualiza la interfaz visual reflejando la opción seleccionada
    function actualizarSeleccion(indiceSeleccionado) {
        const total = <?= count($dat); ?>;

        for (let i = 0; i < total; i++) {
            const tarjeta = document.getElementById('tarjeta_' + i);
            const badge = document.getElementById('badge_sel_' + i);
            const icon = document.getElementById('icon_sel_' + i);
            const txt = document.getElementById('txt_sel_' + i);
            const btnCard = document.getElementById('btn_card_' + i);
            const radio = document.getElementById('radio_cand_' + i);
            const isBlanco = radio ? radio.getAttribute('data-blanco') === '1' : false;

            if (tarjeta && badge && icon && txt && btnCard) {
                if (i === indiceSeleccionado) {
                    tarjeta.classList.add('seleccionado');
                    badge.classList.add('active');
                    icon.className = 'fa-solid fa-circle-check';
                    txt.textContent = 'Seleccionado';
                    btnCard.innerHTML = '<i class="fa-solid fa-check-double"></i> ' + (isBlanco ? 'Voto en Blanco Seleccionado' : 'Candidato Seleccionado');
                } else {
                    tarjeta.classList.remove('seleccionado');
                    badge.classList.remove('active');
                    icon.className = 'fa-regular fa-circle';
                    txt.textContent = 'Seleccionar';
                    btnCard.innerHTML = '<i class="fa-solid fa-check"></i> ' + (isBlanco ? 'Votar en Blanco' : 'Elegir Candidato');
                }
            }
        }

        // Actualizar barra de acción
        const radioSeleccionado = document.getElementById('radio_cand_' + indiceSeleccionado);
        if (radioSeleccionado) {
            const nombre = radioSeleccionado.getAttribute('data-nombre');
            const numero = radioSeleccionado.getAttribute('data-numero');
            const isBlanco = radioSeleccionado.getAttribute('data-blanco') === '1';

            if (isBlanco) {
                document.getElementById('labelCandidatoSeleccionado').innerHTML = 
                    `<span style="color: #0284c7; font-weight: 800;">[OPCIÓN OFICIAL]</span> - ${nombre}`;
            } else {
                document.getElementById('labelCandidatoSeleccionado').innerHTML = 
                    `<span style="color: var(--sena-green); font-weight: 800;">N° ${numero}</span> - ${nombre}`;
            }
        }
    }

    // Validación y confirmación del voto con SweetAlert2
    function validarYConfirmarVoto() {
        const seleccionado = document.querySelector('input[name="canusu"]:checked');

        if (!seleccionado) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'SELECCIÓN REQUERIDA',
                    text: 'Por favor, haz clic sobre la tarjeta de un candidato o Voto en Blanco antes de confirmar.',
                    confirmButtonColor: '#00324D',
                    confirmButtonText: 'Entendido'
                });
            } else {
                alert('Por favor, selecciona una opción antes de continuar.');
            }
            return;
        }

        const nombre = seleccionado.getAttribute('data-nombre');
        const numero = seleccionado.getAttribute('data-numero');
        const isBlanco = seleccionado.getAttribute('data-blanco') === '1';

        const modalHtml = isBlanco ? `
            <div style="text-align: center; margin-top: 10px;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: #e0f2fe; color: #0284c7; font-size: 2.4rem; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; border: 3px solid #0284c7;">
                    <i class="fa-solid fa-box-archive"></i>
                </div>
                <p style="font-size: 1.1rem; color: #1e293b; margin: 0 0 6px 0;">
                    Estás a punto de registrar tu voto por:
                </p>
                <h3 style="font-size: 1.4rem; color: #00324D; font-weight: 800; margin: 0 0 8px 0;">
                    ${nombre}
                </h3>
                <div style="display: inline-block; background: #0284c7; color: #ffffff; padding: 4px 14px; border-radius: 20px; font-weight: 700; font-size: 0.95rem; margin-bottom: 14px;">
                    OPCIÓN INSTITUCIONAL
                </div>
                <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 8px; font-size: 0.9rem; border-left: 4px solid #ffeeba;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <strong>Aviso:</strong> Tu voto es personal, secreto e irreversible una vez confirmado.
                </div>
            </div>
        ` : `
            <div style="text-align: center; margin-top: 10px;">
                <img src="img/user.jpg" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #39A900; margin-bottom: 12px;" onerror="this.src='img/user.jpg';">
                <p style="font-size: 1.1rem; color: #1e293b; margin: 0 0 6px 0;">
                    Estás a punto de emitir tu voto por:
                </p>
                <h3 style="font-size: 1.4rem; color: #00324D; font-weight: 800; margin: 0 0 8px 0;">
                    ${nombre}
                </h3>
                <div style="display: inline-block; background: #39A900; color: #ffffff; padding: 4px 14px; border-radius: 20px; font-weight: 700; font-size: 0.95rem; margin-bottom: 14px;">
                    TARJETÓN N° ${numero}
                </div>
                <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 8px; font-size: 0.9rem; border-left: 4px solid #ffeeba;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <strong>Aviso:</strong> Tu voto es personal, secreto e irreversible una vez confirmado.
                </div>
            </div>
        `;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿CONFIRMAR TU VOTO?',
                html: modalHtml,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-check"></i> Sí, confirmar mi voto',
                cancelButtonText: '<i class="fa-solid fa-xmark"></i> Cancelar',
                confirmButtonColor: '#39A900',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                focusConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Registrando voto...',
                        text: 'Por favor espera un momento.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('frmVotacion').submit();
                }
            });
        } else {
            if (confirm("¿Está seguro de registrar su voto por " + nombre + "?")) {
                document.getElementById('frmVotacion').submit();
            }
        }
    }
</script>