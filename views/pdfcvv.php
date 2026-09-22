<?php
require_once '../models/conexion.php';
require_once '../models/votmcvh.php';

$idusu = isset($_GET['idusu']) ? $_GET['idusu'] : null;

date_default_timezone_set('America/Bogota');
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = date('d') . " de " . $mes[date('m') - 1] . " de " . date('Y');
$fecha2 = date('YmdHis');

$mcvh = new VotMcvh();
$mcvh->setIdusu($idusu);
$datOne = $mcvh->selOne();

function urlimg($url)
{
    $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
    return $imagenBase64;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 50px 30px;
        }
        .cert-content {
            max-width: 650px;
            margin: 0 auto;
        }
        img {
            width: 80px;
            max-width: 100%;
        }
        h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        p {
            font-size: 15px;
            line-height: 1.6;
        }
        .firma {
            margin-top: 50px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            text-align: left;
        }
        .firma > div {
            flex: 1 1 220px;
            font-size: 13px;
            line-height: 1.5;
        }
        .firma > div:last-child {
            text-align: right;
        }
        .verificacion {
            margin-top: 30px;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            body {
                padding: 30px 16px;
            }
            h2 {
                font-size: 17px;
            }
            p {
                font-size: 13px;
            }
            .firma {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }
            .firma > div {
                text-align: center !important;
                flex: 1 1 auto;
            }
        }

        @media print {
            @page {
                size: auto;
                margin: 0;
            }
            html, body {
                height: 100%;
            }
            body {
                padding: 25mm 15mm;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="cert-content">
        <img src="<?php echo urlimg('../image/sena.png'); ?>"><br><br>

        <h2>El Servicio Nacional de Aprendizaje SENA</h2>
        <p><strong>Hace constar que</strong></p>

        <p>
            <?php if($datOne) echo $datOne[0]['nomusu']; ?><br>
            Con CÉDULA DE CIUDADANÍA No.
            <?php if($datOne) echo $datOne[0]['ndocusu']; ?>
        </p>

        <p><strong>Participó en la Jornada electoral</strong><br><br>
        <strong>Votaciones SENA Vocero</strong><br><br>
        <?php
        if ($datOne && $datOne[0]['idfic']) {
            echo 'Ficha ' . $datOne[0]['idfic'] . '<br>';
            echo 'Jornada ' . $datOne[0]['nomval'];
        }
        ?>
        </p>

        <p><small>En testimonio de lo anterior se firma en Chía a la fecha <?php echo $fecha; ?></small></p>

        <div class="firma">
            <div>
                JAVIER RICARDO JIMÉNEZ RINCÓN<br>
                Subdirector
                <?php if($datOne) echo $datOne[0]['nomcen']; ?><br>
                REGIONAL CUNDINAMARCA
            </div>
            <div>
                <?php echo $fecha2; ?><br>
                No. Y FECHA DE REGISTRO
            </div>
        </div>
    </div>
</body>
</html>