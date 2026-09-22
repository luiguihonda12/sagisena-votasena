<?php
require_once("models/conexion.php");
require_once("models/mrcv.php");
require_once("models/votmrcv.php");

$votmrcv = new Votmrcv();

$fidjor = isset($_REQUEST['fidjor']) ? $_REQUEST['fidjor'] : NULL;
$fidfic = isset($_REQUEST['fidfic']) ? $_REQUEST['fidfic'] : NULL;

$jornadas = $votmrcv->getJor();
$fichas = $votmrcv->getFichas($fidjor);

$fichaActual = [];
$dat = [];
$aprendicesFicha = [];
$instructorActual = [];
$nombreJornada = "";
$nombreFicha = "";

if($fidfic) {
    $fichaDetalle = $votmrcv->getFichaDetalle($fidfic);
    if ($fichaDetalle) {
        $fichaActual = $fichaDetalle;
        $nombreJornada = strtoupper($fichaActual['nomjor']);
        $nombreFicha = strtoupper($fichaActual['nomfic']);
    } else {
        $fichaFiltro = array_filter($fichas, function($f) use ($fidfic) {
            return $f['idfic'] == $fidfic;
        });
        $fichaActual = !empty($fichaFiltro) ? current($fichaFiltro) : ['idfic' => $fidfic, 'nomfic' => 'PROGRAMA', 'nomval' => ''];
        $nombreJornada = isset($fichaActual['nomval']) ? strtoupper($fichaActual['nomval']) : "";
        $nombreFicha = isset($fichaActual['nomfic']) ? strtoupper($fichaActual['nomfic']) : "";
    }
    
    $dat = $votmrcv->getVocerosElectosPorFicha($fidfic);
    $aprendicesFicha = $votmrcv->getAprendicesPorFicha($fidfic);
    
    $instructorActual = [
        'nomins'  => isset($fichaActual['nomins']) && !empty($fichaActual['nomins']) ? $fichaActual['nomins'] : 'Instructor Lider / Delegado',
        'ndocins' => isset($fichaActual['ndocins']) ? $fichaActual['ndocins'] : '',
        'emains'  => isset($fichaActual['emains']) ? $fichaActual['emains'] : ''
    ];
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
$mesIndex = (int)date('m') - 1;
$mesNombre = $mes[$mesIndex];
$dia = date('d');
$fecha = "Chía, " . $dia . " de " . $mesNombre . " de " . date('Y');
$fecha2 = date('YmdHis');
$hora_fin = date('H:i') . ' Hrs';
$hora_inicio = date('H:i', strtotime('-30 minutes')) . ' Hrs';
$ancho = 750;
$año = date('Y');
$fcan = date('Y')+1;

// variables para identificar al ganador y calcular el total de los votos
$ganador = null;
$suplente = null;
$maxVotos = -1;
$totalVotosJornada = 0;

foreach ($dat as $k => $d) {
    $nvo = isset($d['total_votos']) ? (int)$d['total_votos'] : 0;
    $totalVotosJornada += $nvo;
    
    if ($k == 0) {
        $ganador = $d;
        $maxVotos = $nvo;
    } else if ($k == 1) {
        $suplente = $d;
    }
}

if ($fidfic) {
    require 'views/votractvoc.php';
    
    $pdf = isset($_GET['pdf']) ? $_GET['pdf'] : NULL;
    if($pdf == "ok") {
        if(file_exists('vendor/autoload.php')) {
            require_once('vendor/autoload.php');
        }
        if (class_exists('Dompdf\Dompdf')) {
            $dompdf = new Dompdf\Dompdf();
            $paper_size = array(0,0, 612,792);
            $dompdf->loadHtml($html);
            $dompdf->setPaper($paper_size);
            $dompdf->render();
            while (ob_get_level()) {
                ob_end_clean();
            }
            if (php_sapi_name() === 'cli') {
                echo $dompdf->output();
            } else {
                $dompdf->stream("Acta_Vocero_".$fecha2.".pdf", array("Attachment" => false));
            }
        } else {
            echo $html;
        }
    } else {
        echo $html;
    }
}
