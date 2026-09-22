<?php require_once 'controllers/votcdpe.php'; ?>

<?php
$icono = isset($icono) ? $icono : 'fa fa-solid fa-user';
if (function_exists('titulo2')) {
    echo titulo2("<i class='" . $icono . "'></i> Datos personales", 2);
} else {
    echo "<h2 class='title-page'><i class='" . $icono . "'></i> Datos personales</h2>";
}

// Determinar la foto del perfil o aprendiz
$fotoPerfil = 'img/user.jpg';
if (!empty($datOne) && !empty($datOne[0]['fotcan']) && file_exists($datOne[0]['fotcan'])) {
    $fotoPerfil = $datOne[0]['fotcan'];
} elseif (file_exists('img/user.jpg')) {
    $fotoPerfil = 'img/user.jpg';
} elseif (file_exists('img/user.jpg')) {
    $fotoPerfil = 'img/user.jpg';
}

// Determinar programa de formación y ficha
$programaFormacion = 'No asignado';
if (!empty($datOne)) {
    if (!empty($datOne[0]['nompro']) && $datOne[0]['nompro'] !== 'Sin programa') {
        $programaFormacion = $datOne[0]['nompro'];
    } elseif (!empty($datOne[0]['nomfic'])) {
        $programaFormacion = $datOne[0]['nomfic'];
    }
}
$numeroFicha = (!empty($datOne) && !empty($datOne[0]['idfic'])) ? $datOne[0]['idfic'] : 'Sin ficha';
$jornada = (!empty($datOne) && !empty($datOne[0]['nomval'])) ? $datOne[0]['nomval'] : 'No asignada';
?>

<div class="vdpe-container">
    <!-- 1. Indicador general del estado de votación -->
    <div class="vdpe-status-banner <?= $votacionAbierta ? 'status-open' : 'status-closed' ?>">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid <?= $votacionAbierta ? 'fa-door-open' : 'fa-lock' ?> fa-lg"></i>
                <span class="fw-semibold">
                    <?= $votacionAbierta ? 'Proceso de Votación Habilitado' : 'Proceso de Votación Cerrado' ?>
                </span>
            </div>
            <span class="badge <?= $votacionAbierta ? 'bg-success text-white' : 'bg-danger text-white' ?> px-3 py-2">
                <?= $votacionAbierta ? 'Abierto' : 'Cerrado' ?>
            </span>
        </div>
    </div>

    <!-- 2. Tarjeta de Perfil e Identidad Visual del Aprendiz -->
    <div class="vdpe-profile-card">
        <div class="vdpe-avatar-wrapper">
            <div class="vdpe-avatar-container">
                <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto del Aprendiz" class="vdpe-avatar-img">
                <div class="vdpe-sena-badge" title="SENA">
                    <img src="image/sena.png" alt="SENA" class="vdpe-sena-badge-img">
                </div>
            </div>
            <div class="vdpe-user-header text-center mt-3">
                <h4 class="vdpe-user-name mb-1">
                    <?= (!empty($datOne) && !empty($datOne[0]['nomusu'])) ? htmlspecialchars($datOne[0]['nomusu']) : 'Usuario' ?>
                </h4>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                        <i class="fa-solid fa-id-badge me-1"></i>
                        <?= (!empty($datOne) && !empty($datOne[0]['nomper'])) ? htmlspecialchars($datOne[0]['nomper']) : 'Aprendiz' ?>
                    </span>
                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                        <i class="fa-solid fa-hashtag me-1"></i> Ficha: <?= htmlspecialchars($numeroFicha) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. Información Académica y de la Ficha -->
        <div class="vdpe-info-section mt-4">
            <h5 class="vdpe-section-title">
                <i class="fa-solid fa-graduation-cap text-success me-2"></i> Información Académica y Personal
            </h5>

            <div class="vdpe-grid">
                <div class="vdpe-grid-item">
                    <div class="vdpe-item-icon">
                        <i class="fa-solid fa-address-card"></i>
                    </div>
                    <div class="vdpe-item-content">
                        <span class="vdpe-label">No. Documento</span>
                        <span class="vdpe-value">
                            <?= (!empty($datOne) && !empty($datOne[0]['ndocusu'])) ? htmlspecialchars($datOne[0]['ndocusu']) : '---' ?>
                        </span>
                    </div>
                </div>

                <div class="vdpe-grid-item">
                    <div class="vdpe-item-icon">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div class="vdpe-item-content">
                        <span class="vdpe-label">Centro de Formación</span>
                        <span class="vdpe-value">
                            <?= (!empty($datOne) && !empty($datOne[0]['nomcen'])) ? htmlspecialchars($datOne[0]['nomcen']) : '---' ?>
                        </span>
                    </div>
                </div>

                <div class="vdpe-grid-item">
                    <div class="vdpe-item-icon">
                        <i class="fa-solid fa-hashtag"></i>
                    </div>
                    <div class="vdpe-item-content">
                        <span class="vdpe-label">Número de Ficha</span>
                        <span class="vdpe-value fw-bold text-success">
                            <?= htmlspecialchars($numeroFicha) ?>
                        </span>
                    </div>
                </div>

                <div class="vdpe-grid-item">
                    <div class="vdpe-item-icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div class="vdpe-item-content">
                        <span class="vdpe-label">Programa de Formación</span>
                        <span class="vdpe-value">
                            <?= htmlspecialchars($programaFormacion) ?>
                        </span>
                    </div>
                </div>

                <div class="vdpe-grid-item">
                    <div class="vdpe-item-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="vdpe-item-content">
                        <span class="vdpe-label">Jornada</span>
                        <span class="vdpe-value">
                            <?= htmlspecialchars($jornada) ?>
                        </span>
                    </div>
                </div>

                <div class="vdpe-grid-item">
                    <div class="vdpe-item-icon">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div class="vdpe-item-content">
                        <span class="vdpe-label">Estado de Usuario</span>
                        <span class="vdpe-value">
                            <span class="badge <?= (!empty($datOne) && $datOne[0]['actusu'] == 1) ? 'bg-success' : 'bg-secondary' ?>">
                                <?= (!empty($datOne) && $datOne[0]['actusu'] == 1) ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 1 & 4. Indicadores de Estado de Voto y Botones de Acción -->
        <div class="vdpe-voting-section mt-4">
            <h5 class="vdpe-section-title">
                <i class="fa-solid fa-check-to-slot text-success me-2"></i> Estado de Votaciones
            </h5>

            <div class="row g-3 mt-1">
                <!-- Elección Representante -->
                <div class="col-md-6">
                    <div class="vdpe-vote-card <?= $yaVotoRepresentante ? 'card-voted' : ($votacionAbierta ? 'card-pending' : 'card-disabled') ?>">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="vdpe-vote-icon">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Representante</h6>
                                    <small class="text-muted">Aprendices SENA</small>
                                </div>
                            </div>
                            <?php if ($yaVotoRepresentante): ?>
                                <span class="badge bg-success">
                                    <i class="fa-solid fa-circle-check me-1"></i> Ya votó
                                </span>
                            <?php elseif ($votacionAbierta): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="fa-solid fa-clock me-1"></i> Pendiente
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="fa-solid fa-lock me-1"></i> Cerrada
                                </span>
                            <?php endif; ?>
                        </div>

                        <p class="vdpe-vote-desc small mb-3">
                            <?php if ($yaVotoRepresentante): ?>
                                Su voto para la elección de Representante ha sido registrado exitosamente.
                            <?php elseif ($votacionAbierta): ?>
                                La votación para Representante está abierta. Haga clic en el botón para ejercer su voto.
                            <?php else: ?>
                                El periodo de votación para Representante se encuentra actualmente cerrado.
                            <?php endif; ?>
                        </p>

                        <div class="vdpe-btn-wrap">
                            <?php if (!$yaVotoRepresentante && $votacionAbierta): ?>
                                <a href="home.php?pg=1203" class="btn btn-success w-100 py-2 fw-semibold">
                                    <i class="fa-solid fa-check-to-slot me-1"></i> Votar Representante
                                </a>
                            <?php elseif ($yaVotoRepresentante): ?>
                                <button class="btn btn-outline-success w-100 py-2" disabled>
                                    <i class="fa-solid fa-check-double me-1"></i> Voto Registrado
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100 py-2" disabled>
                                    <i class="fa-solid fa-lock me-1"></i> Votación Cerrada
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Elección Vocero -->
                <div class="col-md-6">
                    <div class="vdpe-vote-card <?= $yaVotoVocero ? 'card-voted' : ($votacionAbierta ? 'card-pending' : 'card-disabled') ?>">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="vdpe-vote-icon">
                                    <i class="fa-solid fa-bullhorn"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Vocero de Ficha</h6>
                                    <small class="text-muted">Ficha <?= htmlspecialchars($numeroFicha) ?></small>
                                </div>
                            </div>
                            <?php if ($yaVotoVocero): ?>
                                <span class="badge bg-success">
                                    <i class="fa-solid fa-circle-check me-1"></i> Ya votó
                                </span>
                            <?php elseif ($votacionAbierta): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="fa-solid fa-clock me-1"></i> Pendiente
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="fa-solid fa-lock me-1"></i> Cerrada
                                </span>
                            <?php endif; ?>
                        </div>

                        <p class="vdpe-vote-desc small mb-3">
                            <?php if ($yaVotoVocero): ?>
                                Su voto para la elección de Vocero de ficha ha sido registrado exitosamente.
                            <?php elseif ($votacionAbierta): ?>
                                La votación para Vocero de ficha está abierta. Haga clic en el botón para ejercer su voto.
                            <?php else: ?>
                                El periodo de votación para Vocero de ficha se encuentra actualmente cerrado.
                            <?php endif; ?>
                        </p>

                        <div class="vdpe-btn-wrap">
                            <?php if (!$yaVotoVocero && $votacionAbierta): ?>
                                <a href="home.php?pg=1202" class="btn btn-success w-100 py-2 fw-semibold">
                                    <i class="fa-solid fa-check-to-slot me-1"></i> Votar Vocero
                                </a>
                            <?php elseif ($yaVotoVocero): ?>
                                <button class="btn btn-outline-success w-100 py-2" disabled>
                                    <i class="fa-solid fa-check-double me-1"></i> Voto Registrado
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100 py-2" disabled>
                                    <i class="fa-solid fa-lock me-1"></i> Votación Cerrada
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.vdpe-container {
    max-width: 920px;
    margin: 0 auto 30px auto;
}

.vdpe-status-banner {
    padding: 12px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    border: 1px solid transparent;
}
.vdpe-status-banner.status-open {
    background-color: #e8f7ec;
    border-color: #c1e7cb;
    color: #0f6828;
}
.vdpe-status-banner.status-closed {
    background-color: #fde8e8;
    border-color: #f8b4b4;
    color: #9b1c1c;
}

.vdpe-profile-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
}

.vdpe-avatar-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.vdpe-avatar-container {
    position: relative;
    width: 120px;
    height: 120px;
}

.vdpe-avatar-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #117f09;
    box-shadow: 0 4px 12px rgba(17, 127, 9, 0.2);
    background-color: #f3f4f6;
}

.vdpe-sena-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 36px;
    height: 36px;
    background: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #117f09;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.vdpe-sena-badge-img {
    width: 22px;
    height: auto;
}

.vdpe-user-name {
    font-weight: 700;
    color: #1f2937;
    font-size: 1.35rem;
}

.vdpe-section-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 8px;
    margin-bottom: 16px;
}

.vdpe-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 12px;
}

.vdpe-grid-item {
    display: flex;
    align-items: center;
    background: #f9fafb;
    padding: 12px 16px;
    border-radius: 10px;
    border: 1px solid #edf2f7;
    transition: all 0.2s ease;
}

.vdpe-grid-item:hover {
    background: #f0fdf4;
    border-color: #bbf7d0;
}

.vdpe-item-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #e8f7ec;
    color: #117f09;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    margin-right: 14px;
    flex-shrink: 0;
}

.vdpe-item-content {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.vdpe-label {
    font-size: 0.78rem;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.vdpe-value {
    font-size: 0.95rem;
    color: #1f2937;
    font-weight: 600;
    white-space: normal;
    word-break: break-word;
}

.vdpe-vote-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 18px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
}

.vdpe-vote-card.card-voted {
    border-color: #bbf7d0;
    background: #f0fdf4;
}

.vdpe-vote-card.card-pending {
    border-color: #fef08a;
    background: #fffbeb;
}

.vdpe-vote-card.card-disabled {
    border-color: #e5e7eb;
    background: #f9fafb;
    opacity: 0.85;
}

.vdpe-vote-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #117f09;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.vdpe-vote-desc {
    color: #4b5563;
    line-height: 1.4;
}

.vdpe-btn-wrap .btn-success {
    background-color: #117f09;
    border-color: #117f09;
}
.vdpe-btn-wrap .btn-success:hover {
    background-color: #0d6307;
    border-color: #0d6307;
}

@media (max-width: 576px) {
    .vdpe-grid {
        grid-template-columns: 1fr;
    }
}
</style>