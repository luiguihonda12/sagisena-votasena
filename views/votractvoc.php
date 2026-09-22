<?php
// =========================================================================================
// PLANTILLA OFICIAL ACTA DE ELECCIÓN DE VOCEROS Y SUPLENTES DE APRENDICES SENA
// Formatos Oficiales: GOR-F-084 V02 | GOR-F-085 V02 | GFPI-F-121 Versión 03
// Normativa: Guía GFPI-G-050 del 14 de febrero de 2025 | Ley 1581 de 2012
// Responsable Módulo: Yeison Stiven Molina Balceras (Cronograma SAGI)
// =========================================================================================

if (!function_exists('renderizarActaVocerosOficialSena')) {
    function renderizarActaVocerosOficialSena(array $cfg): string {
        $logoBase64 = isset($cfg['logo_base64']) ? $cfg['logo_base64'] : '';
        if (empty($logoBase64)) {
            $logoPath = 'img/sena_logo_oficial.png';
            if (!file_exists($logoPath)) {
                $logoPath = 'img/logo.png';
            }
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }
        }

        $ciudadFecha     = isset($cfg['ciudad_fecha']) ? $cfg['ciudad_fecha'] : ('Chía, ' . date('d/m/Y'));
        $dia             = isset($cfg['dia']) ? $cfg['dia'] : date('d');
        $mesNombre       = isset($cfg['mes_nombre']) ? $cfg['mes_nombre'] : date('F');
        $año             = isset($cfg['año']) ? $cfg['año'] : date('Y');
        $fcan            = isset($cfg['fcan']) ? $cfg['fcan'] : (date('Y') + 1);
        $lugar           = isset($cfg['lugar']) ? $cfg['lugar'] : 'Ambiente de formación y sede/subsede';
        $regionalCentro  = 'Dirección General / Regional Cundinamarca / Centro de Desarrollo Agroempresarial';

        // Ficha
        $ficha           = isset($cfg['ficha']) ? $cfg['ficha'] : [];
        $idfic           = isset($ficha['idfic']) ? $ficha['idfic'] : 'N/A';
        $nomfic          = isset($ficha['nomfic']) ? $ficha['nomfic'] : 'PROGRAMA NO DEFINIDO';
        $nomjor          = isset($ficha['nomjor']) ? $ficha['nomjor'] : (isset($ficha['nomval']) ? $ficha['nomval'] : 'N/A');

        // Vocero (1er puesto)
        $vocero          = isset($cfg['vocero']) ? $cfg['vocero'] : null;
        $nomVocero       = $vocero && !empty($vocero['nomusu']) ? $vocero['nomusu'] : '________________________________________';
        $tipoDocVocero   = $vocero && !empty($vocero['tipodoc']) ? $vocero['tipodoc'] : 'CC';
        $docVocero       = $vocero && !empty($vocero['ndocusu']) ? ($tipoDocVocero . ' ' . $vocero['ndocusu']) : '________________________';
        $telVocero       = $vocero && !empty($vocero['telcan']) ? $vocero['telcan'] : '________________________';
        $corVocero       = $vocero && !empty($vocero['emausu']) ? $vocero['emausu'] : '________________________';

        // Suplente (2do puesto)
        $suplente        = isset($cfg['suplente']) ? $cfg['suplente'] : null;
        $nomSuplente     = $suplente && !empty($suplente['nomusu']) ? $suplente['nomusu'] : '________________________________________';
        $tipoDocSuplente = $suplente && !empty($suplente['tipodoc']) ? $suplente['tipodoc'] : 'CC';
        $docSuplente     = $suplente && !empty($suplente['ndocusu']) ? ($tipoDocSuplente . ' ' . $suplente['ndocusu']) : '________________________';
        $telSuplente     = $suplente && !empty($suplente['telcan']) ? $suplente['telcan'] : '________________________';
        $corSuplente     = $suplente && !empty($suplente['emausu']) ? $suplente['emausu'] : '________________________';

        // Candidatos
        $candidatos      = isset($cfg['candidatos']) && is_array($cfg['candidatos']) ? $cfg['candidatos'] : [];

        // Instructor líder
        $instructor      = isset($cfg['instructor']) ? $cfg['instructor'] : [];
        $nomInstructor   = !empty($instructor['nomins']) ? $instructor['nomins'] : 'Instructor Lider / Delegado';
        $docInstructor   = !empty($instructor['ndocins']) ? $instructor['ndocins'] : '';
        $emaInstructor   = !empty($instructor['emains']) ? $instructor['emains'] : '';

        // Aprendices ficha
        $aprendices      = isset($cfg['aprendices']) && is_array($cfg['aprendices']) ? $cfg['aprendices'] : [];

        ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Acta Elección Voceros - SENA</title>
<style>
  @page {
    margin: 18px 25px 18px 25px;
    size: letter portrait;
  }
  body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10.5px;
    line-height: 1.32;
    color: #000;
    margin: 0;
    padding: 0;
    background: #fff;
  }
  .acta-page {
    width: 100%;
    min-height: 985px;
    box-sizing: border-box;
    page-break-after: always;
    position: relative;
  }
  .acta-page:last-child {
    page-break-after: avoid;
  }
  .header-logo {
    text-align: center;
    margin-bottom: 6px;
  }
  .header-logo img {
    width: 50px;
    height: auto;
  }
  .tbl-acta {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #000;
    margin-bottom: 8px;
  }
  .tbl-acta th, .tbl-acta td {
    border: 1px solid #000;
    padding: 4px 6px;
    vertical-align: top;
  }
  .border-box {
    border: 1px solid #000;
    padding: 10px 12px;
    text-align: justify;
    box-sizing: border-box;
    margin-bottom: 6px;
  }
  .footer-code {
    text-align: center;
    font-size: 10px;
    font-weight: normal;
    color: #000;
    margin-top: 6px;
  }
  .txt-center { text-align: center; }
  .txt-bold   { font-weight: bold; }
  .txt-justify{ text-align: justify; }
  .bg-gray    { background-color: #f2f2f2; }
  
  .box-tag {
    border: 1px solid #000;
    padding: 2px 6px;
    font-weight: bold;
    display: inline-block;
    margin-bottom: 4px;
    font-size: 10.5px;
  }
  .signature-cell {
    border: 1px solid #c00;
    color: #c00;
    font-size: 9.5px;
    font-weight: bold;
    text-align: center;
    padding: 8px 4px;
    margin: 2px 0;
  }
  .law-clause {
    font-size: 8.5px;
    line-height: 1.25;
    text-align: justify;
    margin: 6px 0;
  }
  .tbl-assist {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #000;
    font-size: 8px;
  }
  .tbl-assist th, .tbl-assist td {
    border: 1px solid #000;
    padding: 3px 2px;
    text-align: center;
  }
</style>
</head>
<body>

<!-- ========================================================================== -->
<!-- PÁGINA 1: GOR-F-084 V02 (Encabezado institucional y Normativa Art. 8 y 8.1) -->
<!-- ========================================================================== -->
<div class="acta-page">
  <div class="header-logo">
    <?php if ($logoBase64): ?><img src="<?= $logoBase64 ?>" alt="SENA"><?php endif; ?>
  </div>

  <table class="tbl-acta">
    <tr>
      <td colspan="4" class="txt-center txt-bold" style="padding: 5px; font-size: 11.5px;">ACTA No.</td>
    </tr>
    <tr>
      <td colspan="4" style="padding: 6px;">
        <span class="txt-bold">NOMBRE DEL COMITÉ O DE LA REUNIÓN:</span><br>
        <div class="txt-center txt-bold" style="margin-top: 3px; font-size: 11px;">
          Elección de voceros y Suplentes de aprendices
        </div>
      </td>
    </tr>
    <tr>
      <td style="width: 44%;"><span class="txt-bold">CIUDAD Y FECHA:</span> <?= htmlspecialchars($ciudadFecha) ?></td>
      <td style="width: 28%;"><span class="txt-bold">HORA INICIO:</span></td>
      <td colspan="2" style="width: 28%;"><span class="txt-bold">HORA FIN:</span></td>
    </tr>
    <tr>
      <td><span class="txt-bold">LUGAR Y/O ENLACE:</span> <?= htmlspecialchars($lugar) ?></td>
      <td colspan="3"><span class="txt-bold">DIRECCIÓN / REGIONAL / CENTRO:</span><br><?= $regionalCentro ?></td>
    </tr>
    <tr>
      <td colspan="4" style="padding: 6px;">
        <span class="txt-bold">AGENDA O PUNTOS PARA DESARROLLAR:</span>
        <ol style="margin: 3px 0 3px 20px; padding: 0;">
          <li>Socialización requisitos del vocero</li>
          <li>Elección democrática de vocero y suplente</li>
          <li>Firma del acta</li>
        </ol>
      </td>
    </tr>
    <tr>
      <td colspan="4" style="padding: 6px;">
        <span class="txt-bold">OBJETIVO(S) DE LA REUNIÓN:</span>
        <p style="margin: 3px 0; text-align: justify;">
          Generar escenarios de reconocimiento para aprendices en ejes de liderazgo, proyección social y formación de talentos. Adelantar la elección de voceros y representantes de aprendices.
        </p>
      </td>
    </tr>
    <tr>
      <td colspan="4" class="txt-center txt-bold bg-gray" style="padding: 5px; font-size: 11px;">
        DESARROLLO DE LA REUNIÓN
      </td>
    </tr>
    <tr>
      <td colspan="4" style="padding: 8px; text-align: justify;">
        Esta elección se realizará en el marco de la guía para la elección de representantes y voceros de aprendices SENA GFPI -G-050 del 14 de febrero del año 2025, teniendo en cuenta los siguientes artículos:<br><br>
        <strong>8. Elección de voceros de grupo</strong><br><br>
        <strong>8.1 Requisitos para ser voceros de grupo</strong><br>
        El aprendiz que desee postularse deberá cumplir con los siguientes requisitos:<br><br>
        <strong>A.</strong> Estar activo en su programa de formación en etapa lectiva (estado de inducción o de formación) y debe pertenecer al grupo al que represente.
      </td>
    </tr>
  </table>

  <div class="footer-code">GOR-F-084 V02</div>
</div>

<!-- ========================================================================== -->
<!-- PÁGINA 2: GOR-F-084V02 (Continuación Art. 8.1, 8.2, 8.3 y 8.4 A, B)        -->
<!-- ========================================================================== -->
<div class="acta-page">
  <div class="header-logo">
    <?php if ($logoBase64): ?><img src="<?= $logoBase64 ?>" alt="SENA"><?php endif; ?>
  </div>

  <div class="border-box" style="min-height: 870px;">
    <strong>B.</strong> Postularse o dar su consentimiento para ser postulado, por los aprendices del mismo grupo de programa de formación<br><br>
    <strong>C.</strong> Tener disponibilidad, cuando se requiera, para trabajar en equipo con el representante de centro de formación profesional integral (según modalidad y jornada) y con los demás integrantes de la comunidad educativa, en la medida que pueda dar cumplimiento a sus obligaciones académicas en su jornada de formación<br><br>
    <strong>8.2 Procedimiento para la elección de vocero de grupo</strong><br>
    La elección de los voceros se realizará de la siguiente forma:<br><br>
    <strong>A.</strong> La elección se realizará dentro de los quince (15) días calendario siguientes a la fecha de inicio del programa de formación.<br>
    <strong>B.</strong> El proceso debe estar acompañado por el instructor del grupo o el instructor delegado.<br>
    <strong>C.</strong> Esta elección se realizará en el ambiente de aprendizaje, se postularán los aprendices que deseen ser candidatos y se realizará la votación por los candidatos, no se puede votar por el mismo candidato dos veces.<br>
    <strong>D.</strong> Será elegido, vocero principal el aprendiz candidato que cuente con mayoría de votos y el segundo con la mayor votación será el vocero suplente, en caso de empate se definirá por sorteo.<br>
    <strong>E.</strong> Las evidencias que sustentan la elección de voceros se gestionarán de acuerdo con lineamientos emitidos por el comité electoral del centro de formación.<br><br>
    <strong>8.3 Periodo de representación Vocero de grupo</strong><br>
    El periodo de representación de los aprendices voceros de grupo electos, será hasta la finalización de su etapa lectiva.<br><br>
    <strong>8.4 Responsabilidad de los voceros de grupo</strong><br>
    Los voceros de grupos de aprendices tendrán las siguientes responsabilidades:<br><br>
    <strong>A.</strong> Participar en las estrategias de fortalecimiento de liderazgo que se convoquen desde el centro de formación profesional integral o regional.<br>
    <strong>B.</strong> Promover la participación de los aprendices en las diferentes actividades que aborden en su proceso formativo, así como en las que programe el centro de formación
  </div>

  <div class="footer-code">GOR-F-084V02</div>
</div>

<!-- ========================================================================== -->
<!-- PÁGINA 3: GOR-F-084 V02 (Continuación Art. 8.4 C a H, 8.5 y 8.6 A)          -->
<!-- ========================================================================== -->
<div class="acta-page">
  <div class="header-logo">
    <?php if ($logoBase64): ?><img src="<?= $logoBase64 ?>" alt="SENA"><?php endif; ?>
  </div>

  <div class="border-box" style="min-height: 870px;">
    profesional integral para el desarrollo del Plan Nacional Integral de Bienestar al Aprendiz.<br>
    <strong>C.</strong> Asistir y participar activamente, cada vez que sea citado, en los comités de evaluación y seguimiento, acompañamiento formativo, o disciplinario de su grupo.<br>
    <strong>D.</strong> Participar con los demás voceros y el (los) representante (s) del centro de formación profesional integral, en la formulación y realización de actividades, servicios y proyectos que beneficie a los aprendices que representa.<br>
    <strong>E.</strong> Invitar a los integrantes de su grupo, para el proceso formativo se desarrolle de forma armónica y de diálogo directo con los actores vinculados a la formación.<br>
    <strong>F.</strong> Actuar como canal de comunicación e interlocutor entre los estamentos de la Comunidad Educativa SENA y los aprendices de su grupo.<br>
    <strong>G.</strong> Ser parte activa y garante del proceso electoral de aprendices representantes y los voceros de enfoque diferencial<br>
    <strong>H.</strong> Estar atento a sus compañeros y alertar si alguno se encuentra en riesgo de deserción activar la ruta con el instructor o con el coordinador misional para que designe a algún profesional que tenga funciones del PNIBA.<br><br>
    La participación como vocero de grupo no lo exime de las responsabilidades de su formación profesional integral según rutas de aprendizaje.<br><br>
    <strong>8.5 Revocatoria de la designación de los voceros de grupo</strong><br><br>
    Los voceros de grupo serán relevados de la representación por sus compañeros de grupo, en caso de incumplimiento de sus responsabilidades, por falta(s) académica(s) o disciplinaria(s) contempladas en el reglamento del aprendiz acuerdo 009 de 2024, por postularse como candidato a representante de los aprendices por el centro de formación profesional integral, por renuncia voluntaria o por decisión mayoritaria del grupo que representa.<br><br>
    <strong>8.6 Procedimiento para la revocatoria del vocero de grupo</strong><br><br>
    Los voceros de grupo podrán ser revocados de su responsabilidad a través del mismo mecanismo por el que fueron elegidos y el procedimiento que se atenderá será el siguiente:<br><br>
    <strong>A.</strong> La revocatoria podrá ser solicitada en el centro de Formación, en un formato específico, para dicho fin es necesario presentar:<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&bull; Un documento con la motivación sustentada.
  </div>

  <div class="footer-code">GOR-F-084 V02</div>
</div>

<!-- ========================================================================== -->
<!-- PÁGINA 4: GOR-F-084V02 (Continuación 8.6, 8.7 y Tabla APRENDICES CANDIDATOS) -->
<!-- ========================================================================== -->
<div class="acta-page">
  <div class="header-logo">
    <?php if ($logoBase64): ?><img src="<?= $logoBase64 ?>" alt="SENA"><?php endif; ?>
  </div>

  <div class="border-box" style="min-height: 870px;">
    &nbsp;&nbsp;&nbsp;&nbsp;&bull; Las firmas de los aprendices del grupo, que se deberán ser superiores o iguales a la mitad más uno de los votos obtenidos en las elecciones por el vocero que se pretende revocar.<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&bull; El comité electoral del centro de formación, en máximo tres (3) días hábiles, después de que se presente la revocatoria deberá aprobar o rechazar la solicitud de proceso de revocatoria.<br><br>
    <strong>B.</strong> Una vez agotado lo anterior, el Centro de formación, notificará a la persona cuya representación pretende revocarse y a quienes realizaron la solicitud, en un plazo de dos (2) días hábiles. Asumirá el suplente.<br>
    <strong>C.</strong> Si la mitad más uno de los votantes elige “Si” a la pregunta de si desea revocar el vocero, la revocatoria operará y quedará como vocero el suplente.<br><br>
    En cualquier caso, la cantidad de votos positivos o negativos al respecto de la revocatoria no podrá ser inferior al 50% de los votos totales con los que el representante ganó en las elecciones generales.<br><br>
    Si la revocatoria prospera, se procederá a remover el vocero de su cargo mediante acta en donde quede la constancia de lo sucedido.<br><br>
    <strong>8.7 Suplencia de vocero de grupo</strong><br><br>
    En caso de que se requiera suplir o remover al vocero de grupo el vocero suplente asumirá las responsabilidades del vocero principal. La suplencia durará el tiempo que le faltaba al vocero principal para concluir el periodo. Si no hay vocero suplente, se desarrollará una nueva votación con los aprendices del grupo para elegir nuevo vocero y suplente de la misma forma que se realizó inicialmente.<br><br>

    <div class="txt-center txt-bold" style="font-size: 11px; margin: 10px 0 6px 0;">
      APRENDICES CANDIDATOS
    </div>

    <table class="tbl-acta" style="margin-bottom: 0;">
      <tr class="bg-gray txt-bold txt-center">
        <td style="width: 6%;">No.</td>
        <td style="width: 18%;">TIPO DE DOCUMENTO</td>
        <td style="width: 22%;">No. DOCUMENTO</td>
        <td style="width: 38%;">NOMBRES Y APELLIDOS</td>
        <td style="width: 16%;">NÚMERO DE VOTOS</td>
      </tr>
      <?php
      for ($i = 1; $i <= 5; $i++):
          $idx = $i - 1;
          $c = isset($candidatos[$idx]) ? $candidatos[$idx] : null;
          $td = $c && !empty($c['tipodoc']) ? $c['tipodoc'] : '';
          $nd = $c && !empty($c['ndocusu']) ? $c['ndocusu'] : '';
          $nom = $c && !empty($c['nomusu']) ? $c['nomusu'] : '';
      ?>
      <tr>
        <td class="txt-center txt-bold"><?= $i ?>.</td>
        <td class="txt-center"><?= htmlspecialchars($td) ?></td>
        <td class="txt-center"><?= htmlspecialchars($nd) ?></td>
        <td><?= htmlspecialchars($nom) ?></td>
        <td class="txt-center"></td>
      </tr>
      <?php endfor; ?>
    </table>
  </div>

  <div class="footer-code">GOR-F-084V02</div>
</div>

<!-- ========================================================================== -->
<!-- PÁGINA 5: GOR-F-084 V02 (Datos Vocero y Suplente, Conclusiones y Compromisos)-->
<!-- ========================================================================== -->
<div class="acta-page">
  <div class="header-logo">
    <?php if ($logoBase64): ?><img src="<?= $logoBase64 ?>" alt="SENA"><?php endif; ?>
  </div>

  <div class="border-box" style="min-height: 870px;">
    <div class="box-tag">VOCERO:</div><br>
    Los aprendices suscritos mediante la presente acta hemos elegido vocero de esta formación al Aprendiz.<br><br>
    <table style="width: 100%; font-size: 10.5px; border-collapse: collapse; margin-bottom: 8px;">
      <tr><td style="width: 38%; padding: 2px 0;"><strong>Nombre Completo del aprendiz:</strong></td><td><?= htmlspecialchars($nomVocero) ?></td></tr>
      <tr><td style="padding: 2px 0;"><strong>Tipo y Número de documento:</strong></td><td><?= htmlspecialchars($docVocero) ?></td></tr>
      <tr><td style="padding: 2px 0;"><strong>Teléfono:</strong></td><td><?= htmlspecialchars($telVocero) ?></td></tr>
      <tr><td style="padding: 2px 0;"><strong>Correo:</strong></td><td><?= htmlspecialchars($corVocero) ?></td></tr>
      <tr><td style="padding: 2px 0;"><strong>Programa de formación:</strong></td><td><?= htmlspecialchars($nomfic) ?></td></tr>
      <tr><td style="padding: 2px 0;"><strong>N. de Ficha:</strong></td><td><?= htmlspecialchars($idfic) ?></td></tr>
    </table>

    <div class="box-tag">VOCERO SUPLENTE:</div><br>
    Los aprendices suscritos mediante la presente acta hemos elegido vocero suplente de esta formación al Aprendiz.<br><br>
    <table style="width: 100%; font-size: 10.5px; border-collapse: collapse; margin-bottom: 8px;">
      <tr><td style="width: 38%; padding: 2px 0;"><strong>Nombre Completo del aprendiz:</strong></td><td><?= htmlspecialchars($nomSuplente) ?></td></tr>
      <tr><td style="padding: 2px 0;"><strong>Tipo y Número de documento:</strong></td><td><?= htmlspecialchars($docSuplente) ?></td></tr>
      <tr><td style="padding: 2px 0;"><strong>Teléfono:</strong></td><td><?= htmlspecialchars($telSuplente) ?></td></tr>
      <tr><td style="padding: 2px 0;"><strong>Correo:</strong></td><td><?= htmlspecialchars($corSuplente) ?></td></tr>
      <tr><td style="padding: 2px 0;"><strong>Programa de formación:</strong></td><td><?= htmlspecialchars($nomfic) ?></td></tr>
      <tr><td style="padding: 2px 0;"><strong>N. de Ficha:</strong></td><td><?= htmlspecialchars($idfic) ?></td></tr>
    </table>

    <p style="margin: 4px 0;"><strong>Aprendices Electores:</strong> Se anexa a la presente acta el listado de asistentes a esta elección.</p>

    <div class="txt-center txt-bold bg-gray" style="border: 1px solid #000; padding: 4px; margin: 8px 0 4px 0;">
      CONCLUSIONES
    </div>
    <p style="margin: 4px 0 10px 0;">
      Se obtiene como resultado la elección del vocero y suplente del programa de formación.
    </p>

    <div class="txt-center txt-bold bg-gray" style="border: 1px solid #000; padding: 4px; margin-bottom: 0;">
      ESTABLECIMIENTO Y ACEPTACIÓN DE COMPROMISOS
    </div>
    <table class="tbl-acta" style="margin-bottom: 0;">
      <tr class="bg-gray txt-bold txt-center">
        <td style="width: 44%;">ACTIVIDAD /DECISIÓN</td>
        <td style="width: 14%;">FECHA</td>
        <td style="width: 22%;">RESPONSABLE</td>
        <td style="width: 20%;">FIRMA O PARTICIPACIÓN VIRTUAL</td>
      </tr>
      <tr>
        <td style="font-size: 10px;">
          Participar en las estrategias de fortalecimiento de liderazgo que se convoquen desde el centro de formación profesional integral o regional.
        </td>
        <td class="txt-center" style="font-size: 10px;">Vigencia<br>de la ficha</td>
        <td class="txt-center" style="font-size: 10px;">Aprendices Vocero<br>y suplente</td>
        <td>
          <div class="signature-cell">FIRMA<br>VOCERO</div>
          <div class="signature-cell">FIRMA<br>SUPLENTE</div>
        </td>
      </tr>
      <tr>
        <td style="font-size: 10px;">
          Participar con los demás voceros y el (los) representante (s) del centro de formación profesional integral,
        </td>
        <td class="txt-center" style="font-size: 10px;">Vigencia<br>de la ficha</td>
        <td class="txt-center" style="font-size: 10px;">Aprendices Vocero<br>y suplente</td>
        <td>
          <div class="signature-cell">FIRMA<br>VOCERO</div>
        </td>
      </tr>
    </table>
  </div>

  <div class="footer-code">GOR-F-084 V02</div>
</div>

<!-- ========================================================================== -->
<!-- PÁGINA 6: GOR-F-084V02 (Compromisos cont., Asistentes Aprobación y Anexos)  -->
<!-- ========================================================================== -->
<div class="acta-page">
  <div class="header-logo">
    <?php if ($logoBase64): ?><img src="<?= $logoBase64 ?>" alt="SENA"><?php endif; ?>
  </div>

  <div class="border-box" style="min-height: 870px;">
    <table class="tbl-acta" style="margin-bottom: 8px;">
      <tr>
        <td style="width: 44%; font-size: 10px;">
          en la formulación y realización de actividades, servicios y proyectos que beneficie a los aprendices que representa.
        </td>
        <td style="width: 14%;" class="txt-center">-</td>
        <td style="width: 22%;" class="txt-center">-</td>
        <td style="width: 20%;">
          <div class="signature-cell">FIRMA<br>SUPLENTE</div>
        </td>
      </tr>
      <tr>
        <td style="font-size: 10px;">
          Invitar a los integrantes de su grupo, para el proceso formativo se desarrolle de forma armónica y de diálogo directo con los actores vinculados a la formación.
        </td>
        <td class="txt-center">-</td>
        <td class="txt-center" style="font-size: 10px;">Aprendices Vocero<br>y suplente</td>
        <td>
          <div class="signature-cell">FIRMA<br>VOCERO</div>
          <div class="signature-cell">FIRMA<br>SUPLENTE</div>
        </td>
      </tr>
    </table>

    <div class="txt-center txt-bold bg-gray" style="border: 1px solid #000; padding: 4px; margin-bottom: 0;">
      DE: ASISTENTES Y APROBACIÓN DECISIONES
    </div>
    <table class="tbl-acta" style="margin-bottom: 6px;">
      <tr class="bg-gray txt-bold txt-center">
        <td style="width: 32%;">NOMBRE</td>
        <td style="width: 28%;">DEPENDENCIA/ EMPRESA</td>
        <td style="width: 12%;">APRUEBA (SI/NO)</td>
        <td style="width: 14%;">OBSERVACIÓN</td>
        <td style="width: 14%;">FIRMA O PARTICIPACIÓN VIRTUAL</td>
      </tr>
      <tr>
        <td style="height: 38px;">
          <strong><?= htmlspecialchars($nomInstructor) ?></strong><br>
          <span style="font-size: 9px; color: #555;">Instructor</span>
        </td>
        <td style="font-size: 9.5px;">Centro de Desarrollo Agroempresarial</td>
        <td class="txt-center">SI</td>
        <td style="font-size: 9px;">Acompañamiento</td>
        <td></td>
      </tr>
      <tr>
        <td style="height: 38px;">
          <strong>Profesional de bienestar</strong>
        </td>
        <td style="font-size: 9.5px;">Bienestar al Aprendiz</td>
        <td class="txt-center">SI</td>
        <td style="font-size: 9px;">Garante electoral</td>
        <td></td>
      </tr>
      <tr>
        <td style="height: 26px;"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td style="height: 26px;"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>

    <p class="law-clause">
      De acuerdo con La Ley 1581 de 2012, Protección de Datos Personales, el Servicio Nacional de Aprendizaje SENA, se compromete a garantizar la seguridad y protección de los datos personales que se encuentran almacenados en este documento, y les dará el tratamiento correspondiente en cumplimiento de lo establecido legalmente.
    </p>

    <div class="txt-center txt-bold bg-gray" style="border: 1px solid #000; padding: 4px;">
      ANEXOS
    </div>
    <div style="border: 1px solid #000; border-top: none; height: 180px; padding: 6px; font-size: 9.5px; color: #333;">
      • Formato GOR-F-085 V02: Registro de Asistencia Oficial (Instructor y Profesional de Bienestar).<br>
      • Formato GFPI-F-121 Versión 03: Control de Participación de Actividades de Bienestar del Aprendiz (Aprendices Electores Ficha <?= htmlspecialchars($idfic) ?>).
    </div>
  </div>

  <div class="footer-code">GOR-F-084V02</div>
</div>

<!-- ========================================================================== -->
<!-- PÁGINA 7: GOR-F-085 V02 (Registro de Asistencia Instructor y Bienestar)     -->
<!-- ========================================================================== -->
<div class="acta-page">
  <div class="header-logo">
    <?php if ($logoBase64): ?><img src="<?= $logoBase64 ?>" alt="SENA"><?php endif; ?>
  </div>

  <div class="border-box" style="min-height: 870px;">
    <div class="txt-center txt-bold" style="font-size: 11px; margin-bottom: 6px;">
      REGISTRO DE ASISTENCIA / DÍA <?= htmlspecialchars($dia) ?> DEL MES DE <?= htmlspecialchars(strtoupper($mesNombre)) ?> DEL AÑO <?= htmlspecialchars($año) ?>
    </div>

    <table class="tbl-acta" style="margin-bottom: 6px;">
      <tr>
        <td style="width: 14%;" class="txt-bold bg-gray">OBJETIVO (S)</td>
        <td style="font-size: 9px; text-align: justify;">
          Generar escenarios de reconocimiento para aprendices en ejes de liderazgo, proyección social y formación de talentos. Adelantar la elección de voceros y representantes de aprendices - Actividad elección de voceros y suplentes de aprendices
        </td>
      </tr>
    </table>

    <table class="tbl-assist">
      <tr class="bg-gray txt-bold">
        <td style="width: 3%;">No</td>
        <td style="width: 22%;">NOMBRES Y APELLIDOS</td>
        <td style="width: 13%;">No. DOCUMENTO</td>
        <td style="width: 5%;">PLANTA</td>
        <td style="width: 7%;">CONTRATISTA</td>
        <td style="width: 5%;">OTRO ¿CUAL?</td>
        <td style="width: 17%;">DEPENDENCIA / EMPRESA</td>
        <td style="width: 15%;">CORREO ELECTRÓNICO</td>
        <td style="width: 8%;">TELÉFONO / EXT.</td>
        <td style="width: 5%;">AUTORIZA GRABACIÓN</td>
      </tr>
      <tr>
        <td class="txt-bold">1</td>
        <td style="text-align: left; padding-left: 4px;"><?= htmlspecialchars($nomInstructor) ?></td>
        <td><?= htmlspecialchars($docInstructor) ?></td>
        <td></td>
        <td class="txt-bold">X</td>
        <td></td>
        <td>Centro Desarrollo Agroempresarial</td>
        <td style="font-size: 7.5px;"><?= htmlspecialchars($emaInstructor) ?></td>
        <td></td>
        <td class="txt-bold">SI</td>
      </tr>
      <tr>
        <td class="txt-bold">2</td>
        <td style="text-align: left; padding-left: 4px;">Profesional de bienestar</td>
        <td></td>
        <td></td>
        <td class="txt-bold">X</td>
        <td></td>
        <td>Bienestar al Aprendiz</td>
        <td></td>
        <td></td>
        <td class="txt-bold">SI</td>
      </tr>
      <?php for ($k = 3; $k <= 18; $k++): ?>
      <tr>
        <td class="txt-bold"><?= $k ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <?php endfor; ?>
    </table>

    <p class="law-clause">
      De acuerdo con La Ley 1581 de 2012, Protección de Datos Personales, el Servicio Nacional de Aprendizaje SENA, se compromete a garantizar la seguridad y protección de los datos personales que se encuentran almacenados en este documento, y les dará el tratamiento correspondiente en cumplimiento de lo establecido legalmente.
    </p>
  </div>

  <div class="footer-code">GOR-F-085 V02</div>
</div>

<!-- ========================================================================== -->
<!-- PÁGINA 8: GFPI-F-121 Versión 03 (Formato Control Participación Aprendices)  -->
<!-- ========================================================================== -->
<div class="acta-page">
  <table style="width: 100%; border-collapse: collapse; margin-bottom: 4px;">
    <tr>
      <td style="width: 15%; text-align: left; vertical-align: middle;">
        <?php if ($logoBase64): ?><img src="<?= $logoBase64 ?>" style="width: 45px; height: auto;" alt="SENA"><?php endif; ?>
      </td>
      <td style="width: 70%; text-align: center; vertical-align: middle;">
        <span style="font-size: 10px; font-weight: bold;">PROCESO GESTIÓN DE FORMACIÓN PROFESIONAL INTEGRAL</span><br>
        <span style="font-size: 10.5px; font-weight: bold;">FORMATO CONTROL PARTICIPACIÓN ACTIVIDADES DE BIENESTAR DEL APRENDIZ</span>
      </td>
      <td style="width: 15%; text-align: right; font-size: 8.5px; border: 1px solid #000; padding: 3px; vertical-align: middle;">
        <strong>Versión:</strong> 03<br>
        <strong>Código:</strong> GFPI-F-121
      </td>
    </tr>
  </table>

  <div class="border-box" style="padding: 6px; font-size: 8.5px; line-height: 1.25; min-height: 870px;">
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px; font-size: 8.5px;">
      <tr><td style="width: 18%;" class="txt-bold">CIUDAD Y FECHA:</td><td><?= htmlspecialchars($ciudadFecha) ?></td></tr>
      <tr><td class="txt-bold">REGIONAL Y CENTRO:</td><td>Regional Cundinamarca - Centro de Desarrollo Agroempresarial</td></tr>
      <tr><td class="txt-bold">OBJETIVO ESTRATÉGICO:</td><td>Reconocer al aprendiz en su proceso de formación profesional integral mediante la implementación de un programa de estímulos que se materializa en los objetivos operativos descritos.</td></tr>
      <tr><td class="txt-bold">OBJETIVO OPERATIVO:</td><td>Generar escenarios de reconocimiento para aprendices en ejes de liderazgo, proyección social, formación y de talentos.</td></tr>
      <tr><td class="txt-bold">ACTIVIDAD A REALIZAR:</td><td>Actividades de elección de voceros y Suplentes de aprendices.</td></tr>
      <tr><td class="txt-bold">PROPÓSITO:</td><td>Adelantar la elección de voceros y representantes de aprendices.</td></tr>
    </table>

    <table class="tbl-assist" style="font-size: 7.5px;">
      <tr class="bg-gray txt-bold">
        <td style="width: 2%;">No</td>
        <td style="width: 18%;">NOMBRE Y APELLIDO DEL APRENDIZ</td>
        <td style="width: 6%;">TIPO DOC</td>
        <td style="width: 10%;"># IDENTIFICACIÓN</td>
        <td style="width: 6%;">GÉNERO</td>
        <td style="width: 8%;">FICHA</td>
        <td style="width: 18%;">PROGRAMA DE FORMACIÓN</td>
        <td style="width: 8%;">JORNADA</td>
        <td style="width: 16%;">CORREO ELECTRÓNICO</td>
      </tr>
      <?php
      $cantAprendices = count($aprendices);
      $filasTotal = max($cantAprendices, 22);
      for ($m = 1; $m <= $filasTotal; $m++):
          $a = isset($aprendices[$m - 1]) ? $aprendices[$m - 1] : null;
          $nomAp = $a ? $a['nomusu'] : '';
          $tdAp  = $a ? $a['tipodoc'] : '';
          $docAp = $a ? $a['ndocusu'] : '';
          $genAp = $a ? $a['genero'] : '';
          $ficAp = $a ? $a['idfic'] : ($m <= 5 ? $idfic : '');
          $proAp = $a ? $a['nomfic'] : ($m <= 5 ? $nomfic : '');
          $jorAp = $a ? $a['jornada'] : ($m <= 5 ? $nomjor : '');
          $emaAp = $a ? $a['emausu'] : '';
      ?>
      <tr>
        <td class="txt-bold"><?= $m ?></td>
        <td style="text-align: left; padding-left: 2px;"><?= htmlspecialchars($nomAp) ?></td>
        <td><?= htmlspecialchars($tdAp) ?></td>
        <td><?= htmlspecialchars($docAp) ?></td>
        <td><?= htmlspecialchars($genAp) ?></td>
        <td><?= htmlspecialchars($ficAp) ?></td>
        <td style="text-align: left; font-size: 6.5px;"><?= htmlspecialchars($proAp) ?></td>
        <td><?= htmlspecialchars($jorAp) ?></td>
        <td style="font-size: 6.5px;"><?= htmlspecialchars($emaAp) ?></td>
      </tr>
      <?php endfor; ?>
    </table>
  </div>

  <div class="footer-code" style="text-align: right; font-size: 8px; margin-top: 2px;">GFPI-F-121</div>
</div>

</body>
</html>
<?php
        return ob_get_clean();
    }
}

// Configuración por defecto o generada desde el controlador
$mesesCol = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$mesIndex = (int)date('m') - 1;
$mesTxt = isset($mesesCol[$mesIndex]) ? $mesesCol[$mesIndex] : date('F');

$cfgVoceroOficial = [
    'ciudad_fecha'   => 'Chía, ' . date('d') . ' de ' . $mesTxt . ' de ' . date('Y'),
    'dia'            => date('d'),
    'mes_nombre'     => $mesTxt,
    'año'            => isset($año) ? $año : date('Y'),
    'fcan'           => isset($fcan) ? $fcan : (date('Y') + 1),
    'lugar'          => 'Ambiente de formación y sede/subsede',
    'ficha'          => [
        'idfic'      => isset($fidfic) ? $fidfic : (isset($fichaActual['idfic']) ? $fichaActual['idfic'] : ''),
        'nomfic'     => isset($nombreFicha) ? $nombreFicha : (isset($fichaActual['nomfic']) ? $fichaActual['nomfic'] : ''),
        'nomjor'     => isset($nombreJornada) ? $nombreJornada : (isset($fichaActual['nomval']) ? $fichaActual['nomval'] : '')
    ],
    'vocero'         => isset($ganador) ? $ganador : null,
    'suplente'       => isset($suplente) ? $suplente : null,
    'candidatos'     => isset($dat) ? $dat : [],
    'instructor'     => isset($instructorActual) ? $instructorActual : [
        'nomins'     => isset($fichaActual['nomins']) ? $fichaActual['nomins'] : '',
        'ndocins'    => isset($fichaActual['ndocins']) ? $fichaActual['ndocins'] : '',
        'emains'     => isset($fichaActual['emains']) ? $fichaActual['emains'] : ''
    ],
    'aprendices'     => isset($aprendicesFicha) ? $aprendicesFicha : []
];

$html = renderizarActaVocerosOficialSena($cfgVoceroOficial);
