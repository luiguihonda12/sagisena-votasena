<?php
require_once ('models/votmnvv.php');

$mnvv = new Mnvv();
$idusu = isset($_POST['idusu']) ? $_POST['idusu'] : NULL;
$actusu = isset($_POST['actusu']) ? $_POST['actusu'] : NULL;
$idfic = isset($_POST['idfic']) ? $_POST['idfic'] : NULL;
$fidfic = isset($_POST['fidfic']) && $_POST['fidfic'] !== '' ? $_POST['fidfic'] : (isset($_GET['fidfic']) && $_GET['fidfic'] !== '' ? $_GET['fidfic'] : NULL);
$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;

// Si fidfic viene con texto (por ejemplo "321456 - ADSO ..."), extraer el numero de ficha
if ($fidfic && preg_match('/^(\d+)/', trim($fidfic), $matches)) {
    $fidfic = $matches[1];
}

if($ope == "save" || $ope == "edit") {
    $mnvv->setIdfic($idfic);
    $mnvv->setActusu($actusu);
    $mnvv->setIdusu($idusu);
    
    if($ope == "edit") {
        $mnvv->edit();
    } else {
        $mnvv->save();
    }
}

if($ope == "act" && $idusu && $actusu) {
    $mnvv->setActusu($actusu);
    $mnvv->editAct();
}

// se obtienen las fichas
$fichas = $mnvv->getFichas();

// Correccion de codificacion (por ejemplo MaÃ±ana -> Mañana)
foreach ($fichas as &$f) {
    if (isset($f['nomval'])) {
        if (strpos($f['nomval'], 'Ã') !== false) {
            $f['nomval'] = @utf8_decode($f['nomval']);
        }
        $f['nomval'] = str_replace(['MaÃ±ana', 'maÃ±ana'], ['Mañana', 'mañana'], $f['nomval']);
    }
}
unset($f);

// se obtiene los aprendices y las estadisticas de acuerdo a la ficha seleccionada
$dat = [];
$candidatos = [];
$aprendices = [];
$gaf = ['total_personas' => 0, 'votaron' => 0, 'no_votaron' => 0];

if($fidfic) {
    $candidatos = $mnvv->getCandidatosVoceroPorFicha($fidfic);
    $aprendices = $mnvv->getSoloAprendicesPorFicha($fidfic);

    foreach ($candidatos as &$c) {
        if (isset($c['nomval'])) {
            if (strpos($c['nomval'], 'Ã') !== false) $c['nomval'] = @utf8_decode($c['nomval']);
            $c['nomval'] = str_replace(['MaÃ±ana', 'maÃ±ana'], ['Mañana', 'mañana'], $c['nomval']);
        }
        if (isset($c['nomusu']) && strpos($c['nomusu'], 'Ã') !== false) {
            $c['nomusu'] = @utf8_decode($c['nomusu']);
        }
    }
    unset($c);

    foreach ($aprendices as &$a) {
        if (isset($a['nomval'])) {
            if (strpos($a['nomval'], 'Ã') !== false) $a['nomval'] = @utf8_decode($a['nomval']);
            $a['nomval'] = str_replace(['MaÃ±ana', 'maÃ±ana'], ['Mañana', 'mañana'], $a['nomval']);
        }
        if (isset($a['nomusu']) && strpos($a['nomusu'], 'Ã') !== false) {
            $a['nomusu'] = @utf8_decode($a['nomusu']);
        }
    }
    unset($a);

    $dat = array_merge($candidatos, $aprendices);
    $resGaf = $mnvv->getEstadisticasPorFicha($fidfic);
    if ($resGaf && is_array($resGaf)) {
        $gaf = [
            'total_personas' => (int)($resGaf['total_personas'] ?? 0),
            'votaron'        => (int)($resGaf['votaron'] ?? 0),
            'no_votaron'     => (int)($resGaf['no_votaron'] ?? 0)
        ];
    }
}

$gafGlobal = $mnvv->getEstadisticasGlobalesNoVotantes();
if (!$gafGlobal || !is_array($gafGlobal)) {
    $gafGlobal = ['total_personas' => 0, 'votaron' => 0, 'no_votaron' => 0];
} else {
    $gafGlobal['total_personas'] = (int)($gafGlobal['total_personas'] ?? 0);
    $gafGlobal['votaron']        = (int)($gafGlobal['votaron'] ?? 0);
    $gafGlobal['no_votaron']     = (int)($gafGlobal['no_votaron'] ?? 0);
}
?>