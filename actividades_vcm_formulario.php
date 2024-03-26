<?php

if (!$_SESSION['autentificado']) {
	header("Location: index.php");
	exit;
}

$TIPOS        = consulta_sql("SELECT id,nombre,dimension AS grupo FROM vcm.tipos_act ORDER BY grupo,nombre");
$UNIDADES     = consulta_sql("SELECT u.id,u.nombre,uu.nombre AS grupo FROM gestion.unidades u LEFT JOIN gestion.unidades uu ON uu.id=u.dependencia WHERE u.dependencia IS NOT NULL ORDER BY uu.id,u.nombre");
$RESPONSABLES = consulta_sql("SELECT id,nombre,tipo AS grupo FROM vista_usuarios WHERE activo='Si' ORDER BY tipo,nombre");
$MODALIDADES  = consulta_sql("SELECT id,nombre FROM vista_vcm_modalidad_act ORDER BY nombre");
$ALCANCES     = consulta_sql("SELECT id,nombre FROM vista_vcm_alcance_act ORDER BY nombre");
$CURSOS       = consulta_sql("SELECT id,cod_asignatura||'-'||seccion||' '||asignatura||' ['||profesor||']' AS nombre,carrera AS grupo FROM vista_cursos WHERE ano=$ANO ORDER BY carrera,seccion,cod_asignatura");

if ($_REQUEST['id_responsable'] == "") { $_REQUEST['id_responsable'] = $_SESSION['id_usuario']; }
if ($_REQUEST['id_unidad1'] == "") { $_REQUEST['id_unidad1'] = $_SESSION['id_unidad']; }
if ($_REQUEST['ano'] == "") { $_REQUEST['ano'] = $ANO; }

$HTML_tipo_publico = "";
$TIPO_PUBLICO = consulta_sql("SELECT id,nombre FROM vista_vcm_tipo_publico ORDER BY nombre");
for ($x=0;$x<count($TIPO_PUBLICO);$x++) { 
    $checked = "";
    if (in_array($TIPO_PUBLICO[$x]['id'],$_REQUEST['_tipo_publico'])) { $checked = "checked"; }
    $HTML_tipo_publico .= "<input type='checkbox' name='_tipo_publico[]' id='{$TIPO_PUBLICO[$x]['id']}' value='{$TIPO_PUBLICO[$x]['nombre']}' $checked> "
                       .  "<label for='{$TIPO_PUBLICO[$x]['id']}'>{$TIPO_PUBLICO[$x]['nombre']}</label><br>"; 
}

$HTML_difusion = "";
$DIFUSION = consulta_sql("SELECT id,nombre FROM vista_vcm_tipo_difusion");
for ($x=0;$x<count($DIFUSION);$x++) { 
    $checked = "";
    if (in_array($DIFUSION[$x]['id'],$_REQUEST['_difusion'])) { $checked = "checked"; }
    $HTML_difusion .= "<input type='checkbox' name='_difusion[]' id='{$DIFUSION[$x]['id']}' value='{$DIFUSION[$x]['nombre']}' $checked> "
                   .  "<label for='{$DIFUSION[$x]['id']}'>{$DIFUSION[$x]['nombre']}</label><br>";  
}

$HORAS = array();
for($hora=8;$hora<=20;$hora++) { 
    for($min=0;$min<=45;$min=$min+15) {
        if ($hora<13) { $grupo = "Mañana"; }
        elseif ($hora<19) { $grupo = "Tarde"; }
        else { $grupo = "Vespertina"; }
        $horaf = sprintf("%'.02d",$hora).":".sprintf("%'.02d",$min); 
        $HORAS[] = array("id" => $horaf, "nombre" => $horaf, "grupo" => $grupo);
    }
}

$fecha_min = date("Y-m-d",strtotime($_REQUEST['fec_inicio']));
$ano_min = date("Y",strtotime($_REQUEST['ano']));
?>

<form name="formulario" action="<?php echo($_SERVER['SCRIPT_NAME']); ?>" method="post">
<input type="hidden" name="modulo" value="<?php echo($modulo); ?>">
<input type="hidden" name="id_actividad" value="<?php echo($id_actividad); ?>">
<div style='margin-top: 5px'>
  <input type="submit" name='guardar' value="💾 Guardar">
  <input type="button" name='cancelar' value="❌ Cancelar" onClick="parent.jQuery.fancybox.close();">
</div>
<table bgcolor="#ffffff" cellspacing="1" cellpadding="2" class="tabla" style='margin-top: 5px'>
  
  <tr><td class='celdaNombreAttr' colspan="4" style="text-align: center; ">Antecedentes de la Actividad</td></tr>
  <tr>
    <td class='celdaNombreAttr'><label for="nombre">Nombre:</label></td>
    <td class='celdaValorAttr' colspan="3"><input type="text" size='70' id="nombre" name="nombre" value="<?php echo($_REQUEST['nombre']); ?>" <?php echo($readonly); ?> class='boton' required></td>
  </tr>
  <tr>
    <td class='celdaNombreAttr'><label for="objetivo">Objetivo:</label></td>
    <td class='celdaValorAttr' colspan="3"><textarea id="objetivo" name="objetivo" class="general" required><?php echo($_REQUEST['objetivo']); ?></textarea></td>
  </tr>
  <tr>
    <td class='celdaNombreAttr'><label for="id_tipo">Dimensión/Tipo:</label></td>
    <td class='celdaValorAttr'>
	  <select id="id_tipo" name="id_tipo" class='filtro' style='max-width: none' required>
		<option value=''>-- Seleccione --</option>
		<?php echo(select_group($TIPOS,$_REQUEST['id_tipo'])); ?>
      </select>
	</td>
    <td class='celdaNombreAttr'><label for="alcance">Alcance:</label></td>
    <td class='celdaValorAttr'>
	  <select id="alcance" name="alcance" class='filtro' style='max-width: none' required>
		<option value=''>-- Seleccione --</option>
		<?php echo(select($ALCANCES,$_REQUEST['alcance'])); ?>
      </select>
	</td>
  </tr>
  <tr>
    <td class='celdaNombreAttr'><label for="ano">Año:</label></td>
    <td class='celdaValorAttr'><input type="number" min="<?php echo($ano_min); ?>" size='4' id="ano" name="ano" class="boton" style='width: 50px' value="<?php echo($_REQUEST['ano']); ?>" required></td>
    <td class='celdaNombreAttr'><label for="modalidad">Modalidad:</td>
    <td class='celdaValorAttr'>
	  <select id="modalidad" name="modalidad" class='filtro' required>
		<option value=''>-- Seleccione --</option>
		<?php echo(select_group($MODALIDADES,$_REQUEST['modalidad'])); ?>
      </select>
    </td>
  </tr>
  <tr>
    <td class='celdaNombreAttr'><label for="fecha_inicio">Fecha y hora de Inicio:</label></td>
    <td class='celdaValorAttr'>
      <input type="date" id="fecha_inicio" name="fec_inicio" min="<?php echo($fecha_min); ?>" class="boton" value="<?php echo($_REQUEST['fec_inicio']); ?>" onChange="formulario.fecha_termino.value=this.value; formulario.fecha_termino.min=this.value;" required>
      <select name="hora_inicio" class="filtro" style="height: 25px" onChange="formulario.hora_termino.value=this.value" required>
        <option value="">-- HH:MM --</option>
        <?php echo(select_group($HORAS,$_REQUEST['hora_inicio'])); ?>
      </select>
    </td>
    <td class='celdaNombreAttr'><label for="fecha_termino">Fecha y hora de Término:</label></td>
    <td class='celdaValorAttr'>
      <input type="date" id="fecha_termino" name="fec_termino" min="<?php echo($fecha_min); ?>" class="boton" value="<?php echo($_REQUEST['fec_termino']); ?>" required>
      <select name="hora_termino" class="filtro" style="height: 25px" required>
        <option value="">-- HH:MM --</option>
        <?php echo(select_group($HORAS,$_REQUEST['hora_termino'])); ?>
      </select>
    </td>
  </tr>
  <tr>
    <td class='celdaNombreAttr'>Público Objetivo:</td>
    <td class='celdaValorAttr'><?php echo($HTML_tipo_publico); ?></td>
    <td class='celdaNombreAttr'>Difusión:</td>
    <td class='celdaValorAttr'><?php echo($HTML_difusion); ?></td>
  </tr>
<?php if ($modulo == "actividades_vcm_editar") { ?>
  <tr><td class='celdaNombreAttr' colspan="4" style="text-align: center; ">Enlaces</td></tr>
  <tr>
    <td class='celdaNombreAttr'><label for="enl_videoconferencia">Videoconferencia:</label></td>
    <td class='celdaValorAttr' colspan="3">
      <input type="url" size='70' id="enl_videoconferencia" name="enl_videoconferencia" value="<?php echo($_REQUEST['enl_videoconferencia']); ?>" class='boton' placeholder="https://"><br>
      <sup>(Zoom, Meet, etc)</sup>
    </td>
  </tr>
  <tr>
    <td class='celdaNombreAttr'><label for="enl_conferencia_grabada">Videograbación:</label></td>
    <td class='celdaValorAttr' colspan="3">
      <input type="url" size='70' id="enl_conferencia_grabada" name="enl_conferencia_grabada" value="<?php echo($_REQUEST['enl_conferencia_grabada']); ?>" class='boton' placeholder="https://"><br>
      <sup>(YouTube, Vimeo, etc)</sup>
    </td>
  </tr>
<?php } ?>

  <tr><td class='celdaNombreAttr' colspan="4" style="text-align: center; ">Antecedentes de la Organización</td></tr>

  <tr>
    <td class='celdaNombreAttr'><label for="id_responsable">Responsable:</label></td>
    <td class='celdaValorAttr' colspan="3">
	  <select name="id_responsable" id="id_responsable" class='filtro' required>
		<option value=''>-- Seleccione --</option>
		<?php echo(select_group($RESPONSABLES,$_REQUEST['id_responsable'])); ?>
      </select>
	</td>
  </tr>
  <tr>
    <td class='celdaNombreAttr'><label for="id_unidad1">Unidad Organizadora:</label></td>
    <td class='celdaValorAttr' colspan="3">
	  <select id="id_unidad1" name="id_unidad1" class='filtro' style='max-width: none' required>
		<option value=''>-- Seleccione --</option>
		<?php echo(select_group($UNIDADES,$_REQUEST['id_unidad1'])); ?>
      </select>
	</td>
  </tr>
  <tr>
    <td class='celdaNombreAttr'><label for="id_unidad2">2da Unidad Organizadora:</label></td>
    <td class='celdaValorAttr' colspan="3">
	  <select id="id_unidad2" name="id_unidad2" class='filtro' style='max-width: none'>
		<option value=''>-- Seleccione --</option>
		<?php echo(select_group($UNIDADES,$_REQUEST['id_unidad2'])); ?>
      </select>
	</td>
  </tr>
  <tr>
    <td class='celdaNombreAttr'><label for="id_unidad3">3ra Unidad Organizadora:</label></td>
    <td class='celdaValorAttr' colspan="3">
	    <select id="id_unidad3" name="id_unidad3" class='filtro' style='max-width: none'>
		    <option value=''>-- Seleccione --</option>
		    <?php echo(select_group($UNIDADES,$_REQUEST['id_unidad3'])); ?>
      </select>
	  </td>
  </tr>
  <tr>
    <td class='celdaNombreAttr'><label for="organizador_externo">Co-organizador externo:</label></td>
    <td class='celdaValorAttr' colspan="3"><input type="text" size='70' id="organizador_externo" name="organizador_externo" value="<?php echo($_REQUEST['organizador_externo']); ?>" class='boton'></td>
  </tr>

  <tr><td class='celdaNombreAttr' colspan="4" style="text-align: center; ">Antecedentes de Articulación</td></tr>
  <tr>
    <td class='celdaNombreAttr' colspan="3" style="text-align: center; ">Curso</td>
    <td class='celdaNombreAttr' style="text-align: center; ">Fecha Eval.</td>
  </tr>

  <tr>
    <td class='celdaValorAttr' colspan="3">
	    <select name="id_curso1" id="id_curso1" class='filtro' onChange="formulario.fecha_eval1.required=(this.value!='');">
		    <option value=''>-- Seleccione --</option>
		    <?php echo(select_group($CURSOS,$_REQUEST['id_curso1'])); ?>
      </select>
    </td>
    <td class='celdaValorAttr' align='center'>
      <input type="date" id="fecha_eval1" name="fecha_eval1" min="<?php echo($fecha_min); ?>" class="boton" value="<?php echo($_REQUEST['fecha_eval1']); ?>">
    </td>
  </tr>
  <tr>
    <td class='celdaValorAttr' colspan="3">
	    <select name="id_curso2" id="id_curso2" class='filtro' onChange="formulario.fecha_eval2.required=!(this.value!='');">
		    <option value=''>-- Seleccione --</option>
		    <?php echo(select_group($CURSOS,$_REQUEST['id_curso2'])); ?>
      </select>
    </td>
    <td class='celdaValorAttr' align='center'>
      <input type="date" id="fecha_eval2" name="fecha_eval2" min="<?php echo($fecha_min); ?>" class="boton" value="<?php echo($_REQUEST['fecha_eval2']); ?>">
    </td>
  </tr>
  <tr>
    <td class='celdaValorAttr' colspan="3">
      <select name="id_curso3" id="id_curso3" class='filtro' onChange="formulario.fecha_eval3.required=!(this.value!='');">
		    <option value=''>-- Seleccione --</option>
		    <?php echo(select_group($CURSOS,$_REQUEST['id_curso3'])); ?>
      </select>
    </td>
    <td class='celdaValorAttr' align='center'>
      <input type="date" id="fecha_eval3" name="fecha_eval3" min="<?php echo($fecha_min); ?>" class="boton" value="<?php echo($_REQUEST['fecha_eval3']); ?>">
    </td>
  </tr>

</table>
</form>

<script>

$(document).ready(function () {
	$('#id_responsable').selectize({
		sortField: 'text'
	});
});

$(document).ready(function () {
	$('#id_curso1').selectize({
		sortField: 'text'
	});
});

$(document).ready(function () {
	$('#id_curso2').selectize({
		sortField: 'text'
	});
});

$(document).ready(function () {
	$('#id_curso3').selectize({
		sortField: 'text'
	});
});

</script>
<!-- Fin: <?php echo($modulo); ?> -->
