<?php require_once 'controllers/votcfpro.php';
$fecha = date('d/m/Y');
?>
<link rel="stylesheet" href="css/stypro.css">

<div class="fpro-wrap">
	<!-- Barra de acciones -->
	<div class="fpro-toolbar">
		<button type="button" class="fpro-btn" onclick="window.print();">
			<i class="fa-solid fa-print"></i> Imprimir
		</button>
		<button type="button" class="fpro-btn fpro-btn--pdf" onclick="window.print();">
			<i class="fa-solid fa-file-pdf"></i> Generar PDF
		</button>
	</div>

	<!-- Hoja del documento -->
	<div class="fpro-page">
		<div class="fpro-head">
			<img class="fpro-logo" src="image/sena.png" alt="Logo SENA">
			<div class="fpro-tit">Formato de Propuesta</div>
			<div class="fpro-sub">Elecciones de Representante y Líder de Ficha · <?= $fecha; ?></div>
		</div>

		<div class="fpro-data">
			<div class="fpro-data-row">
				<span class="fpro-data-lbl">Nombre del candidato</span>
				<span class="fpro-data-val"><?= !empty($dus) ? txlbl($dus[0]['nomusu']) : 'No registrado'; ?></span>
			</div>
			<div class="fpro-data-row">
				<span class="fpro-data-lbl">Documento de identidad</span>
				<span class="fpro-data-val"><?= !empty($dus[0]['ndocusu']) ? txlbl($dus[0]['ndocusu']) : '-'; ?></span>
			</div>
			<div class="fpro-data-grid">
				<div class="fpro-data-cell">
					<span class="fpro-data-lbl">Ficha</span>
					<span class="fpro-data-val"><?= $esEst && !empty($dus[0]['idfic']) ? txlbl($dus[0]['idfic'] . ' - ' . $dus[0]['nomfic']) : '-'; ?></span>
				</div>
				<div class="fpro-data-cell">
					<span class="fpro-data-lbl">Centro de formación</span>
					<span class="fpro-data-val"><?= !empty($dus[0]['nomcen']) ? txlbl($dus[0]['nomcen']) : '-'; ?></span>
				</div>
				<div class="fpro-data-cell">
					<span class="fpro-data-lbl">Jornada</span>
					<span class="fpro-data-val"><?= $esEst && !empty($dus[0]['nomval']) ? txlbl($dus[0]['nomval']) : '-'; ?></span>
				</div>
			</div>
		</div>

		<?php if ($dvpr) {
			$n = 0;
			foreach ($dvpr as $dv) {
				if (!$dv['parval']) {
					$valor = ($datOne && isset($datOne[$n]['texpro'])) ? $datOne[$n]['texpro'] : '';
					?>
					<div class="fpro-sec">
						<div class="fpro-sec-tit"><?= txlbl($dv['nomval']); ?></div>
						<div class="fpro-sec-body">
							<p class="fpro-val"><?= nl2br(htmlspecialchars($valor)); ?></p>
						</div>
					</div>
					<?php
					$n++;
				} else {
					?>
					<div class="fpro-sec">
						<div class="fpro-sec-tit"><?= txlbl($dv['nomval']); ?></div>
						<div class="fpro-sec-body">
<?php
						$nr = explode(";", $dv['parval']);
						foreach ($nr as $sub) {
							$valor = ($datOne && isset($datOne[$n]['texpro'])) ? $datOne[$n]['texpro'] : '';
							?>
							<div class="fpro-campo">
								<span class="fpro-campo-lbl"><?= txlbl($sub); ?></span>
								<span class="fpro-campo-val"><?= nl2br(htmlspecialchars($valor)); ?></span>
							</div>
							<?php
							$n++;
						}
						if ((int)$dv['idval'] === 50 && $vidRut && file_exists('videos/' . $vidRut)) {
							echo '<div class="fpro-video">'
								. '<span class="fpro-campo-lbl"><i class="fa-solid fa-video"></i> Video de la propuesta</span>'
								. '<span class="pro-vid-fname" title="' . htmlspecialchars($vidRut) . '"><i class="fa-solid fa-file-video"></i> ' . htmlspecialchars($vidRut) . '</span>'
								. '<div class="pro-vid-frame">'
								. '<video controls preload="metadata" class="pro-vid-player">'
								. '<source src="videos/' . htmlspecialchars($vidRut) . '" type="' . vidmime($vidRut) . '">'
								. 'Su navegador no soporta la visualización de video.'
								. '</video></div></div>';
						}
						?>
						</div>
					</div>
					<?php
				}
			}
		} ?>

		<?php if ($dcon) { ?>
		<div class="fpro-sec">
			<div class="fpro-sec-tit">Condiciones</div>
			<div class="fpro-sec-body">
				<?php
				$i = 0;
				foreach ($dcon as $row) {
					$val = ($datC && isset($datC[$i]['texpro'])) ? $datC[$i]['texpro'] : '';
					$est = ($val == 'Si') ? 'is-si' : (($val == 'No') ? 'is-no' : 'is-pend');
					$icon = ($val == 'Si') ? 'fa-circle-check' : (($val == 'No') ? 'fa-circle-xmark' : 'fa-minus');
					$txt = $val ? $val : 'Pendiente';
					?>
					<div class="fpro-chk-row">
						<span class="fpro-campo-lbl"><?= txlbl($row['nomval']); ?></span>
						<span class="fpro-chk-val <?= $est; ?>"><i class="fa-solid <?= $icon; ?>"></i><?= htmlspecialchars($txt); ?></span>
					</div>
					<?php
					$i++;
				}
				?>
			</div>
		</div>
		<?php } ?>

		<?php if ($dman) { ?>
		<div class="fpro-sec">
			<div class="fpro-sec-tit">Manifiesto</div>
			<div class="fpro-sec-body">
				<?php
				$i = 0;
				foreach ($dman as $row) {
					$val = ($datM && isset($datM[$i]['texpro'])) ? $datM[$i]['texpro'] : '';
					$est = ($val == 'Si') ? 'is-si' : (($val == 'No') ? 'is-no' : 'is-pend');
					$icon = ($val == 'Si') ? 'fa-circle-check' : (($val == 'No') ? 'fa-circle-xmark' : 'fa-minus');
					$txt = $val ? $val : 'Pendiente';
					?>
					<div class="fpro-chk-row">
						<span class="fpro-campo-lbl"><?= txlbl($row['nomval']); ?></span>
						<span class="fpro-chk-val <?= $est; ?>"><i class="fa-solid <?= $icon; ?>"></i><?= htmlspecialchars($txt); ?></span>
					</div>
					<?php
					$i++;
				}
				?>
			</div>
		</div>
		<?php } ?>

		<div class="fpro-firmas">
			<div class="fpro-firma fpro-firma--cand">
				<span class="fpro-firma-txt">Firma del candidato</span>
			</div>
			<div class="fpro-firma fpro-firma--resp">
				<span class="fpro-firma-txt">Firma del responsable</span>
			</div>
		</div>
	</div>
</div>