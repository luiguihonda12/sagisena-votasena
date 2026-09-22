<?php
    require_once ('models/votmvot.php');

    $mvot = new Votmvot();
   
    $idusu = isset($_SESSION['idusu']) ? $_SESSION['idusu'] : NULL;
    $idval = isset($_REQUEST['idval']) ? $_REQUEST['idval'] : NULL;
    $canusu = isset($_POST['canusu']) ? $_POST['canusu'] : NULL;
    $dtvot = date("Y-m-d H:i:s");

    $opera = isset($_REQUEST['opera']) ? $_REQUEST['opera'] : NULL;
    $pg = 1203;

    $mvot->setIdusu($idusu);

    // Consultar si el usuario ya ejerció su voto y qué candidato votó
    $yaVoto = $idusu ? $mvot->getOne() : false;
    $votoInfo = ($idusu && $yaVoto) ? $mvot->getVotoUsuario() : null;
    $candVotadoId = ($votoInfo && isset($votoInfo['canusu'])) ? $votoInfo['canusu'] : null;

    if ($opera == "save") {
        if ($yaVoto) {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Ya ejerciste tu derecho al voto para representante. No es posible modificar tu voto.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#117f09'
                }).then(() => {
                    window.location.href = 'home.php?pg=1203';
                });
            </script>";
            exit();
        }
        if (empty($canusu)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debes seleccionar una opción de voto',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = 'home.php?pg=1203';
                });
            </script>";
            exit();
        }
        $mvot->setCanusu($canusu);
        $mvot->setDtvot($dtvot);
        
        if ($mvot->save()) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Voto para representante registrado con éxito',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#117f09'
                }).then(() => {
                    window.location.href = 'home.php?pg=1203';
                });
            </script>";
            exit();
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ya has votado por representante o hubo un problema',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = 'home.php?pg=1203';
                });
            </script>";
            exit();
        }
    }

    // Jornada del votante
    $jorn = $mvot->getOneJor();
    if ($jorn && isset($jorn[0]['jornada']) && $jorn[0]['jornada'] != null && $jorn[0]['jornada'] != '') {
        $jorn = $jorn[0]['jornada'];
    } else {
        $jorn = 1;
    }

    // Lemas aleatorios de campaña para los candidatos
    $lemas = array(
        'Compromiso, liderazgo y trabajo en equipo para una formación con excelencia.',
        'Una voz fuerte que representa a todos los aprendices ante el centro de formación.',
        'Iniciativa, diálogo y gestión para mejorar la calidad de vida académica.',
        'Por una comunidad aprendiz más unida, participativa y visible.',
        'Escucha activa, propuestas claras y resultados que beneficien a todos.',
        'Transparencia, cercanía y dedicación al servicio de los aprendices.'
    );

    // Se muestran 3 candidatos aleatorios + el voto en blanco
    $dat = array();

    $candidatos = $mvot->getCandidatosAleatorios($jorn, 3);
    if ($candidatos) {
        foreach ($candidatos as $c) {
            $c['esblanco'] = false;
            $c['fotcan'] = 'img/user.jpg';
            $c['lema'] = $lemas[array_rand($lemas)];
            $dat[] = $c;
        }
    }

    $blanco = $mvot->getVotoBlanco($jorn);
    if ($blanco) {
        $blanco['esblanco'] = true;
        $blanco['nomusu'] = 'VOTO EN BLANCO';
        $blanco['lema'] = 'Opción legítima para manifestar que ninguna de las candidaturas cuenta con tu respaldo.';
        $dat[] = $blanco;
    }

    // Si el usuario ya votó, asegurarse de que el candidato votado esté presente en la lista
    if ($yaVoto && $candVotadoId) {
        $encontrado = false;
        foreach ($dat as $cExistente) {
            if ($cExistente['idusu'] == $candVotadoId) {
                $encontrado = true;
                break;
            }
        }
        if (!$encontrado) {
            $candElegido = $mvot->getCandidatoById($candVotadoId);
            if ($candElegido) {
                $candElegido['esblanco'] = ($candElegido['noca'] == '999' || strpos(strtoupper($candElegido['nomusu']), 'BLANCO') !== false);
                $candElegido['fotcan'] = 'img/user.jpg';
                $candElegido['lema'] = 'Opción registrada en tu votación.';
                array_unshift($dat, $candElegido);
            }
        }
    }

    if (empty($dat)) {
        $dat = array();
    }
?>