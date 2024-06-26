<?php

if (!$_SESSION['autentificado']) {
	header("Location: index.php");
	exit;
}

include("validar_modulo.php");

$id_carrera   = $_REQUEST['id_carrera'];
$id_curso     = $_REQUEST['id_curso'];
$id_alumno    = $_REQUEST['id_alumno'];
$calificacion = $_REQUEST['calificacion'];

$ids_carreras = $_SESSION['ids_carreras'];

if($_REQUEST['anular'] == "Anular" && strpos($_REQUEST['calificaciones_disponibles'],$calificacion) > 0) {
	$SQL_update = "UPDATE cargas_academicas SET $calificacion=null,nota_final=null,id_estado=null
	               WHERE id_curso='$id_curso' AND id_alumno='$id_alumno';";
	$anulacion = consulta_dml($SQL_update);
	if ($anulacion > 0) {
		echo(msje_js("Se ha anulado la calificación para el alumno(a) y curso seleccionado.\\n"
		            ."Debe reingresar la nota de este alumno en este curso.\\n"
		            ."Luego se debe recalcular el promedio de notas si corresponde, "
		            ."ya que se ha anulado el que hubiera tenido actualmente."));
		echo(js("window.location='$enlbase=ver_curso&id_curso=$id_curso';"));
		exit;
	}
}

$SQL_carreras = "SELECT id,nombre FROM carreras";
if ($ids_carreras <> "") { $SQL_carreras .= " WHERE id IN ($ids_carreras)"; }
$SQL_carreras .= " ORDER BY nombre;";

$carreras = consulta_sql($SQL_carreras);

$ano_cursos      = $ANO;
$semestre_cursos = $SEMESTRE;

if ($id_carrera <> "") {

	if ($_SESSION['id_tipo'] == 0 && $_REQUEST['ano_cursos'] <> "" &&  $_REQUEST['semestre_cursos'] <> "") {
		$ano_cursos      = $_REQUEST['ano_cursos']; 
		$semestre_cursos = $_REQUEST['semestre_cursos'];
	}

	$SQL_cursos = "SELECT id,cod_asignatura||'-'||seccion||' '||asignatura||' ('||profesor||')' AS nombre
	               FROM vista_cursos
	               WHERE ano=$ano_cursos AND semestre=$semestre_cursos AND id_carrera='$id_carrera' ORDER BY nombre;";
	$cursos = consulta_sql($SQL_cursos);

	if ($id_curso <> "") {
		$SQL_alumnos = "SELECT va.id,va.nombre 
	                   FROM cargas_academicas AS ca
	                   LEFT JOIN vista_alumnos AS va ON va.id=ca.id_alumno
	                   WHERE id_curso='$id_curso' AND (id_estado IS NULL OR id_estado NOT IN (6,10))
	                   ORDER BY nombre;";
		$alumnos_curso = consulta_sql($SQL_alumnos);

		if ($id_alumno <> "" && $id_curso <> "") {
			$SQL_tiempo_calificaciones = "SELECT solemne1,nota_catedra,solemne2,recuperativa
			                              FROM tiempo_calificaciones
			                              WHERE ano=$ANO AND semestre=$SEMESTRE;";
			$tiempo_calificaciones = consulta_sql($SQL_tiempo_calificaciones);
			$calificaciones_disponibles = array();
			foreach ($tiempo_calificaciones[0] AS $campo => $valor) {
				if ($valor == "t") {
					$calificaciones_disponibles = array_merge($calificaciones_disponibles,array($campo));
				}
			} 
			$calificaciones_disponibles = implode("," ,$calificaciones_disponibles);
			
			if ($calificaciones_disponibles <> "") {
				$SQL_calificaciones = "SELECT $calificaciones_disponibles
				                       FROM cargas_academicas
				                       WHERE id_curso='$id_curso' AND id_alumno='$id_alumno';";				
				$calificaciones = consulta_sql($SQL_calificaciones);
				if (count($calificaciones) > 0) {
					$calificaciones_aux = array();
					$cont = 0;
					foreach ($calificaciones[0] AS $calif => $nota) {
						if ($nota == -1.00) {
							$nota = "NSP";
							if ($calif == "nota_catedra") { $nota = "NCR"; }
						}
						if ($nota == "") { $nota = "--No Ingresada"; }
						$nombre_calif = "";
						switch ($calif) {
							case "solemne1":
								$nombre_calif = "Solemne 1";
								break;
							case "nota_catedra":
								$nombre_calif = "Nota Cátedra";
								break;
							case "solemne2":
								$nombre_calif = "Solemne 2";
								break;
							case "recuperativa":
								$nombre_calif = "Recuperativa";
								break;
						}								
						$calificaciones_aux = array_merge($calificaciones_aux,
						                                  array($cont => array("id"=>$calif,"nombre" => "$nombre_calif: $nota")));
						$cont++;
					}
					$calificaciones_alumno = $calificaciones_aux;
				}
			}
		}
	}		
} 
?>


<!-- Inicio: <?php echo($modulo); ?> -->
<form name="formulario" action="principal.php" method="get">
<input type="hidden" name="modulo" value="<?php echo($modulo); ?>">
<input type="hidden" name="calificaciones_disponibles" value=" <?php echo($calificaciones_disponibles); ?>">
<div class="tituloModulo">
  	<?php echo($nombre_modulo); ?>
</div><br>
<table class="tabla">
  <tr>
    <td class="tituloTabla"><input type="submit" name="anular" value="Anular"></td>
    <td class="tituloTabla"><input type="button" name="cancelar" value="Cancelar" onClick="history.back();"></td>
  </tr>
</table>
<br>
<table bgcolor="#ffffff" cellspacing="1" cellpadding="2" class="tabla">
<?php if ($_SESSION['id_tipo'] == 0) { ?>
  <tr>
    <td class="celdaNombreAttr">Periodo:</td>
    <td class="celdaValorAttr">
      <select name="semestre_cursos">
        <?php echo(select($semestres,$semestre_cursos)); ?>
      </select> - 
      <select name="ano_cursos">
        <?php echo(select($anos,$ano_cursos)); ?>
      </select>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td class="celdaNombreAttr">Carrera:</td>
    <td class="celdaValorAttr">
      <select name="id_carrera" onChange="submitform();">
        <option value="">-- Seleccione --</option>
        <?php echo(select($carreras,$id_carrera)); ?>
      </select>
    </td>
  </tr>
<?php if ($id_carrera <> "") { ?>
  <tr>
    <td class="celdaNombreAttr">Curso:</td>
    <td class="celdaValorAttr">
      <select name="id_curso" onChange="submitform();">
        <option value="">-- Seleccione --</option>
        <?php echo(select($cursos,$id_curso)); ?>
      </select>
    </td>
  </tr>
	<?php if ($id_curso <> "") { ?>
  <tr>
    <td class="celdaNombreAttr">Alumno:</td>
    <td class="celdaValorAttr">
      <select name="id_alumno" onChange="submitform();">
        <option value="">-- Seleccione --</option>
        <?php echo(select($alumnos_curso,$id_alumno)); ?>
      </select>
    </td>
  </tr>
		<?php if ($id_alumno <> "") { ?>
  <tr>
    <td class="celdaNombreAttr">Calificaciones:</td>
    <td class="celdaValorAttr">
      <select name="calificacion">
        <option value="">-- Seleccione --</option>
        <?php echo(select($calificaciones_alumno,$calificacion)); ?>
      </select>
    </td>
  </tr>
		<?php };?>
	<?php };?>
<?php };?>
</table>
</form>
<!-- Fin: <?php echo($modulo); ?> -->
