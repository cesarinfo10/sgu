<?php

$ids_carreras = $_SESSION['ids_carreras'];

$cant_reg = $_REQUEST['cant_reg'];
if (empty($_REQUEST['cant_reg'])) { $cant_reg = 30; }
$tot_reg  = 0;

$reg_inicio = $_REQUEST['r_inicio'];
if ($reg_inicio=="") { $reg_inicio = 0; }

$texto_buscar      = $_REQUEST['texto_buscar'];
$buscar            = $_REQUEST['buscar'];
$id_carrera        = $_REQUEST['id_carrera'];
$jornada           = $_REQUEST['jornada'];
$semestre_cohorte  = $_REQUEST['semestre_cohorte'];
$mes_cohorte       = $_REQUEST['mes_cohorte'];
$cohorte           = $_REQUEST['cohorte'];
$ano_egreso        = $_REQUEST['ano_egreso'];
$semestre_egreso   = $_REQUEST['semestre_egreso'];
$fec_ini_egreso    = $_REQUEST['fec_ini_egreso'];
$fec_fin_egreso    = $_REQUEST['fec_fin_egreso'];
$moroso_financiero = $_REQUEST['moroso_financiero'];
$admision          = $_REQUEST['admision'];
$regimen           = $_REQUEST['regimen'];
$aprob_ant         = $_REQUEST['aprob_ant'];
$matriculado       = $_REQUEST['matriculado'];
//nuevos campos incorporados

$texto__rut        = $_REQUEST['texto_rut'];
$texto__ndocumento = $_REQUEST['texto_ndocumento'];
$texto__npasaporte = $_REQUEST['texto_npasaporte'];
$texto__nombre     = $_REQUEST['texto_nombre'];
$texto__apellidos  = $_REQUEST['texto_apellidos'];
$texto__direccion  = $_REQUEST['texto_direccion'];
$texto__email      = $_REQUEST['texto_email'];
$texto__celular    = $_REQUEST['texto_celular'];
$texto__fnacimiento= $_REQUEST['texto_fnacimiento'];
$ddl__pais         = $_REQUEST['ddl_pais'];
$ddl__tipoDocumento= $_REQUEST['ddl_tipoDocumento'];
$ddl__estadoCivil  = $_REQUEST['ddl_estadoCivil'];
$ddl__genero       = $_REQUEST['ddl_genero'];
$ddl__regimen      = $_REQUEST['ddl_regimen'];

$ddl__viaAdmision   = $_REQUEST['ddl_viaAdmision'];
$ddl__convalidante  = $_REQUEST['ddl_convalidante'];
$ddl__regimen       = $_REQUEST['ddl_regimen'];
$ddl__carrera       = $_REQUEST['ddl_carrera'];


if (empty($_REQUEST['matriculado'])) { $matriculado = ""; }
if (empty($_REQUEST['cohorte'])) { $cohorte = 0; }
if (empty($_REQUEST['semestre_cohorte'])) { $semestre_cohorte = 0; }
if (empty($_REQUEST['mes_cohorte'])) { $mes_cohorte = 0; }
if (empty($_REQUEST['ano_egreso'])) { $ano_egreso = $ANO; $semestre_egreso = -1; }
if (empty($_REQUEST['fec_ini_egreso'])) { $fec_ini_egreso = date("Y")."-01-01"; }
if (empty($_REQUEST['fec_fin_egreso'])) { $fec_fin_egreso = date("Y-m-d"); }
if (empty($_REQUEST['moroso_financiero'])) { $moroso_financiero = -1; }
if (empty($_REQUEST['regimen'])) { $regimen = 'PRE'; }
if (empty($_REQUEST['aprob_ant'])) { $aprob_ant = 't'; }
if (empty($cond_base)) { $cond_base = "ae.nombre='Egresado'"; }




$SQL_paises = "SELECT localizacion,nombre FROM public.pais ORDER BY nombre;";
$paises = consulta_sql($SQL_paises);

$SQL_víaAdmision = "select id, nombre from public.admision_tipo where id in (1, 2);";
$víaAdmision = consulta_sql($SQL_víaAdmision);

$SQL_institucionConvalidante = "select id, nombre_original from public.inst_edsup order by nombre_original desc;";
$institucionConvalidante = consulta_sql($SQL_institucionConvalidante);

$SQL_carreraPrograma = "SELECT id_arancel AS id,  carrera AS nombre FROM vista_aranceles_carreras WHERE ano=2023 AND regimen='1. Pregrado' ORDER BY nombre;"; 
$carreraPrograma = consulta_sql($SQL_carreraPrograma);






if(filter_input(INPUT_POST, 'btnGuardar')){
    // Propiedades del archivo
  echo" entrada normal ";

  
}
if(filter_input(INPUT_POST, 'btnGrabaPaso1')){
  // Propiedades del archivo
 
  // Conexión con PostgreSQL
  $conn = pg_connect("host=10.1.1.88 dbname=regacad user=sgu ") or die(" Error al conectar a PostgreSQL");

  // Verificamos si no hay error en la conexión
  if(!$conn){
      $error = pg_last_error($conn);
      die("ERROR: " . $error);
  }

    echo "antes de la query";

  $sql = "INSERT INTO pap (
    rut,
    nombres,
    apellidos,
    fec_nac,
    direccion,
    estado_carpeta_doctos_fecha,
    tipo_docto_ident, 
    email, 
    tel_movil, 
    est_civil, 
    genero, 
    carrera3_post, 
    regimen, 
    nro_docto_ident, 
    pasaporte) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15)";
  $params = array(
    $texto__rut, 
    strtoupper($texto__nombre), 
    strtoupper($texto__apellidos), 
    $texto__fnacimiento, 
    strtoupper($texto__direccion), 
    $texto__fnacimiento, 
    $ddl__tipoDocumento, 
    $texto__email, 
    $texto__celular, 
    $ddl__estadoCivil, 
    $ddl__genero, 
    $ddl__viaAdmision, 
    $ddl__regimen, 
    $texto__ndocumento, 
    $texto__npasaporte);

echo $sql;
echo $params;
//, $16, $17
//, nacionalidad, id_inst_edsup_proced
//, $ddl__pais, $ddl__convalidante

  // Ejecutamos la sentencia preparada
  $result = pg_query_params($conn, $sql, $params);

  if($result){
      $last_insert_id = pg_last_oid($result);
      echo "s<br>Ya guardamos el Paso 1 en la base de datos<br/>";
  } else {
      echo "s<br>Hubo un problema y no se guardó el archivo. " . pg_last_error($conn) . "<br/>";
  }

  pg_close($conn);
}

if(filter_input(INPUT_POST, 'btnGrabaPaso2')){
    // Propiedades del archivo
  echo"viene desde Grabar paso 2  ";
  $elRut = $texto__rut;
  $pre = "PRE";
  $dbconn=pg_connect("host=10.1.1.88 dbname=regacad user=sgu ") or die(" Error al conectar a PostgreSQL");
  //$query="UPDATE public.pap SET regimen = 'PREE' WHERE rut = '123456788-0'";
  $query="UPDATE public.pap SET regimen = '$pre' WHERE rut = '$elRut' ";
  $result=pg_query($dbconn,$query);

  if (!$result){
  echo $query;
  }
  else
  {
  echo "Update listo";
  }

  $row_count= pg_num_rows($result);
  pg_free_result($result);
  pg_close($dbconn);


}

if(filter_input(INPUT_POST, 'btnGrabaPaso3')){
  // Propiedades del archivo
  echo"viene desde Grabar paso 3";
}

if(filter_input(INPUT_POST, 'btnGrabaPaso4')){
    // Propiedades del archivo
  echo"viene desde Grabar paso 4";
}


?>

<head>
    <style>
    
      .Titulo1{
          font-size:22px;
          font-family: Vegur, 'PT Sans', Verdana, sans-serif;
          color: #024289;
          background:#EEEFEE;
      }
      
      .Titulo2{
          font-size:20px;
          font-family: Vegur, 'PT Sans', Verdana, sans-serif;
          color: #024289;
          background:#EEEFEE;
      }

      .labelName{
      	font-weight:600;
      	font-size:18px;
      	color: #024289;;
         font-family: sans-serif;
        font-style: normal;
       }

      .Tabla{
           border-collapse: "collapse";
      }
      
      td {
        margin: 15px;
        padding: 5px;
        border: 1px solid #ccc; /* Establece el borde para las celdas */
      }
      body {
          background: #FFFFFF
      }
            
    </style>
</head>

<body>
<form name="formularioPaso1" action="principal.php?modulo=MatriculaOnline/IngresoMatriculaOnlineAlumno" method="POST" target='_self'>
  <table cellpadding="2" class="Tabla" border="0" align="center" cellspacing="2" width="800px">
    <tr>
      <th align="center" colspan="2" class="Titulo1" width="100%"><b>MATRICULATE ONLINE</b> <br>
      </th>
    </tr> 
    <tr>
      <th align="center" colspan="2" class="Titulo2" width="100%"><b>Paso 1 Datos de Identidad</b> <br>
      </th>
    </tr>
    <tr>
 <br>
    </tr> 
    <tr>
      <td class="labelName" colspan="2">
        Tipo de documento de Identidad: <br>
        <select class="filtro" name="ddl_tipoDocumento" id="id_tipoDocumento" onchange="esRut();" style="visibility: visible;" value="R">
          <option value="0"></option>
            <option value="R" class="1">Rut</option>
            <option value="P" class="2">Pasaporte</option>
        </select>
      </td>
    </tr>  
      <tr>
        <td class="labelName">
          <div id="div_rut" >
          Rut:<br>
          <input type="text" name="texto_rut" align="right"  size="30" id="idTxtNRut" class='boton' value="<?php echo($texto__rut); ?>">
          </div>
        </td>
        <td class="labelName">
          <div id="div_documento" >
            N° de documento:<br>
            <input type="text" name="texto_ndocumento" align="right" size="30" id="idTxtNDocumento" class='boton' value="<?php echo($texto__ndocumento ); ?>">
          </div>
        </td>
      </tr>
     
    <tr>
      <td class="labelName" colspan="2">
        <div id="div_pasaporte" >
          Pasaporte:<br>
          <input type="text" name="texto_npasaporte" align="right" size="30" id="idTxtPasaporte" class='boton' value="<?php echo($texto__npasaporte ); ?>">
        </div>
      </td>
    </tr>
 
    <tr>
      <td class="labelName">
        Nombre:<br>
        <input type="text" name="texto_nombre" align="right" value="<?php echo($texto__nombre); ?>" size="30" id="texto_buscar" class='boton'>
      </td>
      <td class="labelName">
        Apellidos:<br>
        <input type="text" name="texto_apellidos" align="right" value="<?php echo($texto__apellidos); ?>" size="30" id="texto_buscar" class='boton'>
      </td>
    </tr>

    <tr>
      <td class="labelName">
        Correo electrónico:<br>
        <input type="text" name="texto_email" align="right" value="<?php echo($texto__email); ?>" size="30" id="texto_email" class='boton'>
      </td>
       <td class="labelName">
        Teléfono celular:<br>
        <input type="text" name="texto_celular" align="right" value="<?php echo($texto__celular); ?>" size="30" id="texto_celular" class='boton'>
      </td>
    </tr>
    <tr>
      <td class="labelName">
        Fecha de nacimiento:<br>
        <input type="date" name="texto_fnacimiento" align="right" value="<?php echo($texto__fnacimiento); ?>" size="30" id="texto_buscar" class='boton'>
      </td>
      <td class="labelName">
        Estado Civil: <br>
        <select class="filtro" name="ddl_estadoCivil" style="visibility: visible;">
          <option value="0">Seleccione</option>
            <option value="S" class="1">Soltero</option>
            <option value="C" class="1">Casado</option>
            <option value="D" class="1">Divorciado</option>
        </select>
      </td>
    </tr>

    <tr>
      <td class="labelName">
        Genero: <br>
        <select class="filtro" name="ddl_genero" style="visibility: visible;">
          <option value="0">Seleccione</option>
            <option value="m" class="1">Hombre</option>
            <option value="f" class="2">Mujer</option>
        </select>
      </td>


      <td class="labelName">
          Nacionalidad::<br>
          <select class="filtro" name="ddl_pais" onChange="submitform();">
            <option value="">Todos</option>
            <?php echo(select($paises,$id)); ?>
          </select>
        </td>
    </tr>

    <tr>
      <td class="labelName" colspan = "2">
        Dirección:<br>
        <input type="text" name="texto_direccion" align="right" value="<?php echo($texto__direccion); ?>" size="50" id="texto_direccion" class='boton'>
      </td>
    </tr>
    

    <tr>
      
    <td class="labelName">
        Vía de Admisión::<br>
        <select class="filtro" name="ddl_viaAdmision" onChange="submitform();">
          <option value="">Todos</option>
          <?php echo(select($víaAdmision,$id)); ?>
        </select>
      </td>

     <td class="labelName">
        Institucion Convalidante:<br>
        <select class="filtro" name="ddl_convalidante" onChange="submitform();">
          <option value="">Todos</option>
          <?php echo(select($institucionConvalidante, $_REQUEST['ddl_convalidante'])); ?>
        </select>
      </td>
    </tr>
    <tr>
      <td class="labelName">
          Jornada:<br>
        <select class="filtro" name="ddl_genero" style="visibility: visible;">
          <option value="0">Seleccione</option>
            <option value="D" class="1">Diurno</option>
            <option value="V" class="2">Vespertino</option>
        </select>
       
      </td>
    <td class="labelName">
      Carrera Programa:<br>
      <select class="filtro" name="ddl_carrera" onChange="submitform();">
        <option value="">Todos</option>
        <?php echo(select($carreraPrograma, $ddl_carrera)); ?>
      </select>
    </td>

    </tr>
    <tr>
     <td colspan = "2" align="center">
      <input type="submit" name="btnGrabaPaso1" value="Guardar Paso 1" /> 
     </td>
    </tr>
</table>
</form>
</body>


<!-- Fin: <?php echo($modulo); ?> -->


<script type="text/javascript">
$(document).ready(function(){
	$("#sgu_fancybox").fancybox({
		'autoScale'			: false,
		'autoDimensions'	: true,
		'titleShow'         : false,
		'titlePosition'     : 'inside',
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic',
		'width'				: 1000,
		'height'			: 550,
		'maxHeight'			: 600,
		'afterClose'		: function () { location.reload(true); },
		'type'				: 'iframe'
	});
});

$(document).ready(function(){
	$("#sgu_fancybox_small").fancybox({
		'autoScale'			: false,
		'autoDimensions'	: true,
		'titleShow'         : false,
		'titlePosition'     : 'inside',
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic',
		'width'				: 600,
		'height'			: 550,
		'maxHeight'			: 550,
		'afterClose'		: function () { location.reload(true); },
		'type'				: 'iframe'
	});
});

function esRut() {
 
    if (document.getElementById("id_tipoDocumento").value === "R") {
      //document.getElementById("idTxtPasaporte").disabled = true;
      //document.getElementById("idTxtNDocumento").disabled = false;
      //document.getElementById("idTxtNRut").disabled = false;
      document.getElementById("idTxtPasaporte").value = "";

      //document.getElementById("div_rut").style.display="block";
      //document.getElementById("div_documento").style.display="block";
      document.getElementById("div_pasaporte").style.display="none";
      
      
      
    } else {
      document.getElementById("idTxtPasaporte").disabled = false;
      //document.getElementById("idTxtNDocumento").disabled = true;
      //document.getElementById("idTxtNRut").disabled = true;
      //document.getElementById("idTxtNRut").value = "";
      //document.getElementById("idTxtNDocumento").value = "";


      //document.getElementById("div_rut").style.display="none"; 
      //document.getElementById("div_documento").style.display="none";
      document.getElementById("div_pasaporte").style.display="block";
      }
    //textonpasaporte.style.display = ddltipoDocumento.value == "R" ? "block" : "none";
}
</script>



