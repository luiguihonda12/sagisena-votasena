<?php
ini_set('memory_limit', '512M');
require_once("models/votmact.php");
if(file_exists('vendor/autoload.php')) {
    require_once('vendor/autoload.php');
}
use Dompdf\Dompdf;

$pdf = isset($_GET['pdf']) ? $_GET['pdf']:NULL;
$fidcen = isset($_REQUEST['fidcen']) ? $_REQUEST['fidcen']:NULL;
$fidjor = isset($_REQUEST['fidjor']) ? $_REQUEST['fidjor']:NULL;

$votmact = new Votmact();

$dat = $votmact->selAll($fidcen, $fidjor);
$djor = $votmact->getJor();
if ($fidjor) {
    $djor = array_filter($djor, function($j) use ($fidjor) {
        return $j['idval'] == $fidjor;
    });
}

if (!function_exists('imagenes')) {
    function imagenes($imgn){
        if (file_exists($imgn)) {
            $imgnBase64 = "data:image/png;base64," . base64_encode(file_get_contents($imgn));
            return $imgnBase64;
        } else {
            $default = "img/user.jpg";
            if (file_exists($default)) {
                return "data:image/png;base64," . base64_encode(file_get_contents($default));
            }
            return "";
        }
    }
}

if (!function_exists('urlimg')) {
    function urlimg($url)
    {
        if (file_exists($url)) {
            $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
            return $imagenBase64;
        }
        return "";
    }
}

date_default_timezone_set('America/Bogota');
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = date('d')." de ".$mes[date('m')-1]." de ".date('Y');
$fecha2 = date('YmdHis');
$ancho = 750;
$año = date('Y');
$fcan = date('Y')+1;
$hora_inicio = date("H:i") . " Hrs";
$hora_fin = date("H:i", strtotime("+30 minutes")) . " Hrs";

// Cargar la vista HTML para generar la variable $html
require 'views/votract.php';

if($pdf=="ok" && class_exists('Dompdf\Dompdf')){
	$dompdf = new Dompdf();
	$paper_size = array(0,0, 612,792);

	$dompdf->loadHtml($html);
	$dompdf->setPaper($paper_size);

	$dompdf->render();
	if (ob_get_length()) ob_end_clean();
	if (php_sapi_name() === 'cli') {
		echo $dompdf->output();
	} else {
		$dompdf->stream("Acta_".$fecha2.".pdf", array("Attachment" => false));
	}
} else {
	echo $html;
}
?>
