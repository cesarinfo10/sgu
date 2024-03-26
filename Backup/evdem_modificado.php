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
<style>
.dt-buttons{ padding: 0 !important; border: none !important; } 
</style>
</script> 
<link href="/assets/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">

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

actualizaPorcentajeAsistencia($anoEnCurso);
actualizaPorcentajeCapacitacion();


		//echo ($ss);
		$SQL_tabla_completa = "COPY ($ss) to stdout WITH CSV HEADER";
		$candidatos     = consulta_sql($ss);
		$HTML_encuesta .= " <thead> ".$LF
		. "<tr class='filaTituloTabla'>".$LF
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

		$HTML_encuesta .= " <tbody>".$LF;
		for ($x=0;$x<count($candidatos);$x++) {
			extract($candidatos[$x]);
			
			$disabledAtribute = "";
			if ($autoevaluacion_cerrada == "t") { //true
				$disabledAtribute = "disabled";
			}
			$HTML_encuesta .= " <tr class='filaTabla'>".$LF;
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
		<div id="tblEvdem"></div>

	<script src="./js/evedem_exportar.js"></script>
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
			llamarBecasTBL();
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
    $('#tblExporEvdem').DataTable({
		"language": {
          "sProcessing": "Procesando...",
          "sLengthMenu": "Mostrar MENU registros",
          "sZeroRecords": "No se encontraron resultados",
          "sEmptyTable": "Ningún dato disponible en esta tabla",
		  "info": "Mostrando _START_ de _END_ Total de Registros _TOTAL_",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
          "sInfoFiltered": "(filtrado de un total de MAX registros)",
          "sInfoPostFix": "",
          "sSearch": "Buscar:",
          "sUrl": "",
          "sInfoThousands": ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
              "sFirst": "Primero",
              "sLast": "Último",
              "sNext": "Siguiente",
              "sPrevious": "Anterior"
          },
          "oAria": {
              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
      },

        dom: 'Bfrtip',
        buttons: [
			'excel', 'csv', 'pdf'
        ]
    });
}, 1000);
    </script>
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="/assets/styleswitcher/jQuery.style.switcher.js"></script>



