<script>
function cambiaPeriodo() {	
	var periodoSeleccionado = $("#cmbPeriodos").val();
	enviarValores(periodoSeleccionado);
}
function almacenaVariable(nombreVariable, valor) {
	var i=document.createElement('input');
		i.type='hidden';
		i.name=nombreVariable;
		i.value=valor;
		//f.appendChild(i);
	return i;
}

function enviarValores(periodoSeleccionado){
		var f = document.createElement('form');
		f.action='?modulo=evdem_exportar';
		f.method='POST';
		f.target='_self';
		
		var i=document.createElement('input');
		i = almacenaVariable("periodo_seleccionado", periodoSeleccionado);
		f.appendChild(i);
		document.body.appendChild(f);
		f.submit();
}

</script>
<link href="/assets/bootstrap/css/bootstrap.css" rel="stylesheet">
<?php
if (!$_SESSION['autentificado']) {
	header("Location: index.php");
	exit;
}
if (isset($_POST['periodo_seleccionado'])) {
	$periodoSeleccionado 			= $_POST['periodo_seleccionado'];
} else {
	$ss = "select id as id_vigente from periodo_eval where activo";
	$sqlperiodo     = consulta_sql($ss);
	extract($sqlperiodo[0]);	
	$periodoSeleccionado = $id_vigente;
}
//echo("periodoSeleccionado = $periodoSeleccionado"."</br>");

$id_usuario = $_SESSION['id_usuario'];
$id_usuarioParam = $_GET['ID_USUARIO_PARAM'];
//echo(msje_js("usuario normal = $id_usuario"));
if ($id_usuarioParam <> "") {
//	//$id_usuario = 1273; //960; //656; //1321; //1211; //1305; //569; //1321; //939; //1180; //315; //722; //1274; //3; //655; //656; //419; //558; //744; //1258; //741; //1207; //1211; //1273;
//	echo(msje_js("usuario = $id_usuarioParam"));
	$id_usuario = $id_usuarioParam;	
	echo(msje_js("usuario cambiado = $id_usuario"));
}

$id_usuarios_tipo = $_SESSION['tipo'];


	$anoEnCurso = $ANO; //"2020";
	//$id_periodo_eval = "1";
	//FIN DATOS EN DURO
	$ss = "	 
	select mini_glosa as mini_glosa, id as id_periodo_eval from periodo_eval where activo=true;
		";
	$sqlss     = consulta_sql($ss);
	extract($sqlss[0]);	
	
//		include("validar_modulo.php");

//echo("ANTES DE ACTUALIZAR");
actualizaPorcentajeAsistencia($anoEnCurso);
actualizaPorcentajeCapacitacion();
//echo("SUPUESTAMENTE LO HIZO");
//ESTA ES 
/*
$ss = "
select 
	id, 
	nombre, 
	glosa_tipo_ponderaciones, 
	unidad, 
	nombre_usuario,
	sum(resulteval) resulteval, 
	sum(asistencia) asistencia, 
	sum(capacitacion) capacitacion, 
	sum(autoevaluacion) autoevaluacion, 
	sum(poa) poa, 
	sum(final_resultado) final_resultado,
	esfuerzo_de_mejora,
	necesidad_capacitacion,
	comentario_desempeno		
	from (
select 
u.id as id, 
u.nombre || ' ' || u.apellido as nombre, 
(select glosa_tipo_ponderaciones from tipo_ponderaciones where id = u.id_tipo_ponderaciones) glosa_tipo_ponderaciones, 
gu.nombre as unidad,
u.nombre_usuario nombre_usuario,
coalesce(uj.resultado_eval,0) as resulteval,
coalesce(uj.porc_asistencia,0) as asistencia, 
coalesce(uj.porc_capacitacion,0) as capacitacion,
coalesce(
	case when (
				(
					select uj2.final_auto_funcionario_directivo_vicerrector 
						from usuarios_jerarquia uj2 
							where uj2.id_evaluador = u.id 
							and uj2.id_evaluado = u.id 
							and uj2.id_periodo_eval = $periodoSeleccionado
				) is not null	
			) then 
				(
					select uj2.final_auto_funcionario_directivo_vicerrector 
						from usuarios_jerarquia uj2 
							where uj2.id_evaluador = u.id 
							and uj2.id_evaluado = u.id 
							and uj2.id_periodo_eval = $periodoSeleccionado
				)
			
	else uj.final_auto_funcionario_directivo_vicerrector
	end
	,0) as autoevaluacion, 			
coalesce(uj.cumplimiento_poa,0) as poa,
coalesce(uj.final_resultado,0) as final_resultado,
(
	select evaluacion respuesta_01 from eval_items_evaluaciones where id_usuario_jerarquia in (
	select id from usuarios_jerarquia ujj where ujj.id_periodo_eval = 2 and ujj.id_evaluado = u.id) 
	and evaluacion is not null and id_eval_items_preguntas = 18
	
) esfuerzo_de_mejora,
(
	select evaluacion respuesta_01 from eval_items_evaluaciones where id_usuario_jerarquia in (
	select id from usuarios_jerarquia ujj where ujj.id_periodo_eval = 2 and ujj.id_evaluado = u.id) 
	and evaluacion is not null and id_eval_items_preguntas = 19
) necesidad_capacitacion,
(
	select evaluacion respuesta_01 from eval_items_evaluaciones where id_usuario_jerarquia in (
	select id from usuarios_jerarquia ujj where ujj.id_periodo_eval = 2 and ujj.id_evaluado = u.id) 
	and evaluacion is not null and id_eval_items_preguntas = 20
) comentario_desempeno
from 
usuarios u 
left join gestion.unidades as gu on gu.id = u.id_unidad
left join usuarios_jerarquia as uj on uj.id_evaluado = u.id 
								and uj.id_evaluador <> u.id 
								and uj.id_periodo_eval = $periodoSeleccionado
where u.tipo <> 3 and u.activo
and u.id_unidad is not null
) as tabla
group by 
id, 
nombre, 
glosa_tipo_ponderaciones,
unidad,
nombre_usuario, 
esfuerzo_de_mejora,
necesidad_capacitacion,
comentario_desempeno
--order by gu.nombre, u.nombre_usuario, unidad	
order by unidad, nombre_usuario
";
*/
$ss = "
select 
	id, 
	nombre, 
	glosa_tipo_ponderaciones, 
	unidad, 
	nombre_usuario,
	sum(resulteval) resulteval, 
	sum(asistencia) asistencia, 
	sum(capacitacion) capacitacion, 
	sum(autoevaluacion) autoevaluacion, 
	sum(poa) poa, 
	sum(final_resultado) final_resultado,
	p1_responsab,
	p2_responsab,
	p3_responsab,
	p1_actitud,
	p2_actitud,
	p3_actitud,
	p4_actitud,
	p5_actitud,
	p1_cargo,
	p2_cargo,
	p3_cargo,
	p4_cargo,
	p5_cargo,
	p1_direccion,
	p2_direccion,
	p3_direccion,
	p4_direccion,
	esfuerzo_de_mejora,
	necesidad_capacitacion,
	comentario_desempeno		
	from (
select 
u.id as id, 
u.nombre || ' ' || u.apellido as nombre, 
(select glosa_tipo_ponderaciones from tipo_ponderaciones where id = u.id_tipo_ponderaciones) glosa_tipo_ponderaciones, 
gu.nombre as unidad,
u.nombre_usuario nombre_usuario,
coalesce(uj.resultado_eval,0) as resulteval,
coalesce(uj.porc_asistencia,0) as asistencia, 
coalesce(uj.porc_capacitacion,0) as capacitacion,
coalesce(
	case when (
				(
					select uj2.final_auto_funcionario_directivo_vicerrector 
						from usuarios_jerarquia uj2 
							where uj2.id_evaluador = u.id 
							and uj2.id_evaluado = u.id 
							and uj2.id_periodo_eval = uj.id_periodo_eval
				) is not null	
			) then 
				(
					select uj2.final_auto_funcionario_directivo_vicerrector 
						from usuarios_jerarquia uj2 
							where uj2.id_evaluador = u.id 
							and uj2.id_evaluado = u.id 
							and uj2.id_periodo_eval = uj.id_periodo_eval
				)

	else uj.final_auto_funcionario_directivo_vicerrector
	end
	,0) as autoevaluacion, 			
coalesce(uj.cumplimiento_poa,0) as poa,
coalesce(uj.final_resultado,0) as final_resultado,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P1'
	)
) p1_responsab,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P2'
	)
) p2_responsab,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P3'
	)
) p3_responsab,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P4'
	)
) p1_actitud,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P5'
	)
) p2_actitud,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P6'
	)
) p3_actitud,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P7'
	)
) p4_actitud,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P8'
	)
) p5_actitud,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P9'
	)
) p1_cargo,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P10'
	)
) p2_cargo,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P11'
	)
) p3_cargo,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P12'
	)
) p4_cargo,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P13'
	)
) p5_cargo,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P14'
	)
) p1_direccion,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P15'
	)
) p2_direccion,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P16'
	)
) p3_direccion,
(
	select evaluacion  from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id
	and id_eval_items_preguntas = (
		select id_eval_items from eval_items_preguntas
		where cod_interno = '1_P17'
	)
) p4_direccion,

(	select evaluacion respuesta_01 from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id	
	and evaluacion is not null 
	and id_eval_items_preguntas = 18

) esfuerzo_de_mejora,
(
	select evaluacion respuesta_02 from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id 
	and id_eval_items_preguntas = 19
) necesidad_capacitacion,
(
	select evaluacion respuesta_03 from eval_items_evaluaciones 
	where id_usuario_jerarquia = uj.id 
	and id_eval_items_preguntas = 20
) comentario_desempeno
from 
usuarios u 
left join gestion.unidades as gu on gu.id = u.id_unidad
left join usuarios_jerarquia as uj on uj.id_evaluado = u.id 
								and uj.id_evaluador <> u.id 
								and uj.id_periodo_eval = $periodoSeleccionado
where u.tipo <> 3 and u.activo
and u.id_unidad is not null
) as tabla
group by 
id, 
nombre, 
glosa_tipo_ponderaciones,
unidad,
nombre_usuario, 
p1_responsab,
p2_responsab,
p3_responsab,
p1_actitud,
p2_actitud,
p3_actitud,
p4_actitud,
p5_actitud,
p1_cargo,
p2_cargo,
p3_cargo,
p4_cargo,
p5_cargo,
p1_direccion,
p2_direccion,
p3_direccion,
p4_direccion,
esfuerzo_de_mejora,
necesidad_capacitacion,
comentario_desempeno
order by  id asc
";
		//echo ($ss);
		$SQL_tabla_completa = "COPY ($ss) to stdout WITH CSV HEADER";
		$candidatos     = consulta_sql($ss);
		$HTML_encuesta .= " <thead> 
		<tr class='filaTituloTabla'>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>Id</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>Nombre</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>Tipo Ponderador</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>Unidad</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>POA</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>Ev.Jefe</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>Autoevaluaci&oacute;n</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>Asistencia</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>Capacitaci&oacute;n</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>Resultado Final</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p1_responsab</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p2_responsab</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p3_responsab</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p1_actitud</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p2_actitud</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p3_actitud</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p4_actitud</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p5_actitud</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p1_cargo</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p2_cargo</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p3_cargo</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p4_cargo</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p5_cargo</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p1_direccion</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p2_direccion</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p3_direccion</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>p4_direccion</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>esfuerzo_de_mejora</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>necesidad_capacitacion</b></td>".$LF
		. "    <td colspan='2' align='center' class='tituloTabla'><b>comentario_desempeno</b></td>".$LF
		. "  </tr>
		</thead>".$LF;
		
//echo("uno");
		$id_sesion = $_SESSION['usuario']."_".$modulo."_".session_id();
//		echo("dos");		
		$boton_tabla_completa = "<a href='#' onClick=\"javascript:window.open('tabla_completa.php?id_sesion=$id_sesion');\" class='boton'><small>Tabla Completa</small></a>";
		$boton_peirodo_evaluacion = "<a href='principal.php?modulo=evdem_periodo_evaluacion' class='boton'><small>Activar Periodo Evaluación</small></a>";

/*		
		$boton_peirodo_evaluacion = "<a id='sgu_fancybox' 
href='<?php echo($enlbase); ?>=evdem_periodo_evaluacion
&modo=NUEVO
' class='boton'>Activar Periodo Evaluación</a>  		
";
*/


//		echo("tres");
		$nombre_arch = "sql-fulltables/$id_sesion.sql";
//		echo("cuatro");
		file_put_contents($nombre_arch,$SQL_tabla_completa);
//		echo("cinco");

		$ss = "select id as id, mini_glosa as mini_glosa, activo as activo from periodo_eval";
		$periodos = consulta_sql($ss);
		$HTML_selectAno = "Resultado evaluación desempeño : 
							<select name='cmbPeriodos' id='cmbPeriodos' onChange='cambiaPeriodo()'>";

		for ($x=0;$x<count($periodos);$x++) {
			extract($periodos[$x]);
			$sss = "";
			//echo("id = $id ");
			if ($id == $periodoSeleccionado) {
				$sss = "selected";
			}
			$HTML_selectAno .="<option value='$id' $sss>$mini_glosa</option>";
		}	

		$HTML_selectAno .="</select>";	

		for ($x=0;$x<count($candidatos);$x++) {
			extract($candidatos[$x]);
			
			$disabledAtribute = "";
			if ($autoevaluacion_cerrada == "t") { //true
				$disabledAtribute = "disabled";
			}
			$HTML_encuesta .= " <tbody> <tr class='filaTabla'>".$LF
							. "    <td colspan='2' class='textoTabla'><u>$id</u></td>".$LF
							. "    <td colspan='2' class='textoTabla'><u>$nombre</u></td>".$LF
							. "    <td colspan='2' class='textoTabla'><u>$glosa_tipo_ponderaciones</u></td>".$LF							
							. "    <td colspan='2' class='textoTabla'><u>$unidad</u></td>".$LF;
			if ($poa != "") {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla'  style='text-align: right;' ><u>$poa%</u></td>".$LF;
			} else {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
			}
			if ($resulteval != "") {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$resulteval%</u></td>".$LF;
			} else {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
			}
			if ($autoevaluacion != "") {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$autoevaluacion%</u>";
				$HTML_encuesta .= "</td>.$LF";
			} else {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
			}
			if ($asistencia != "") {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$asistencia%</u></td>".$LF;
			} else {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
			}
			if ($capacitacion != "") {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$capacitacion%</u></td>".$LF;
			} else {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
			}
			if ($final_resultado != "") {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$final_resultado%</u></td>".$LF;					
			} else {
				$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
			}
/*-------------------------------------*/
if ($p1_responsab != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p1_responsab</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}
if ($p2_responsab != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p2_responsab</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}
if ($p3_responsab != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p3_responsab</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}
/*-------------------------------------*/
if ($p1_actitud != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p1_actitud</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}

if ($p2_actitud != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p2_actitud</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}
if ($p3_actitud != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p3_actitud</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}
if ($p4_actitud != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p4_actitud</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}
if ($p5_actitud != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p5_actitud</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}

/*-------------------------------------*/
if ($p1_cargo != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p1_cargo</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}

if ($p2_cargo != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p2_cargo</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}
if ($p3_cargo != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p3_cargo</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}

if ($p4_cargo != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p4_cargo</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}

if ($p5_cargo != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p5_cargo</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}

/*-------------------------------------*/
if ($p1_direccion != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p1_direccion</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}

if ($p2_direccion != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p2_direccion</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}
if ($p3_direccion != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p3_direccion</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}

if ($p4_direccion != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$p4_direccion</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}

/*-------------------------------------*/
if ($esfuerzo_de_mejora != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$esfuerzo_de_mejora</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}

if ($necesidad_capacitacion != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$necesidad_capacitacion</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;
}
if ($comentario_desempeno != "") {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla' style='text-align: right;'><u>$comentario_desempeno</u></td>".$LF;					
} else {
	$HTML_encuesta .= "		<td colspan='2' class='textoTabla'><u></u></td>".$LF;

}

		

/*--------------------------------------*/




			if ($asistencia == "") {
				$asistencia = "0";
			}
			if ($capacitacion == "") {
				$capacitacion = "0";
			}
			if ($porcprocon == "") {
				$porcprocon = "0";
			}

			$ww = "200px";
			$hh = "30px";
		}
		?>
		<div class="tituloModulo">
			<?php echo($nombre_modulo); ?>
			
		</div><br>
		<div class="texto">
		<?php
			echo($HTML_selectAno);
		?>
		</div>		
		<div class="texto">
		<?php
			echo($boton_tabla_completa);
			echo($boton_peirodo_evaluacion);
		?>
		</div>
		
		</br>
		<!--<table cellpadding="2" cellspacing="1" class="tabla" bgcolor="#FFFFFF">-->
		<table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
		<?php 
			echo($HTML_encuesta); 
			echo '</tr>
			</tbody>';			
			
		?>
		</table>

		<script src="/assets/bootstrap/js/popper.min.js"></script>
        <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
		<script src="/assets/datatables/jquery.dataTables.min.js"></script>

	<!-- slimscrollbar scrollbar JavaScript -->
	<script src="/assets/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="/assets/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="/assets/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="/assets/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src=/assets/sparkline/jquery.sparkline.min.js></script>
    <!--Custom JavaScript -->
    <script src="/assets/js/custom.min.js"></script>

    <!-- start - This is for export functionality only -->
	<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
	
		<script>
			setTimeout(() => {
	 $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
}, 300);
    </script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="/assets/styleswitcher/jQuery.style.switcher.js"></script>



