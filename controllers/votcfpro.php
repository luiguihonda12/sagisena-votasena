<?php
require_once 'models/votmfpro.php';

$mfpro = new Votmfpro();
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu'] : $_SESSION['idusu'];

$pg = 1218;

/* Datos del candidato y de su propuesta */
$datOne = NULL;
$datC   = NULL;
$datM   = NULL;
$dus    = NULL;
$vidRut = NULL;

if ($idusu) {
	$mfpro->setIdusu($idusu);

	$mfpro->setIddom(3);
	$datOne = $mfpro->selOne();

	$mfpro->setIddom(2);
	$datC = $mfpro->selOne();

	$mfpro->setIddom(4);
	$datM = $mfpro->selOne();

	$dus = $mfpro->getUsu();

	$vid = $mfpro->getVideo();
	$vidRut = ($vid && isset($vid[0]['rutvid']) && $vid[0]['rutvid']) ? $vid[0]['rutvid'] : NULL;
}

/* Perfiles Aprendiz: solo a un estudiante se le muestra ficha y jornada */
$perEst = array(4, 8, 22, 23, 39, 41);
$esEst  = ($dus && in_array((int)$dus[0]['idper'], $perEst)) ? true : false;

$dcon = $mfpro->getVal(2);
$dvpr = $mfpro->getVal(3);
$dman = $mfpro->getVal(4);

/**
 * Decodifica entidades HTML de los textos administrativos (valor/usuario)
 * para conservar la presentación de la aplicación original.
 */
if (!function_exists('txlbl')) {
	function txlbl($s) {
		return html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	}
}

if (!function_exists('vidmime')) {
	function vidmime($fname) {
		$ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
		$m = array(
			'mp4' => 'video/mp4', 'm4v' => 'video/mp4', 'webm' => 'video/webm',
			'mov' => 'video/quicktime', 'qt' => 'video/quicktime',
			'avi' => 'video/x-msvideo', 'ogv' => 'video/ogg'
		);
		return isset($m[$ext]) ? $m[$ext] : 'video/mp4';
	}
}