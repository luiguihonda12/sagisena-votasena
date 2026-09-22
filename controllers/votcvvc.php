<?php
require_once('models/votmvvc.php');

// Obtener parámetros de sesión y navegación con seguridad
$idusu = isset($_SESSION['idusu']) ? $_SESSION['idusu'] : (isset($_SESSION['id']) ? $_SESSION['id'] : NULL);
$idper = isset($_SESSION['idper']) ? $_SESSION['idper'] : (isset($_SESSION['pefid']) ? $_SESSION['pefid'] : NULL);
$pg = isset($_REQUEST["pg"]) ? $_REQUEST["pg"] : 1202;

// Instanciar el modelo de votación de vocero
$votmvvc = new Votmvvc();
$mvvc = $votmvvc; // Compatibilidad

if ($idusu) {
    $votmvvc->setIdusu($idusu);
}

// Validación de sesión si no existe
if (!$idusu && session_status() === PHP_SESSION_ACTIVE && !isset($_SESSION['nomusu'])) {
    // Si no hay sesión válida, redirigir
    header("Location: index.php?error=sesion");
    exit();
}

// Consultar si el usuario ya votó
$yaVoto = $idusu ? $votmvvc->getOne('vocero') : false;
$votoInfo = ($idusu && $yaVoto) ? $votmvvc->getVotoUsuario('vocero') : null;
$candVotadoId = ($votoInfo && isset($votoInfo['canusu'])) ? $votoInfo['canusu'] : null;

// Procesar el registro del voto
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['opera']) && $_POST['opera'] === 'save') {
    $canusu = isset($_POST['canusu']) ? $_POST['canusu'] : NULL;

    if ($yaVoto) {
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'VOTO PREVIO REGISTRADO',
                    text: 'Ya has ejercido tu derecho al voto para vocero de ficha. No es posible modificar tu voto.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#00324D'
                }).then(() => {
                    window.location.href = 'home.php?pg=1202';
                });
              </script>";
        exit();
    }

    if (!empty($canusu)) {
        $votmvvc->setCanusu($canusu);
        $votmvvc->setDtvot(date("Y-m-d H:i:s"));
        
        $guardado = $votmvvc->save('vocero');
        
        if ($guardado) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡VOTO REGISTRADO CON ÉXITO!',
                        text: 'Tu voto para vocero de ficha ha sido registrado correctamente.',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#39A900'
                    }).then(() => {
                        window.location.href = 'home.php?pg=1202';
                    });
                  </script>";
            exit();
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'NO SE PUDO REGISTRAR EL VOTO',
                        text: 'Hubo un inconveniente al procesar tu solicitud o ya votaste previamente.',
                        confirmButtonText: 'Reintentar',
                        confirmButtonColor: '#d33'
                    }).then(() => {
                        window.location.href = 'home.php?pg=1202';
                    });
                  </script>";
            exit();
        }
    }
}

// Obtener ficha del aprendiz
$idfic = $idusu ? $votmvvc->getFichaUsuario($idusu) : null;

// Candidatos: si el usuario tiene ficha, se muestran los voceros registrados en su ficha;
// si no tiene ficha (por ejemplo, personal administrativo), se muestran todos los candidatos
// voceros registrados del centro.
$fidcen = isset($_SESSION['idcen']) ? $_SESSION['idcen'] : null;
if ($idfic) {
    $candidatosBD = ($idusu) ? $votmvvc->getVocerosMismaFicha($idfic, $idusu) : [];
} else {
    $candidatosBD = $votmvvc->getAllVoceros($fidcen, $idusu);
}

// Módulo de voto en blanco
$votoBlanco = [
    'idusu' => 999,
    'nomusu' => 'Voto en Blanco',
    'noca' => 99,
    'idfic' => '',
    'nomfic' => 'Opción Democrática Institucional',
    'fotcan' => 'img/user.jpg',
    'lema' => 'Opción legítima para manifestar inconformidad o no afinidad con las candidaturas.',
    'is_blanco' => true
];

// Candidatos registrados desde el módulo Candidato Vocero (1211)
$dat = [];
foreach ($candidatosBD as $idx => $cand) {
    $dat[] = [
        'idusu' => $cand['idusu'],
        'nomusu' => $cand['nomusu'],
        'noca' => !empty($cand['noca']) ? $cand['noca'] : ($idx + 1),
        'idfic' => !empty($cand['idfic']) ? $cand['idfic'] : $idfic,
        'nomfic' => !empty($cand['nomfic']) ? $cand['nomfic'] : 'Formación Titulada',
        'fotcan' => 'img/user.jpg',
        'lema' => 'Compromiso, dedicación y vocería activa en representación de la ficha.',
        'is_blanco' => false
    ];
}
// Agregar opción de voto en blanco
$dat[] = $votoBlanco;

// Si el usuario ya votó, asegurarse de que la opción votada esté presente en $dat
if ($yaVoto && $candVotadoId) {
    $encontrado = false;
    foreach ($dat as $cExistente) {
        if ($cExistente['idusu'] == $candVotadoId) {
            $encontrado = true;
            break;
        }
    }
    if (!$encontrado && $votoInfo) {
        $dat[] = [
            'idusu' => $candVotadoId,
            'nomusu' => !empty($votoInfo['nomusu']) ? $votoInfo['nomusu'] : 'Candidato Seleccionado',
            'noca' => 'OK',
            'idfic' => !empty($votoInfo['idfic']) ? $votoInfo['idfic'] : $idfic,
            'nomfic' => !empty($votoInfo['nomfic']) ? $votoInfo['nomfic'] : 'Formación Titulada',
            'fotcan' => 'img/user.jpg',
            'lema' => 'Opción registrada en tu votación de vocero.',
            'is_blanco' => false
        ];
    }
}
?>