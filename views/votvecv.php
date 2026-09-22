<?php require_once 'controllers/votcecv.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Selección de Voceros", 2); ?>

    <?php if (isset($_REQUEST['idficfil']) && !empty($_REQUEST['idficfil'])): ?>
        <div class="alert alert-info py-2 px-3 d-flex align-items-center mb-3 rounded-3 shadow-sm border-0 bg-info-subtle text-info-emphasis">
            <i class="fas fa-circle-info fa-lg me-2 text-info"></i>
            <div>
                <strong>Regla de postulación:</strong> Puedes seleccionar un máximo de <strong>4 candidatos</strong> a vocero por ficha.
            </div>
        </div>
    <?php endif; ?>

    <!-- Dashboard Estadístico -->
    <div class="row g-3 mb-4 mt-1">
        <!-- Card 1: Cantidad de Fichas -->
        <div class="col-12 col-md-6">
            <div class="card border shadow-sm h-100 rounded-3">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="rounded-3 bg-success-subtle text-success me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="fas fa-layer-group fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold d-block">Cantidad de Fichas</span>
                        <h3 class="mb-0 fw-bold text-dark"><?= number_format($totalFichas, 0, ',', '.'); ?></h3>
                        <small class="text-success"><i class="fas fa-check-circle me-1"></i>Fichas registradas en el sistema</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Cantidad de Candidatos Vocero -->
        <div class="col-12 col-md-6">
            <div class="card border shadow-sm h-100 rounded-3">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="rounded-3 bg-warning-subtle text-warning-emphasis me-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="fas fa-check-to-slot fa-2x text-warning"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold d-block">Candidatos a Vocero</span>
                        <?php if (isset($_REQUEST['idficfil']) && !empty($_REQUEST['idficfil'])): ?>
                            <h3 class="mb-0 fw-bold text-dark">
                                <?= (int)$conteoCandidatos; ?> <span class="fs-6 text-muted fw-normal">/ 4 en ficha <?= htmlspecialchars($_REQUEST['idficfil']); ?></span>
                            </h3>
                            <small class="<?= $conteoCandidatos >= 4 ? 'text-danger' : 'text-success'; ?> fw-semibold">
                                <i class="fas <?= $conteoCandidatos >= 4 ? 'fa-ban' : 'fa-user-check'; ?> me-1"></i>
                                <?= $conteoCandidatos >= 4 ? 'Límite alcanzado (4/4)' : (4 - $conteoCandidatos) . ' cupo(s) disponible(s)'; ?> 
                                <span class="text-muted fw-normal">(Total sistema: <?= number_format($totalCandidatosVocero, 0, ',', '.'); ?>)</span>
                            </small>
                        <?php else: ?>
                            <h3 class="mb-0 fw-bold text-dark"><?= number_format($totalCandidatosVocero, 0, ',', '.'); ?></h3>
                            <small class="text-muted"><i class="fas fa-user-check me-1 text-warning"></i>Candidatos registrados en todo el sistema</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtro y Selector de Ficha con Búsqueda -->
<div class="card border shadow-sm rounded-3 mb-4">
    <div class="card-body p-3 p-md-4">
        <form id="formFicha" name="frm1" action="home.php?pg=<?= $pg; ?>" method="POST">
            <input type="hidden" name="pg" value="<?= $pg; ?>">
            <div class="row g-3 align-items-center">
                <!-- Campo de búsqueda en tiempo real con lista de resultados flotante -->
                <div class="col-12 col-md-6 position-relative">
                    <label for="buscar-ficha-input" class="form-label fw-semibold text-secondary mb-1">
                        <i class="fas fa-search text-success me-1"></i> Buscar Ficha por Número o Nombre
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted">
                            <i class="fas fa-filter"></i>
                        </span>
                        <input type="text" 
                               id="buscar-ficha-input" 
                               class="form-control" 
                               placeholder="Escriba número o nombre (ej: ADSO, 269...)..." 
                               autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="btn-limpiar-busqueda" title="Limpiar búsqueda">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <!-- Menú flotante con resultados -->
                    <div id="dropdown-fichas-resultados" 
                         class="list-group position-absolute w-100 shadow mt-1 overflow-auto rounded-3 border" 
                         style="top: 100%; left: 0; z-index: 1050; max-height: 280px; display: none;"></div>
                </div>

                <!-- Selector de Ficha sincronizado -->
                <div class="col-12 col-md-6">
                    <label for="ficha-selector" class="form-label fw-semibold text-secondary mb-1">
                        <i class="fas fa-id-card text-success me-1"></i> Seleccione Ficha para ver aprendices
                    </label>
                    <select name="idficfil" id="ficha-selector" class="form-select" onchange="this.form.submit();" required>
                        <option value="">-- Seleccione una ficha --</option>
                        <?php
                        if ($dfi) {
                            foreach ($dfi as $de) {
                                $jornadaLimpia = (isset($de['nomval']) && strpos($de['nomval'], 'Ã') !== false) ? @utf8_decode($de['nomval']) : ($de['nomval'] ?? '');
                                $jornadaLimpia = str_replace(['MaÃ±ana', 'maÃ±ana'], ['Mañana', 'mañana'], $jornadaLimpia);
                                $selected = (isset($_REQUEST['idficfil']) && $_REQUEST['idficfil'] == $de['idfic']) ? 'selected' : '';
                        ?>
                                <option value="<?= $de['idfic']; ?>" 
                                        data-numero="<?= $de['idfic']; ?>"
                                        data-nombre="<?= htmlspecialchars(mb_strtolower($de['nomfic'], 'UTF-8')); ?>"
                                        data-jornada="<?= htmlspecialchars(mb_strtolower($jornadaLimpia, 'UTF-8')); ?>"
                                        data-nombre-mostrar="<?= htmlspecialchars($de['nomfic']); ?>"
                                        data-jornada-mostrar="<?= htmlspecialchars($jornadaLimpia); ?>"
                                        <?= $selected; ?>>
                                    <?= $de['idfic']; ?> - <?= $de['nomfic']; ?> (<?= $jornadaLimpia; ?>)
                                </option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (isset($_REQUEST['idficfil']) && !empty($_REQUEST['idficfil'])): ?>
    <div class="card border shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-success-subtle text-success p-2 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Aprendices de la Ficha <?= htmlspecialchars($idficfil); ?></h5>
                    <small class="text-muted">Total: <?= count($dat ?? []); ?> aprendiz(ces) registrados</small>
                </div>
            </div>
            <div>
                <span class="badge <?= $conteoCandidatos >= 4 ? 'bg-danger' : 'bg-success'; ?> px-3 py-2 fs-6">
                    <i class="fas <?= $conteoCandidatos >= 4 ? 'fa-lock' : 'fa-user-check'; ?> me-1"></i>
                    Candidatos: <?= $conteoCandidatos; ?> / 4
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if ($dat && count($dat) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th class="text-center" style="width: 140px;">Acción</th>
                                <th class="text-center" style="width: 100px;">Estado</th>
                                <th class="text-center" style="width: 80px;">Foto</th>
                                <th>Aprendiz</th>
                                <th>No. Documento</th>
                                <th>Perfil / Cargo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dat as $d): 
                                $esCandidato = ($d['es_candidato'] > 0 || $d['idper'] == 13);
                            ?>
                                <tr class="<?= $esCandidato ? 'table-success bg-opacity-25' : ''; ?>">
                                    <td class="text-center" style="vertical-align: middle !important; position: static !important;">
                                        <?php if ($esCandidato): ?>
                                            <a href="javascript:void(0)" role="button" 
                                               class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center"
                                               onclick="confirmarEliminarVocero(<?= $d['idusu']; ?>, '<?= addslashes(htmlspecialchars($d['nomusu'])); ?>')"
                                               style="position: static !important; float: none !important; margin: 0 auto !important; vertical-align: middle !important; text-decoration: none;">
                                                <i class="fas fa-user-minus me-1"></i> Quitar
                                            </a>
                                        <?php else: ?>
                                            <?php if ($conteoCandidatos < 4): ?>
                                                <a href="javascript:void(0)" role="button" 
                                                   class="btn btn-sm btn-success d-inline-flex align-items-center justify-content-center text-white"
                                                   onclick="confirmarVocero(<?= $d['idusu']; ?>, '<?= addslashes(htmlspecialchars($d['nomusu'])); ?>')"
                                                   style="position: static !important; float: none !important; margin: 0 auto !important; vertical-align: middle !important; text-decoration: none;">
                                                    <i class="fas fa-user-plus me-1"></i> Seleccionar
                                                </a>
                                            <?php else: ?>
                                                <button type="button" 
                                                    class="btn btn-sm btn-secondary d-inline-flex align-items-center justify-content-center" 
                                                    disabled title="Límite máximo de 4 candidatos alcanzado"
                                                    style="position: static !important; float: none !important; margin: 0 auto !important; vertical-align: middle !important;">
                                                    <i class="fas fa-ban me-1"></i> Cupo lleno
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle !important;">
                                        <?php if ($d['actusu'] == 1): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                <i class="fas fa-circle-check me-1"></i>Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                                <i class="fas fa-circle-xmark me-1"></i>Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle !important;">
                                        <?php 
                                        $fotoSrc = (!empty($d['fotcan']) && file_exists($d['fotcan'])) ? $d['fotcan'] : 'img/user.jpg';
                                        ?>
                                        <img src="<?= $fotoSrc; ?>" alt="Foto" width="45" height="45" class="rounded-circle border shadow-sm" style="object-fit: cover;">
                                    </td>
                                    <td style="vertical-align: middle !important;">
                                        <span class="fw-bold text-dark d-block"><?= htmlspecialchars($d['nomusu']); ?></span>
                                        <?php if ($esCandidato): ?>
                                            <small class="text-success fw-semibold"><i class="fas fa-award me-1"></i>Postulado a vocero</small>
                                        <?php endif; ?>
                                    </td>
                                    <td style="vertical-align: middle !important;">
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-1" style="font-size: 1.4rem !important; font-weight: 600; letter-spacing: 0.5px;">
                                            <?= htmlspecialchars($d['ndocusu']); ?>
                                        </span>
                                    </td>
                                    <td style="vertical-align: middle !important;">
                                        <?php if ($esCandidato): ?>
                                            <span class="badge bg-success px-2 py-1">
                                                <i class="fas fa-star me-1"></i>Candidato a Vocero
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                                <?= htmlspecialchars($d['nomper']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-info-circle fa-2x text-secondary mb-2 d-block"></i>
                    No se encontraron aprendices registrados en esta ficha.
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('buscar-ficha-input');
    const selectFicha = document.getElementById('ficha-selector');
    const btnLimpiar = document.getElementById('btn-limpiar-busqueda');
    const dropdownResultados = document.getElementById('dropdown-fichas-resultados');
    const formFicha = document.getElementById('formFicha');

    if (!selectFicha || !searchInput) return;

    // Almacenar todas las opciones originales
    const todasLasOpciones = Array.from(selectFicha.options);
    const placeholderOption = todasLasOpciones[0];
    const opcionesFichas = todasLasOpciones.slice(1);
    let activeIndex = -1;

    // Función para normalizar cadenas (quitar tildes y pasar a minúsculas)
    function normalizar(texto) {
        return (texto || '')
            .toString()
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    // Coincidencia inteligente por frase completa o por cada palabra
    function coincideFicha(query, numero, nombre, jornada, textoCompleto) {
        if (!query) return true;
        if (numero.includes(query) || nombre.includes(query) || jornada.includes(query) || textoCompleto.includes(query)) {
            return true;
        }
        const palabras = query.split(/\s+/).filter(p => p.length > 0);
        if (palabras.length > 1) {
            return palabras.every(p => 
                numero.includes(p) || nombre.includes(p) || jornada.includes(p) || textoCompleto.includes(p)
            );
        }
        return false;
    }

    // Función para filtrar las opciones y mostrar el dropdown inferior
    function filtrarOpciones(abrirDropdown = true) {
        const query = normalizar(searchInput.value);
        const valorSeleccionado = selectFicha.value;
        activeIndex = -1;

        // Limpiar select nativo y reinsertar placeholder
        selectFicha.innerHTML = '';
        selectFicha.appendChild(placeholderOption.cloneNode(true));

        if (dropdownResultados) {
            dropdownResultados.innerHTML = '';
        }

        let coincidencias = [];
        let opcionSeleccionadaExiste = false;

        opcionesFichas.forEach(function (opt) {
            const numero = normalizar(opt.getAttribute('data-numero') || '');
            const nombre = normalizar(opt.getAttribute('data-nombre') || '');
            const jornada = normalizar(opt.getAttribute('data-jornada') || '');
            const textoCompleto = normalizar(opt.text);

            const nombreMostrar = opt.getAttribute('data-nombre-mostrar') || opt.text;
            const jornadaMostrar = opt.getAttribute('data-jornada-mostrar') || '';

            if (coincideFicha(query, numero, nombre, jornada, textoCompleto)) {
                const optClon = opt.cloneNode(true);
                if (opt.value === valorSeleccionado) {
                    optClon.selected = true;
                    opcionSeleccionadaExiste = true;
                }
                selectFicha.appendChild(optClon);

                coincidencias.push({
                    numero: opt.value,
                    nombreMostrar: nombreMostrar,
                    jornadaMostrar: jornadaMostrar,
                    textoCompleto: opt.text
                });
            }
        });

        if (!opcionSeleccionadaExiste && valorSeleccionado && query) {
            selectFicha.value = '';
        }

        // Renderizar el dropdown inferior si el usuario está escribiendo
        if (dropdownResultados) {
            if (query.length > 0 && abrirDropdown) {
                if (coincidencias.length > 0) {
                    coincidencias.forEach(function (item, index) {
                        const itemBtn = document.createElement('button');
                        itemBtn.type = 'button';
                        itemBtn.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 border-bottom';
                        itemBtn.dataset.index = index;
                        itemBtn.dataset.idfic = item.numero;

                        const jLower = normalizar(item.jornadaMostrar);
                        let badgeClass = 'bg-light text-dark border';
                        let iconoJornada = 'fa-clock';
                        if (jLower.includes('manana')) {
                            badgeClass = 'bg-warning-subtle text-dark border border-warning';
                            iconoJornada = 'fa-sun';
                        } else if (jLower.includes('tarde')) {
                            badgeClass = 'bg-info-subtle text-dark border border-info';
                            iconoJornada = 'fa-cloud-sun';
                        } else if (jLower.includes('virtual')) {
                            badgeClass = 'bg-primary-subtle text-primary border border-primary';
                            iconoJornada = 'fa-laptop';
                        } else if (jLower.includes('noche') || jLower.includes('nocturna')) {
                            badgeClass = 'bg-dark text-white';
                            iconoJornada = 'fa-moon';
                        }

                        itemBtn.innerHTML = `
                            <div class="text-truncate me-2">
                                <strong class="text-success">${item.numero}</strong> - <span class="text-dark">${item.nombreMostrar}</span>
                            </div>
                            <span class="badge ${badgeClass} text-nowrap px-2 py-1">
                                <i class="fas ${iconoJornada} me-1"></i>${item.jornadaMostrar || 'Jornada N/A'}
                            </span>
                        `;

                        itemBtn.addEventListener('click', function () {
                            seleccionarFicha(item.numero, item.textoCompleto);
                        });

                        dropdownResultados.appendChild(itemBtn);
                    });
                } else {
                    dropdownResultados.innerHTML = `
                        <div class="p-3 text-muted text-center small bg-white">
                            <i class="fas fa-circle-exclamation text-warning me-1"></i> No se encontraron fichas para "<strong>${searchInput.value}</strong>"
                        </div>
                    `;
                }
                dropdownResultados.style.display = 'block';
            } else {
                dropdownResultados.style.display = 'none';
            }
        }
    }

    function seleccionarFicha(idfic, textoVisible) {
        selectFicha.value = idfic;
        searchInput.value = textoVisible || idfic;
        if (dropdownResultados) dropdownResultados.style.display = 'none';
        formFicha.submit();
    }

    searchInput.addEventListener('input', function () {
        filtrarOpciones(true);
    });

    searchInput.addEventListener('focus', function () {
        if (searchInput.value.trim().length > 0) {
            filtrarOpciones(true);
        }
    });

    searchInput.addEventListener('keydown', function (e) {
        if (!dropdownResultados || dropdownResultados.style.display === 'none') {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (selectFicha.options.length > 1) {
                    selectFicha.selectedIndex = 1;
                    selectFicha.form.submit();
                }
            }
            return;
        }

        const items = dropdownResultados.querySelectorAll('.list-group-item');
        if (items.length === 0) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = (activeIndex + 1) % items.length;
            actualizarItemActivo(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            actualizarItemActivo(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIndex >= 0 && items[activeIndex]) {
                items[activeIndex].click();
            } else if (items.length > 0) {
                items[0].click();
            }
        } else if (e.key === 'Escape') {
            dropdownResultados.style.display = 'none';
        }
    });

    function actualizarItemActivo(items) {
        items.forEach((item, idx) => {
            if (idx === activeIndex) {
                item.classList.add('active');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });
    }

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && (!dropdownResultados || !dropdownResultados.contains(e.target))) {
            if (dropdownResultados) dropdownResultados.style.display = 'none';
        }
    });

    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', function () {
            searchInput.value = '';
            filtrarOpciones(false);
            if (dropdownResultados) dropdownResultados.style.display = 'none';
            searchInput.focus();
        });
    }
});

function confirmarVocero(idusu, nombre) {
    Swal.fire({
        title: '¿Confirmar Candidato?',
        html: '¿Deseas postular a <strong>' + nombre + '</strong> como candidato a vocero de esta ficha?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check me-1"></i> Sí, postular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `home.php?pg=<?= $pg; ?>&idusu=${idusu}&opera=make_vocero&idficfil=<?= isset($_REQUEST['idficfil']) ? urlencode($_REQUEST['idficfil']) : ''; ?>`;
        }
    });
}

function confirmarEliminarVocero(idusu, nombre) {
    Swal.fire({
        title: '¿Quitar Candidato?',
        html: '¿Estás seguro de retirar a <strong>' + nombre + '</strong> como candidato a vocero?<br><small class="text-muted">Volverá a su estado regular de aprendiz.</small>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-user-minus me-1"></i> Sí, quitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `home.php?pg=<?= $pg; ?>&idusu=${idusu}&opera=remove_vocero&idficfil=<?= isset($_REQUEST['idficfil']) ? urlencode($_REQUEST['idficfil']) : ''; ?>`;
        }
    });
}
</script>