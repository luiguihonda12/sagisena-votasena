<?php require_once 'controllers/votcnvv.php'; ?>

<?php echo titulo2("<i class='fas fa-users'></i> No votantes vocero", 2); ?>

<!-- Dashboard: Aprendices que no han votado (global) -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3 p-md-4 d-flex align-items-center">
        <div class="rounded-3 bg-danger-subtle text-danger p-3 d-flex align-items-center justify-content-center me-3">
            <i class="fas fa-user-times fa-2x"></i>
        </div>
        <div>
            <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Aprendices que no han votado</span>
            <h3 class="mb-0 fw-bold text-danger"><?= number_format($gafGlobal['no_votaron'], 0, ',', '.'); ?></h3>
            <small class="text-muted"><i class="fas fa-layer-group me-1"></i> Total en todas las fichas del sistema</small>
        </div>
    </div>
</div>

<!-- Buscador de Ficha -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3 p-md-4">
        <form action="home.php?pg=<?= $pg; ?>" method="POST" id="form-filtro-ficha">
            <input type="hidden" name="fidfic" id="fidfic-hidden" value="<?= htmlspecialchars($fidfic ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <div class="position-relative">
                <label for="nvvv-buscar-input" class="form-label fw-semibold text-secondary mb-2 d-flex align-items-center small text-uppercase">
                    <i class="fas fa-search text-success me-2"></i> Buscar Ficha de Formación
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-layer-group text-muted"></i>
                    </span>
                    <input type="text"
                           id="nvvv-buscar-input"
                           class="form-control border-start-0"
                           placeholder="Escriba número de ficha o nombre del programa..."
                           autocomplete="off"
                           value="">
                    <button class="btn btn-outline-secondary" type="button" id="nvvv-btn-limpiar" title="Limpiar búsqueda">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Dropdown flotante de resultados -->
                <div id="nvvv-dropdown-fichas" class="shadow rounded-3 border bg-white position-absolute start-0 end-0" style="display:none; top:100%; max-height: 280px; overflow-y: auto; z-index: 1030; margin-top: 4px;"></div>
            </div>
        </form>
    </div>
</div>

<?php if($fidfic): ?>
    <?php 
    $fichaActualFiltrada = array_filter($fichas, function($f) use ($fidfic) {
        return $f['idfic'] == $fidfic;
    });
    $fichaActual = !empty($fichaActualFiltrada) ? current($fichaActualFiltrada) : ['idfic' => $fidfic, 'nomfic' => 'Ficha ' . $fidfic, 'nomval' => ''];
    
    $jornadaActual = (isset($fichaActual['nomval']) && strpos($fichaActual['nomval'], 'Ã') !== false) ? utf8_decode($fichaActual['nomval']) : ($fichaActual['nomval'] ?? '');
    $jornadaActual = str_replace(['MaÃ±ana', 'maÃ±ana'], ['Mañana', 'mañana'], $jornadaActual);

    $porcentajeVotaron = $gaf['total_personas'] > 0 ? round(($gaf['votaron'] / $gaf['total_personas']) * 100) : 0;
    $porcentajeNoVotaron = $gaf['total_personas'] > 0 ? (100 - $porcentajeVotaron) : 0;
    $gradosVotaron = $gaf['total_personas'] > 0 ? round(($gaf['votaron'] / $gaf['total_personas']) * 360) : 0;
    ?>
    
    <!-- Encabezado de la Ficha Seleccionada -->
    <div class="card border-0 shadow-sm rounded-3 mb-4" style="border-left: 5px solid #198754 !important;">
        <div class="card-body p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Ficha Seleccionada</span>
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center flex-wrap gap-2">
                    <i class="fas fa-id-card text-success"></i>
                    <span><?= htmlspecialchars($fichaActual['idfic'], ENT_QUOTES, 'UTF-8'); ?> &ndash; <?= htmlspecialchars($fichaActual['nomfic'], ENT_QUOTES, 'UTF-8'); ?></span>
                </h5>
            </div>
            <?php if(!empty($jornadaActual)): ?>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-6 fw-normal">
                    <i class="fas fa-clock text-success me-1"></i> <?= htmlspecialchars($jornadaActual, ENT_QUOTES, 'UTF-8'); ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Panel Estadístico de Votación -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-0 pt-3 pb-0">
            <h6 class="fw-bold text-secondary mb-0">
                <i class="fas fa-chart-pie text-success me-2"></i> Estado de la Votación
            </h6>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-4">
                <!-- Gráfico de Donut -->
                <div class="col-12 col-md-5 col-lg-4 text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="position-relative d-inline-flex align-items-center justify-content-center" 
                         style="width: 170px; height: 170px; border-radius: 50%; background: conic-gradient(#198754 <?= $gradosVotaron ?>deg, #dc3545 0deg); box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <div class="bg-white rounded-circle d-flex flex-column align-items-center justify-content-center shadow-sm" style="width: 118px; height: 118px;">
                            <span class="fw-bold text-dark fs-3" style="line-height: 1;"><?= $porcentajeVotaron ?>%</span>
                            <span class="text-muted small" style="font-size: 11px;">Participación</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <span class="small d-inline-flex align-items-center text-secondary">
                            <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background-color: #198754;"></span> Votaron
                        </span>
                        <span class="small d-inline-flex align-items-center text-secondary">
                            <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background-color: #dc3545;"></span> No votaron
                        </span>
                    </div>
                </div>

                <!-- Tabla Resumen -->
                <div class="col-12 col-md-7 col-lg-8">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-secondary small text-uppercase py-2">Métrica</th>
                                    <th class="text-center text-secondary small text-uppercase py-2">Cantidad</th>
                                    <th class="text-end text-secondary small text-uppercase py-2">Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-2">
                                        <i class="fas fa-users text-primary me-2"></i>
                                        <strong>Total Aprendices</strong>
                                    </td>
                                    <td class="text-center fw-bold py-2"><?= $gaf['total_personas'] ?></td>
                                    <td class="text-end text-muted py-2">100%</td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span class="text-success fw-semibold">Votaron</span>
                                    </td>
                                    <td class="text-center fw-bold text-success py-2"><?= $gaf['votaron'] ?></td>
                                    <td class="text-end fw-semibold text-success py-2"><?= $porcentajeVotaron ?>%</td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        <span class="text-danger fw-semibold">No votaron</span>
                                    </td>
                                    <td class="text-center fw-bold text-danger py-2"><?= $gaf['no_votaron'] ?></td>
                                    <td class="text-end fw-semibold text-danger py-2"><?= $porcentajeNoVotaron ?>%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Listado de Candidatos y Aprendices -->
    <div class="mb-4">
        <?php if(!empty($candidatos) || !empty($aprendices)): ?>

            <!-- SECCIÓN 1: CANDIDATOS A VOCERO -->
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                    <i class="fas fa-bullhorn text-success me-2"></i> Candidatos a Vocero (<?= count($candidatos); ?>)
                </h5>
            </div>

            <?php if(!empty($candidatos)): ?>
                <?php foreach ($candidatos as $candidato): ?>
                    <div class="card border-0 shadow-sm rounded-3 mb-2" style="border-left: 5px solid #198754 !important;">
                        <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="img/user.jpg" alt="Candidato a Vocero" width="44" height="44" class="rounded-circle border flex-shrink-0" style="object-fit: cover;">
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <span class="fw-bold text-dark fs-6"><?= htmlspecialchars($candidato['nomusu'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1 small">
                                            <i class="fas fa-star text-warning me-1"></i> Candidato a Vocero
                                        </span>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-id-card me-1 text-secondary"></i> Documento: <?= htmlspecialchars($candidato['ndocusu'], ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <?php if ($candidato['votado']): ?>
                                    <span class="badge bg-success px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1">
                                        <i class="fas fa-check-circle"></i> Votó
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1">
                                        <i class="fas fa-times-circle"></i> No ha votado
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card border-0 shadow-sm rounded-3 mb-3 bg-light text-center p-3 text-muted">
                    <small><i class="fas fa-info-circle me-1"></i> No hay candidatos a vocero registrados en esta ficha</small>
                </div>
            <?php endif; ?>

            <!-- SECCIÓN 2: APRENDICES DE LA FICHA -->
            <div class="d-flex align-items-center justify-content-between mt-4 mb-3 pb-2 border-bottom">
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                    <i class="fas fa-user-graduate text-primary me-2"></i> Aprendices de la Ficha (<?= count($aprendices); ?>)
                </h5>
            </div>

            <?php if(!empty($aprendices)): ?>
                <?php foreach ($aprendices as $aprendiz): ?>
                    <div class="card border-0 shadow-sm rounded-3 mb-2">
                        <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="img/user.jpg" alt="Aprendiz" width="42" height="42" class="rounded-circle border flex-shrink-0" style="object-fit: cover;">
                                <div>
                                    <div class="fw-semibold text-dark mb-1"><?= htmlspecialchars($aprendiz['nomusu'], ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="text-muted small">
                                        <i class="fas fa-id-card me-1 text-secondary"></i> Documento: <?= htmlspecialchars($aprendiz['ndocusu'], ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <?php if ($aprendiz['votado']): ?>
                                    <span class="badge bg-success px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1">
                                        <i class="fas fa-check-circle"></i> Votó
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1">
                                        <i class="fas fa-times-circle"></i> No ha votado
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card border-0 shadow-sm rounded-3 mb-3 bg-light text-center p-3 text-muted">
                    <small><i class="fas fa-info-circle me-1"></i> No hay aprendices registrados en esta ficha</small>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-warning text-center p-4 rounded-3 border-0 shadow-sm">
                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                <h6 class="mb-0 fw-bold">No se encontraron aprendices ni candidatos en esta ficha</h6>
            </div>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="alert alert-light border shadow-sm text-center p-4 rounded-3">
        <i class="fas fa-arrow-up text-success fa-2x mb-2"></i>
        <h5 class="fw-semibold text-dark mb-1">Seleccione una ficha de formación</h5>
        <p class="text-muted small mb-0">Use el buscador superior para seleccionar una ficha y consultar los votos y no votantes.</p>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('nvvv-buscar-input');
    var fichaHidden = document.getElementById('fidfic-hidden');
    var btnLimpiar  = document.getElementById('nvvv-btn-limpiar');
    var dropdown    = document.getElementById('nvvv-dropdown-fichas');
    var form        = document.getElementById('form-filtro-ficha');

    if (!searchInput) return;

    var listaFichas = [
        <?php foreach ($fichas as $ficha):
            $jornadaFic = (isset($ficha['nomval']) && strpos($ficha['nomval'], 'Ã') !== false) ? utf8_decode($ficha['nomval']) : ($ficha['nomval'] ?? '');
            $jornadaFic = str_replace(['MaÃ±ana', 'maÃ±ana'], ['Mañana', 'mañana'], $jornadaFic);
        ?>
        { id: "<?= $ficha['idfic']; ?>", nombre: <?= json_encode($ficha['nomfic']); ?>, jornada: <?= json_encode($jornadaFic); ?> },
        <?php endforeach; ?>
    ];

    var activeIndex = -1;

    function normalizar(t) {
        return (t || '').toString().toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();
    }

    function badgeJornada(jornada) {
        var j = normalizar(jornada);
        if (j.includes('manana') || j.includes('ma\u00f1ana')) {
            return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-sun me-1"></i>' + jornada + '</span>';
        } else if (j.includes('tarde')) {
            return '<span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-cloud-sun me-1"></i>' + jornada + '</span>';
        } else if (j.includes('noche') || j.includes('nocturna')) {
            return '<span class="badge bg-dark text-white px-2 py-1"><i class="fas fa-moon me-1"></i>' + jornada + '</span>';
        } else if (j.includes('fin de semana') || j.includes('sabado') || j.includes('s\u00e1bado')) {
            return '<span class="badge bg-secondary text-white px-2 py-1"><i class="fas fa-calendar-week me-1"></i>' + jornada + '</span>';
        } else if (j.includes('virtual')) {
            return '<span class="badge bg-primary text-white px-2 py-1"><i class="fas fa-laptop me-1"></i>' + jornada + '</span>';
        }
        return '<span class="badge bg-light text-dark border px-2 py-1">' + jornada + '</span>';
    }

    function renderDropdown(lista) {
        dropdown.innerHTML = '';
        activeIndex = -1;
        if (lista.length === 0) {
            dropdown.innerHTML = '<div class="p-3 text-muted text-center small"><i class="fas fa-exclamation-circle text-warning me-1"></i> No se encontraron fichas</div>';
            dropdown.style.display = 'block';
            return;
        }
        lista.forEach(function (item) {
            var div = document.createElement('div');
            div.className = 'p-2 px-3 border-bottom d-flex justify-content-between align-items-center nvvv-item-ficha';
            div.style.cursor = 'pointer';
            div.style.transition = 'background-color 0.15s ease';
            div.innerHTML =
                '<div class="text-truncate me-2"><strong class="text-success">' + item.id + '</strong> &ndash; <span class="text-dark">' + item.nombre + '</span></div>' +
                badgeJornada(item.jornada);

            div.addEventListener('mouseenter', function () {
                this.style.backgroundColor = '#e8f5e9';
            });
            div.addEventListener('mouseleave', function () {
                if (!this.classList.contains('nvvv-active')) {
                    this.style.backgroundColor = '';
                }
            });
            div.addEventListener('click', function () { seleccionar(item); });
            dropdown.appendChild(div);
        });
        dropdown.style.display = 'block';
    }

    function seleccionar(item) {
        searchInput.value = item.id + ' - ' + item.nombre + ' (' + item.jornada + ')';
        fichaHidden.value = item.id;
        dropdown.style.display = 'none';
        form.submit();
    }

    function filtrar() {
        var query = normalizar(searchInput.value);
        if (!query) { renderDropdown(listaFichas); return; }
        var esNumero = /^\d+$/.test(query);
        var res = listaFichas.filter(function (item) {
            var idN  = normalizar(item.id);
            var nomN = normalizar(item.nombre);
            return esNumero ? idN.indexOf(query) === 0 : (nomN.indexOf(query) !== -1 || idN.indexOf(query) === 0);
        });
        renderDropdown(res);
    }

    searchInput.addEventListener('input', filtrar);
    searchInput.addEventListener('focus', filtrar);

    searchInput.addEventListener('keydown', function (e) {
        var items = dropdown.querySelectorAll('.nvvv-item-ficha');
        if (dropdown.style.display === 'none' || items.length === 0) return;
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = (activeIndex + 1) % items.length;
            items.forEach(function (el, i) {
                var isActive = (i === activeIndex);
                el.classList.toggle('nvvv-active', isActive);
                el.style.backgroundColor = isActive ? '#e8f5e9' : '';
            });
            items[activeIndex].scrollIntoView({ block: 'nearest' });
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            items.forEach(function (el, i) {
                var isActive = (i === activeIndex);
                el.classList.toggle('nvvv-active', isActive);
                el.style.backgroundColor = isActive ? '#e8f5e9' : '';
            });
            items[activeIndex].scrollIntoView({ block: 'nearest' });
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIndex >= 0 && items[activeIndex]) { items[activeIndex].click(); }
            else if (items.length > 0) { items[0].click(); }
        } else if (e.key === 'Escape') {
            dropdown.style.display = 'none';
        }
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function () {
            searchInput.value = '';
            fichaHidden.value = '';
            dropdown.style.display = 'none';
            searchInput.focus();
        });
    }

    /* Mostrar ficha ya seleccionada al cargar */
    <?php if ($fidfic): ?>
    (function () {
        var actual = listaFichas.find(function (f) { return f.id == "<?= $fidfic; ?>"; });
        if (actual) searchInput.value = actual.id + ' - ' + actual.nombre + ' (' + actual.jornada + ')';
    })();
    <?php endif; ?>
});
</script>