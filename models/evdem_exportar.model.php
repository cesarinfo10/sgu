<script>
let anoPer = $('select[name="cmbPeriodos"] option:selected').text();	
$("#anp").html('<h4 class="modal-title">Tipo Ponderador Años: '+ anoPer +' </h4>');

	
</script>
<style>

.dataTables_filter {
   float: left !important;
}
</style>
<?php

include('conexion.php');

/*=============================================
LLAMAR EV. DESEMPEÑO
=============================================*/
if (isset($_GET['getAllExportar'])){
	$anoPer = $_GET['anoPer'];
	if ($anoPer == 0){
		$periodo ='';
	}else{
	$periodo = "and uj.id_periodo_eval = '".$anoPer."'";
	}
	
    $dbconn = db_connect();

    $query = "SELECT id,
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
	comentario_desempeno from (
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
								$periodo
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
order by  id asc";

    $result = pg_query($dbconn, $query) or die('La consulta fallo: ' . pg_last_error());
	//$row = pg_fetch_row($result);

	/*while ($row = pg_fetch_row($result)) {
    var_dump($row);
	}*/
   echo '<br>

   <br>
   <input type="button" style="display: block;
   position: relative;
   z-index: 1000;
   left: 10%;
   bottom: 12px;
   border-radius: 5%;
   text-decoration: none;
   background: #d3e0ea;
   padding: 10px;
   top: 40px;
   font-size: .88em;" 
   mar 
   data-toggle="modal" data-target="#myModal"
   value="Ver Tipo Ponderador">

   <table id="tblExporEvdem" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Id</th>
		<th>Nombre</th>
		<th>
		Tipo Ponderador
		</th>
		<th>Unidad</th>
		<th>POA</th>
		<th>Ev.Jefe</th>
		<th>Autoevaluación</th>
		<th>Asistencia</th>
		<th>Capacitación</th>
		<th class="text-center">Resultado<br/>Final</th>
		<th>p1_responsab</th>
		<th>p2_responsab</th>
		<th>p3_responsab</th>
		<th>p1_actitud</th>
		<th>p2_actitud</th>
		<th>p3_actitud</th>
		<th>p4_actitud</th>
		<th>p5_actitud</th>
		<th>p1_cargo</th>
		<th>p2_cargo</th>
		<th>p3_cargo</th>
		<th>p4_cargo</th>
		<th>p5_cargo</th>
		<th>p1_direccion</th>
		<th>p2_direccion</th>
		<th>p3_direccion</th>
		<th>p4_direccion</th>
		<th>Esfuerzo de Mejora</th>
		<th>Necesidad Capacitacion</th>	
		<th>Comentario Desempeno</th>
		
			
    </tr>
    </thead>
    <tbody>';
  
    while ($row = pg_fetch_row($result)) {
      echo '
      <tr>
            <td>'.$row[0].'</td>
			<td>'.$row[1].'</td>
			<td>'.$row[2].'</td>
			<td>'.$row[3].'</td>
			<td>'.$row[9].'</td>
			<td>'.$row[5].'</td>
			<td>'.$row[8].'</td>
			<td>'.$row[6].'</td>
			<td>'.$row[7].'</td>
			<td>'.$row[10].'</td>
			<td>'.$row[11].'</td>
			<td>'.$row[12].'</td>
			<td>'.$row[13].'</td>
			<td>'.$row[14].'</td>
			<td>'.$row[15].'</td>
			<td>'.$row[16].'</td>
			<td>'.$row[17].'</td>
			<td>'.$row[18].'</td>
			<td>'.$row[19].'</td>
			<td>'.$row[20].'</td>
			<td>'.$row[21].'</td>
			<td>'.$row[22].'</td>
			<td>'.$row[23].'</td>
			<td>'.$row[24].'</td>
			<td>'.$row[25].'</td>
			<td>'.$row[26].'</td>
			<td>'.$row[27].'</td>
			<td>'.$row[28].'</td>
			<td>'.$row[29].'</td>
			<td>'.$row[30].'</td>
      </tr>';
    }

	

	$queryPon = "SELECT 
    glosa_tipo_ponderaciones,
 	TRIM(TRAILING '.' FROM TRIM(TRAILING '0' FROM TO_CHAR(ROUND(AVG(poa)::numeric, 2), 'FM999999990.00'))) AS poa,
    TRIM(TRAILING '.' FROM TRIM(TRAILING '0' FROM TO_CHAR(ROUND(AVG(resulteval)::numeric, 2), 'FM999999990.00'))) AS resulteval,
    TRIM(TRAILING '.' FROM TRIM(TRAILING '0' FROM TO_CHAR(ROUND(AVG(asistencia)::numeric, 2), 'FM999999990.00'))) AS asistencia,
    TRIM(TRAILING '.' FROM TRIM(TRAILING '0' FROM TO_CHAR(ROUND(AVG(capacitacion)::numeric, 2), 'FM999999990.00'))) AS capacitacion, 
    TRIM(TRAILING '.' FROM TRIM(TRAILING '0' FROM TO_CHAR(ROUND(AVG(final_resultado)::numeric, 2), 'FM999999990.00'))) AS Porcentaje
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
								$periodo
	where u.tipo <> 3 and u.activo
	and u.id_unidad is not null
	)
	as tabla

	group by 
	glosa_tipo_ponderaciones
";

$resultPon = pg_query($dbconn, $queryPon) or die('La consulta fallo: ' . pg_last_error());


    echo '</tbody>
    </table>
	<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
		<div id="anp"></div>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
         
		<table class="table table-striped">
    <thead>
	   <tr>  
        <th>Tipo Ponderador</th>
		<th>PROCON</th>
		<th>Ev.Jefe</th>
        <th>Asistencia</th>
		<th>Capacitación</th>
        <th>Total %</th>
      </tr>
	</thead>
    <tbody>';
	$sumaPresente = 0;
	$sumaResultado = 0;
	while ($row = pg_fetch_row($resultPon)) {
		if ($row[0] != '' || $row[0] != null)
		{
			/*$sumaPresente= $sumaPresente +$row[1];
			$sumaResultado= $sumaResultado +ceil($row[2]);*/
		echo '
      <tr>
	  	<td>'.$row[0].'</td>
		<td>'.($row[1]).'%</td>
		<td>'.($row[2]).'%</td>
        <td>'.($row[3]).'%</td>
		<td>'.($row[4]).'%</td>
        <td>'.($row[5]).'%</td>';
	  }
	  echo'</tr>';
	  }
	echo'</tbody>
  </table>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
        </div>
        
      </div>
    </div>
  </div>
	';
     }
