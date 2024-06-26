<?php

$id_alumno     = $_REQUEST['id_alumno'];
$id_profesor   = $_REQUEST['id_profesor'];
$id_curso      = $_REQUEST['id_curso'];
$id_evaluador  = $_REQUEST['id_evaluador'];
$arch_encuesta = $_REQUEST['arch_encuesta'];

if ($arch_encuesta == "") { echo(js("window.location='https://www.umcervantes.cl/';")); }

$ANO_Encuesta      = $_REQUEST['ano'];
$SEMESTRE_Encuesta = $_REQUEST['semestre'];

$SQL_cursos_actuales = "SELECT id FROM cursos WHERE ano=$ANO_Encuesta AND semestre=$SEMESTRE_Encuesta";

$aCampos = $aCampos_Req = array();

if ($id_alumno == "" && $id_curso == "" && $arch_encuesta == "estudiantil") {
	echo(msje_js("Faltan datos para llevar a cabo esta encuesta."));
	exit;
} elseif ($id_alumno <> "" && id_curso <> "" && $arch_encuesta == "estudiantil") {
	$SQL_alumno_curso = "SELECT vc.profesor,vc.carrera,
                                    vc.cod_asignatura||'-'||vc.seccion||' '||vc.asignatura AS asignatura
	                     FROM cargas_academicas AS ca
	                     LEFT JOIN vista_cursos AS vc ON vc.id=ca.id_curso
	                     WHERE ca.id_curso='$id_curso' AND ca.id_alumno='$id_alumno'
	                       AND ca.id_curso IN ($SQL_cursos_actuales);";
	$aIdentificacion = consulta_sql($SQL_alumno_curso);
	if (count($aIdentificacion) == 0) {
		echo(msje_js("Usted no pertenece a este curso."));
		exit;
	}
	$aCampos = array("id_alumno","id_curso");
}

if (($id_profesor == "" || $id_evaluador == "") && $arch_encuesta == "evaluacion_docente") {
	echo(msje_js("Faltan datos para llevar a cabo esta encuesta."));
	exit;
} elseif ($id_profesor <> "" && $id_evaluador <> "" && $arch_encuesta == "evaluacion_docente") {
	$SQL_profesor = "SELECT profesor FROM vista_cursos
	                 WHERE id IN ($SQL_cursos_actuales) and id_profesor='$id_profesor';";
	$aIdentificacion = consulta_sql($SQL_profesor);
	if (count($aIdentificacion) == 0) {
		echo(msje_js("Este profesor no tiene cursos vigentes en este periodo."));
		exit;
	} else {
		$SQL_evaluador = "SELECT nombre||' '||apellido AS evaluador FROM usuarios WHERE id='$id_evaluador' AND tipo IN (1,2)";
		$evaluador = consulta_sql($SQL_evaluador);
		if (count($evaluador) == 0) {
			echo(msje_js("Usted no es un evaluador."));
			exit;
		}
		$aIdentificacion[0]['evaluador'] = $evaluador[0]['evaluador'];
		$SQL_encuesta = "SELECT * FROM encuestas.evaluacion_docente WHERE id_evaluador=$id_evaluador AND id_profesor=$id_profesor;";
		$encuesta = consulta_sql($SQL_encuesta);
		if (count($encuesta) > 0) { $_REQUEST = array_merge($_REQUEST,$encuesta[0]); }
		$aCampos = array("id_profesor","id_evaluador");
		$id_encuesta = $encuesta[0]['id'];
	}
}

if ($id_profesor == "" && $arch_encuesta == "autoevaluacion_docente") {
	echo(msje_js("Faltan datos para llevar a cabo esta encuesta."));
	exit;
} elseif ($id_profesor <> "" && $arch_encuesta == "autoevaluacion_docente") {
	$SQL_profesor = "SELECT profesor,carrera FROM vista_cursos
	                 WHERE id IN ($SQL_cursos_actuales) and id_profesor='$id_profesor';";
	$aIdentificacion = consulta_sql($SQL_profesor);
	if (count($aIdentificacion) == 0) {
		echo(msje_js("Estimado Profesor(a), usted no tiene cursos vigentes en este periodo."));
		exit;
	}
	$aCampos = array("id_profesor");
	//$arch_encuesta .= "_extendida";
}

$periodo = "$SEMESTRE_Encuesta-$ANO_Encuesta";
$aIdentificacion[0] = array_merge($aIdentificacion[0],array("periodo"=>$periodo));

$HTML_identificacion = "";
foreach ($aIdentificacion[0] as $nombre_campo => $valor_campo) {
	$nombre_campo=ucfirst($nombre_campo);
	$HTML_identificacion .= "  <tr>".$LF
	                      . "    <td class='celdaNombreAttr'>$nombre_campo:</td>".$LF
	                      . "    <td class='celdaValorAttr'>$valor_campo</td>".$LF
	                      . "  </tr>".$LF;
}

//$encuesta        = file($arch_encuesta.".txt");
$encuesta        = file($arch_encuesta."_extendida".".txt");
//$instrucciones   = nl2br(file_get_contents("instrucciones_".$arch_encuesta.".txt"));
$instrucciones   = nl2br(file_get_contents("instrucciones_".$arch_encuesta."_extendida.txt"));
$titulo_encuesta = nl2br(file_get_contents("titulo_".$arch_encuesta.".txt"));
$HTML_encuesta   = "";

for ($x=0;$x<count($encuesta);$x++) {
	$linea_pregunta = explode("#",$encuesta[$x]);
	if (count($linea_pregunta) == 1) {
		$titulo = $linea_pregunta[0];
		$HTML_encuesta .= "  <tr class='filaTituloTabla'>".$LF
		                . "    <td colspan='2' align='center' class='tituloTabla'><b>$titulo</b></td>".$LF
		                . "  </tr>".$LF;
	} 
	if (count($linea_pregunta) > 1) {
	
		$nombre_pregunta=$linea_pregunta[0];
		$aCampos = array_merge($aCampos, array($nombre_pregunta));

		if (count($linea_pregunta) == 4) {
		
			$opciones  = "<textarea name='$nombre_pregunta'>" . $_REQUEST["$nombre_pregunta"] . "</textarea>";
			$requerida = $linea_pregunta[1];
			$pregunta  = $linea_pregunta[2];
			$HTML_encuesta .= "  <tr class='filaTabla'>".$LF
			                . "    <td class='textoTabla'>$pregunta</td>".$LF
			                . "    <td class='textoTabla'>$opciones</td>".$LF
			                . "  </tr>".$LF;
		} else {
		
			$opciones = array();
			for($y=3;$y<count($linea_pregunta);$y++) {
				$opciones = array_merge($opciones, array(array("id"=>$y-2,"nombre"=>trim($linea_pregunta[$y]))));
			}
			$requerida = $linea_pregunta[1];
			$pregunta  = $linea_pregunta[2];
			$HTML_encuesta .= "  <tr class='filaTabla'>".$LF
			                . "    <td class='textoTabla'><u>$pregunta</u></td>".$LF
			                . "    <td class='textoTabla'>".$LF
			                . "      <select name='$nombre_pregunta'>".$LF
			                . "        <option value=''>-- Seleccione --</option>".$LF
			                .          select($opciones,$_REQUEST["$nombre_pregunta"])
			                . "      </select>".$LF
			                . "    </td>".$LF
			                . "  </tr>".$LF;
			if ($requerida == "*") {
				$aCampos_Req = array_merge($aCampos_Req,array($nombre_pregunta));
			}
		}
		
	}
}

$requeridos = "'" . str_replace("," , "','" , implode("," , $aCampos_Req)) . "'";

if ($_REQUEST['enviar'] == "  -->  Terminar Encuesta  <--  ") {
	if ($_REQUEST['id'] <> "") {
		$id_encuesta = $_REQUEST['id'];
		$SQL_guardar_encuesta = "UPDATE encuestas.$arch_encuesta SET " . arr2sqlupdate($_REQUEST,$aCampos) . " WHERE id=$id_encuesta";
	} else {
		$SQL_guardar_encuesta = "INSERT INTO encuestas.$arch_encuesta " . arr2sqlinsert($_REQUEST,$aCampos);
	}
	//echo($SQL_guardar_encuesta);
	$encuesta  = consulta_dml($SQL_guardar_encuesta);
	if ($encuesta > 0) {
		echo(msje_js("Se ha recibido su encuesta contestada y los datos se almacenaron exitosamente\\n"
                            ."Gracias!"));

		switch ($arch_encuesta) {
			case "estudiantil":
				$redireccionar = "?modulo=encuestas_alumno&id_alumno=$id_alumno&ano=$ANO_Encuesta&semestre=$SEMESTRE_Encuesta";
				break;
			case "autoevaluacion_docente":
				//$redireccionar = "http://www.umcervantes.cl/";
				$redireccionar = "http://encuestas.umc.cl/limesurvey/index.php/311241?lang=es";
				break;
			case "evaluacion_docente":
				$redireccionar = "?modulo=encuestas_escuela&id_evaluador=$id_evaluador&ano=$ANO_Encuesta&semestre=$SEMESTRE_Encuesta";
				break;
		}

		echo(js("window.location='$redireccionar';"));
		exit;
	}
}
?>
<div align="center" class="tituloModulo">
  <?php echo($titulo_encuesta); ?>
</div>
<br>
<table cellpadding="2" cellspacing="1" border="0" bgcolor="#FFFFFF" class="tabla">
  <?php echo($HTML_identificacion); ?>
</table>
<br>
<div class="texto" align="justify">
  <?php echo($instrucciones); ?>
</div>
<br>
<form name="formulario" action="index.php" onSubmit="return enblanco2(<?php echo($requeridos); ?>);" method="post">
  <input type="hidden" name="modulo"        value="<?php echo($modulo); ?>">
  <input type="hidden" name="id_alumno"     value="<?php echo($id_alumno); ?>">
  <input type="hidden" name="id_curso"      value="<?php echo($id_curso); ?>">
  <input type="hidden" name="id_profesor"   value="<?php echo($id_profesor); ?>">
  <input type="hidden" name="id_evaluador"  value="<?php echo($id_evaluador); ?>">
  <input type="hidden" name="arch_encuesta" value="<?php echo($arch_encuesta); ?>">
  <input type="hidden" name="id"            value="<?php echo($id_encuesta); ?>">
  <input type="hidden" name="ano"           value="<?php echo($ANO_Encuesta); ?>">
  <input type="hidden" name="semestre"      value="<?php echo($SEMESTRE_Encuesta); ?>">
  <table cellpadding="2" cellspacing="1" class="tabla" align="center" bgcolor="#FFFFFF" width="95%">
    <?php echo($HTML_encuesta); ?>
  </table>
  <br>
  <div align="center">
    <input type="submit" name="enviar" value="  -->  Terminar Encuesta  <--  ">
  </div>
</form>
