<?php require_once 'controllers/votcpro.php';
$vis = isset($_REQUEST['vis']) ? $_REQUEST['vis'] : NULL;
$reado = ($vis == "OK");

$vid    = isset($vid) ? $vid : NULL;
$vAct   = ($vid && isset($vid[0]['rutvid']) && $vid[0]['rutvid']) ? $vid[0]['rutvid'] : '';
$vPath  = $vAct ? ('videos/' . $vAct) : '';
$vMime  = $vAct ? vidmime($vAct) : 'video/mp4';
?>
<link rel="stylesheet" href="css/stypro.css">

<div class="conte">
	<?php echo titulo2("<i class='" . $icono . "'></i> Propuesta", 2); ?>
</div>

<div class="pro-wrap">
	<?php if ($dus) { ?>
	<!-- Encabezado del candidato -->
	<div class="pro-head">
		<?php if (!empty($dus[0]['fotcan']) && file_exists($dus[0]['fotcan'])) { ?>
		<img class="pro-photo" src="<?= htmlspecialchars($dus[0]['fotcan']); ?>" alt="Foto del candidato">
		<?php } else { ?>
		<img class="pro-photo" src="img/user.jpg" alt="Foto del candidato">
		<?php } ?>
		<div class="pro-data">
			<div class="pro-noca"><?= txlbl($dus[0]['noca']); ?></div>
			<div class="pro-nom"><?= txlbl($dus[0]['nomusu']); ?></div>
			<div class="pro-meta">
				<span><i class="fa-solid fa-building-columns"></i><?= txlbl($dus[0]['nomcen']); ?></span>
				<?php if ($esEst) { ?>
				<span><i class="fa-solid fa-hashtag"></i>Ficha <?= (int)$dus[0]['idfic']; ?> - <?= txlbl($dus[0]['nomfic']); ?></span>
				<span><i class="fa-solid fa-clock"></i>Jornada: <?= txlbl($dus[0]['nomval']); ?></span>
				<?php } ?>
			</div>
		</div>
	</div>

	<form name="frm1" action="home.php?pg=<?= (int)$pg; ?>" method="POST">
		<?php if ($dvpr) { ?>
		<!-- Sección Propuesta (slogan y estrategias) -->
		<div class="pro-sec">
			<div class="pro-sec-head"><i class="fa-solid fa-file-lines"></i> Propuesta</div>
			<div class="pro-sec-body">
				<?php
				$n = 0;
				foreach ($dvpr as $dv) {
					if (!$dv['parval']) {
						$valor = ($datOne && isset($datOne[$n]['texpro'])) ? $datOne[$n]['texpro'] : '';
						$npro  = ($datOne && isset($datOne[$n]['npro'])) ? (int)$datOne[$n]['npro'] : '';
						?>
						<div class="form-group mb-3">
							<label class="pro-lbl" for="texpro_<?= $n; ?>"><?= txlbl($dv['nomval']); ?></label>
							<textarea name="texpro[]" id="texpro_<?= $n; ?>" class="form-control" rows="3" required <?= $reado ? 'readonly' : ''; ?>><?= htmlspecialchars($valor); ?></textarea>
							<input type="hidden" name="idval[]" value="<?= (int)$dv['idval']; ?>">
							<input type="hidden" name="npro[]" value="<?= $npro; ?>">
						</div>
						<?php
						$n++;
					} else {
						?>
						<div class="pro-subh"><?= txlbl($dv['nomval']); ?></div>
						<?php
						$nr = explode(";", $dv['parval']);
						for ($o = 0; $o < count($nr); $o++) {
							$valor = ($datOne && isset($datOne[$n]['texpro'])) ? $datOne[$n]['texpro'] : '';
							$npro  = ($datOne && isset($datOne[$n]['npro'])) ? (int)$datOne[$n]['npro'] : '';
							?>
							<div class="form-group mb-3">
								
								<label class="pro-lbl" for="texpro_<?= $n; ?>"><?= txlbl($nr[$o]); ?></label>
								<textarea name="texpro[]" id="texpro_<?= $n; ?>" class="form-control" rows="8" required <?= $reado ? 'readonly' : ''; ?>><?= htmlspecialchars($valor); ?></textarea>
								<input type="hidden" name="idval[]" value="<?= (int)$dv['idval']; ?>">
								<input type="hidden" name="npro[]" value="<?= $npro; ?>">
							</div>
							<?php
							$n++;
						}
					}
				}
				?>
			</div>
		</div>

		<!-- Sección Video: debajo de Estrategias -->
		<div class="pro-sec">
			<div class="pro-sec-head"><i class="fa-solid fa-video"></i> Video</div>
			<div class="pro-sec-body">
				<p class="pro-vid-help"><i class="fa-solid fa-circle-info"></i> Sube un video donde describas tu propuesta de manera gráfica (máximo 97Mb &middot; mp4, webm, mov, avi, m4v, ogv).</p>
				<div class="pro-vid-actions">
					<?php if (!$reado) { ?>
					<input type="file" name="vidpro" id="vidpro" accept="video/*" class="d-none" onchange="subirVideo(this);">
					<button type="button" class="btn-pro btn-pro--vid" onclick="document.getElementById('vidpro').click();">
						<i class="fa-solid fa-upload"></i> Subir video
					</button>
					<?php } ?>
					<?php if ($vAct && file_exists($vPath)) { ?>
					<button type="button" class="btn-pro btn-pro--ver" onclick="verVideo();">
						<i class="fa-solid fa-circle-play"></i> Ver video
					</button>
					<span class="pro-vid-name"><i class="fa-solid fa-file-video"></i> <?= htmlspecialchars($vAct); ?></span>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php
		echo dbche("Condiciones", $dcon, $datC, $reado);
		echo dbche("Manifiesto", $dman, $datM, $reado);
		?>

		<?php if ($dvpr || $dcon || $dman) { ?>
		<div class="pro-actions">
			<button type="submit" class="btn-pro" <?= $reado ? 'disabled' : ''; ?>><i class="fa-solid fa-floppy-disk"></i> <?= $datOne ? "Actualizar" : "Registrar"; ?></button>
			<input type="hidden" name="opera" value="Insertar">
			<input type="hidden" name="idusu" value="<?= (int)($dus[0]['idusu']); ?>">
		</div>
		<?php } else { ?>
		<div class="pro-empty"><i class="fa-solid fa-circle-info"></i><br>No hay campos configurados para esta propuesta.</div>
		<?php } ?>
	</form>

	<!-- Modal Ver video de la propuesta -->
	<div class="modal fade" id="vidModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content pro-vid-modal">
				<div class="modal-header cand-mh">
					<div class="cand-hd">
						<div class="cand-hd-name"><i class="fa-solid fa-circle-play"></i> Video de la propuesta</div>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>
				<div class="modal-body pro-vid-body">
					<video id="vidPlayer" controls preload="metadata" class="pro-vid-player">
						<source id="vidSource" src="" type="">
						Su navegador no soporta la visualización de video.
					</video>
				</div>
				<div class="modal-footer cand-mf">
					<button type="button" class="btn-pro" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<?php } else { ?>
		<div class="pro-empty"><i class="fa-solid fa-user-slash"></i><br>No se encontró el candidato solicitado.</div>
	<?php } ?>
</div>

<?php if ($ok) { ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		Swal.fire({
			icon: 'success',
			title: '¡Propuesta guardada!',
			text: 'Los cambios se registraron correctamente.',
			confirmButtonColor: '#117f09'
		});
	});
</script>
<?php } ?>

<?php if ($okVid) { ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		Swal.fire({
			icon: 'success',
			title: '¡Video subido!',
			text: 'El video de tu propuesta se guardó correctamente.',
			confirmButtonColor: '#117f09'
		});
	});
</script>
<?php } ?>

<?php if ($vidErr) { ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		Swal.fire({
			icon: 'warning',
			title: 'Video no guardado',
			text: <?= json_encode($vidErr); ?>,
			confirmButtonColor: '#117f09'
		});
	});
</script>
<?php } ?>

<?php if ($dus) { ?>
<script>
var vidModal = null;

function subirVideo(input) {
	if (!input.files || !input.files.length) return;
	var fd = new FormData();
	fd.append('idusu', <?= (int)($dus[0]['idusu']); ?>);
	fd.append('vidpro', input.files[0]);
	Swal.fire({
		title: 'Subiendo video...',
		text: 'Por favor espera mientras se carga tu video.',
		allowOutsideClick: false,
		didOpen: function () { Swal.showLoading(); }
	});
	$.ajax({
		url: 'home.php?pg=<?= (int)$pg; ?>',
		type: 'POST',
		data: fd,
		processData: false,
		contentType: false,
		success: function () {
			Swal.fire({
				icon: 'success',
				title: '¡Video subido!',
				text: 'El video de tu propuesta se guardó correctamente.',
				confirmButtonColor: '#117f09'
			}).then(function () { location.reload(); });
		},
		error: function () {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'No se pudo subir el video. Inténtalo nuevamente.',
				confirmButtonColor: '#117f09'
			});
		}
	});
}

function verVideo() {
	var s = document.getElementById('vidSource');
	s.src = <?= json_encode($vPath); ?>;
	s.type = <?= json_encode($vMime); ?>;
	var p = document.getElementById('vidPlayer');
	p.load();
	if (!vidModal) vidModal = new bootstrap.Modal(document.getElementById('vidModal'));
	vidModal.show();
	p.play();
}

document.addEventListener('DOMContentLoaded', function () {
	var m = document.getElementById('vidModal');
	if (m) {
		m.addEventListener('hidden.bs.modal', function () {
			var p = document.getElementById('vidPlayer');
			p.pause();
			p.removeAttribute('src');
			p.load();
		});
	}
});
</script>
<?php } ?>