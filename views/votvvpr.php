<?php require_once 'controllers/votcvpr.php'; ?>
<link rel="stylesheet" href="css/stypro.css">

<div class="conte">
	<?php echo titulo2("<i class='" . $icono . "'></i> Visualizar Propuesta", 2); ?>
</div>

<div class="cand-wrap">
	<?php if ($dcand) { ?>
	<div class="cand-grid">
		<?php foreach ($dcand as $c) { ?>
		<div class="cand-card" data-id="<?= (int)$c['idusu']; ?>">
			<div class="cand-photo">
				<?php if (!empty($c['fotcan']) && file_exists($c['fotcan'])) { ?>
				<img src="<?= htmlspecialchars($c['fotcan']); ?>" alt="Foto de <?= txlbl($c['nomusu']); ?>">
				<?php } else { ?>
				<img src="img/user.jpg" alt="Foto de <?= txlbl($c['nomusu']); ?>">
				<?php } ?>
				<?php if ($c['noca'] !== '' && $c['noca'] !== null) { ?>
				<span class="cand-num"><?= txlbl($c['noca']); ?></span>
				<?php } ?>
			</div>
			<div class="cand-body">
				<div class="cand-name"><?= txlbl($c['nomusu']); ?></div>
				<div class="cand-meta">
					<?php if (!empty($c['idfic'])) { ?>
					<span><i class="fa-solid fa-hashtag"></i>Ficha <?= (int)$c['idfic']; ?> - <?= txlbl($c['nomfic']); ?></span>
					<?php } ?>
					<?php if (!empty($c['nomjor'])) { ?>
					<span><i class="fa-solid fa-clock"></i><?= txlbl($c['nomjor']); ?></span>
					<?php } ?>
					<?php if (!empty($c['nomcen'])) { ?>
					<span><i class="fa-solid fa-building-columns"></i><?= txlbl($c['nomcen']); ?></span>
					<?php } ?>
				</div>
			</div>
			<div class="cand-foot">
				<span class="cand-ver"><i class="fa-solid fa-eye"></i> Ver propuesta</span>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php } else { ?>
	<div class="pro-empty">
		<i class="fa-solid fa-file-circle-question"></i><br>
		No hay propuestas registradas en tu jornada.
	</div>
	<?php } ?>
</div>

<!-- Modal con la propuesta del candidato -->
<div class="modal fade" id="candModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable cand-dlg">
		<div class="modal-content cand-modal">
			<div class="modal-header cand-mh">
				<div class="cand-hd">
					<div id="m_foto" class="cand-hd-photo"></div>
					<div class="cand-hd-info">
						<div class="cand-hd-name" id="m_nombre"></div>
						<div class="cand-hd-meta" id="m_meta"></div>
					</div>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
			</div>
			<div class="modal-body cand-mb" id="m_body"></div>
			<div class="modal-footer cand-mf">
				<button type="button" class="btn-pro" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cerrar</button>
			</div>
		</div>
	</div>
</div>

<script>
const CAND = <?= json_encode($candJson, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
const DEF  = <?= json_encode($defJson, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

function eH(t) {
	return String(t === null || t === undefined ? '' : t).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

function nb(t) {
	return eH(t).replace(/\n/g, '<br>');
}

function chkVal(v) {
	const est = (v === 'Si') ? 'is-si' : ((v === 'No') ? 'is-no' : 'is-pend');
	const icn = (v === 'Si') ? 'fa-circle-check' : ((v === 'No') ? 'fa-circle-xmark' : 'fa-minus');
	const txt = v ? v : 'Pendiente';
	return '<span class="fpro-chk-val ' + est + '"><i class="fa-solid ' + icn + '"></i>' + eH(txt) + '</span>';
}

function vidMime(n) {
	const e = String(n || '').split('.').pop().toLowerCase();
	const m = { mp4: 'video/mp4', m4v: 'video/mp4', webm: 'video/webm', mov: 'video/quicktime', qt: 'video/quicktime', avi: 'video/x-msvideo', ogv: 'video/ogg' };
	return m[e] || 'video/mp4';
}

function vidBlock(c) {
	if (!c.videoOk || !c.video) return '';
	return '<div class="fpro-video">' +
		'<span class="fpro-campo-lbl"><i class="fa-solid fa-video"></i> Video de la propuesta</span>' +
		'<span class="pro-vid-fname" title="' + eH(c.video) + '"><i class="fa-solid fa-file-video"></i> ' + eH(c.video) + '</span>' +
		'<div class="pro-vid-frame">' +
		'<video controls preload="metadata" class="pro-vid-player">' +
		'<source src="videos/' + eH(c.video) + '" type="' + vidMime(c.video) + '">' +
		'Su navegador no soporta la visualización de video.' +
		'</video></div></div>';
}

function bloqueChk(titulo, def, vals) {
	if (!def || !def.length) return '';
	let h = '<div class="fpro-sec"><div class="fpro-sec-tit">' + eH(titulo) + '</div><div class="fpro-sec-body">';
	def.forEach(function (f) {
		const v = (vals && vals[f.idval]) ? vals[f.idval] : '';
		h += '<div class="fpro-chk-row"><span class="fpro-campo-lbl">' + eH(f.nomval) + '</span>' + chkVal(v) + '</div>';
	});
	h += '</div></div>';
	return h;
}

function cuerpoPropuesta(c) {
	let h = '';
	const d3 = DEF[3] || [];
	let n = 0;
	d3.forEach(function (f) {
		if (f.subs && f.subs.length) {
			h += '<div class="fpro-sec"><div class="fpro-sec-tit">' + eH(f.nomval) + '</div><div class="fpro-sec-body">';
			f.subs.forEach(function (sub) {
				const v = (c.props3 && c.props3[n]) ? c.props3[n] : '';
				n++;
				h += '<div class="fpro-campo"><span class="fpro-campo-lbl">' + eH(sub) + '</span><span class="fpro-campo-val">' + (v ? nb(v) : '<em>Sin diligenciar</em>') + '</span></div>';
			});
			if (f.idval === 50) h += vidBlock(c);
			h += '</div></div>';
		} else {
			const v = (c.props3 && c.props3[n]) ? c.props3[n] : '';
			n++;
			h += '<div class="fpro-sec"><div class="fpro-sec-tit">' + eH(f.nomval) + '</div><div class="fpro-sec-body"><p class="fpro-val">' + (v ? nb(v) : '<em>Sin diligenciar</em>') + '</p></div></div>';
		}
	});
	h += bloqueChk('Condiciones', DEF[2] || [], c.conds);
	h += bloqueChk('Manifiesto', DEF[4] || [], c.mans);
	return h;
}

let candModal = null;
document.querySelectorAll('.cand-card').forEach(function (card) {
	card.addEventListener('click', function () {
		const id = card.getAttribute('data-id');
		const c = CAND.find(function (x) { return String(x.idusu) === id; });
		if (!c) return;

		let foto = '';
		if (c.fotcan) {
			foto = '<img src="' + eH(c.fotcan) + '" alt="Foto de ' + eH(c.nomusu) + '" onerror="this.onerror=null;this.src=\'img/user.jpg\'">';
		} else {
			foto = '<img src="img/user.jpg" alt="Foto de ' + eH(c.nomusu) + '">';
		}
		document.getElementById('m_foto').innerHTML = foto;

		let meta = '';
		if (c.noca) meta += '<span class="cand-hd-num"><i class="fa-solid fa-star"></i> Candidato # ' + eH(c.noca) + '</span>';
		if (c.idfic) meta += '<span><i class="fa-solid fa-hashtag"></i> Ficha ' + eH(c.idfic) + ' - ' + eH(c.nomfic) + '</span>';
		if (c.nomjor) meta += '<span><i class="fa-solid fa-clock"></i> ' + eH(c.nomjor) + '</span>';
		if (c.nomcen) meta += '<span><i class="fa-solid fa-building-columns"></i> ' + eH(c.nomcen) + '</span>';

		document.getElementById('m_nombre').textContent = c.nomusu;
		document.getElementById('m_meta').innerHTML = meta;
		document.getElementById('m_body').innerHTML = cuerpoPropuesta(c);

		const dlg = document.querySelector('#candModal .modal-dialog');
		if (dlg) {
			if (c.videoOk) dlg.classList.add('cand-dlg--wide');
			else dlg.classList.remove('cand-dlg--wide');
		}

		if (!candModal) candModal = new bootstrap.Modal(document.getElementById('candModal'));
		candModal.show();
	});
});

document.addEventListener('DOMContentLoaded', function () {
	const cm = document.getElementById('candModal');
	if (!cm) return;
	cm.addEventListener('hidden.bs.modal', function () {
		cm.querySelectorAll('video').forEach(function (v) { v.pause(); });
		document.getElementById('m_body').innerHTML = '';
	});
});
</script>