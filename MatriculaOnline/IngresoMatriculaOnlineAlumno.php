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

date_default_timezone_set("America/Santiago");
$fcha = date("Y-m-d");
?>

<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="MatriculaOnline/css/mat.css">
    <link rel="stylesheet" href="MatriculaOnline/css/bootstrap.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" crossorigin="anonymous">
    
    <link href="/MatriculaOnline/js/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/MatriculaOnline/js/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/MatriculaOnline/js/switchery/dist/switchery.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  
  <table cellpadding="3" class="table table-bordered" border="0" align="center" cellspacing="2" width="800px">
    <tr>
      <th align="center" colspan="3" class="text-left Titulo1" width="100%"><h4 class="text-center">MATRICULATE ONLINE</h4>
      </th>
    </tr> 
    <tr>
      <th align="center" colspan="3" class="Titulo2" width="100%"><h5 class="text-center">Paso 1 Datos de Identidad</h5>
      </th>
    </tr>
    <tr>
 <br>
    </tr> 
    <tr>
      <td class="labelName" colspan="3">
        Tipo de documento de Identidad:<br>
        <select class="filtro form-control" name="ddl_tipoDocumento" id="id_tipoDocumento" onchange="esRut();" style="visibility: visible;" value="R">
          <option value="0">Seleccione</option>
            <option value="R" class="1">Rut</option>
            <option value="P" class="2">Pasaporte</option>
        </select>
      </td>
    </tr>  
      <tr>
        <td class="labelName" style="width: 33.4%">
          <div class="row">
          <div class="col-xs-3">
          Rut:<br>
          <input type="text" name="idTxtNRut" align="right" id="idTxtNRut" maxlength="10" class='form-control boton' oninput="checkRut(this)" value="">
          </div>
          </div>
        </td>
        <td class="labelName" style="width: 33.4%">
        <div class="row">
          <div class="col-xs-3">

            N° de documento:<br>
            <input type="text" name="texto_ndocumento" align="right" maxlength="9" id="idTxtNDocumento" class='form-control boton' value="">
            </div>
          </div>
        </td>

      <td class="labelName" colspan="2">
        <div id="div_pasaporte" >
          Pasaporte:<br>
          <input type="text" name="texto_npasaporte" align="right" maxlength="50" id="idTxtPasaporte" class='form-control boton' value="">
        </div>
      </td>
    </tr>
 </table>
 <table cellpadding="3" class="table table-bordered" border="0" align="center" cellspacing="2" width="800px">
    <tr>
      <td class="labelName">
        Nombre:<br>
        <input type="text" name="texto_nombre" align="right" value="" maxlength="50" id="texto_nombre" class='form-control boton'>
      </td>
      <td class="labelName">
        Apellidos:<br>
        <input type="text" name="texto_apellidos" align="right" value="" maxlength="50" id="texto_apellidos" class='form-control boton'>
      </td>
    </tr>
    </table>
    
 <table cellpadding="3" class="table table-bordered" border="0" align="center" cellspacing="2" width="800px">
    <tr>
      <td class="labelName">
        Correo electrónico:<br>
        <input type="mail" name="texto_email" align="right" value="" maxlength="50" id="texto_email" class='form-control boton'>
      </td>
       <td class="labelName">
        Teléfono celular:<br>
        <input type="text" name="texto_celular" align="right" value=""maxlength="15" id="texto_celular" class='form-control boton'>
      </td>
      <td class="labelName">
        Fecha de nacimiento:<br>
        <input type="date" class="form-control boton"  name="texto_fnacimiento" align="right" value="<?php echo $fcha; ?>" size="30" id="texto_fnacimiento" class='boton'>
      </td>
      </tr>
      </table>

      <table cellpadding="3" class="table table-bordered" border="0" align="center" cellspacing="2" width="800px">
      <tr>
      <td class="labelName" style="width: 33.4%">
        Estado Civil: <br>
        <select class="shadow-lg p-1 bg-white form-control"  name="ddl_estadoCivil" id="ddl_estadoCivil" style="visibility: visible;">
          <option value="0">Seleccione</option>
            <option value="S" class="1">Soltero</option>
            <option value="C" class="1">Casado</option>
            <option value="D" class="1">Divorciado</option>
            <option value="A" class="1">AUC</option>
        </select>
      </td>
      <td class="labelName" style="width: 33.4%">
        Genero: <br>
        <select class="shadow-lg p-1 bg-white form-control" id="ddl_genero" name="ddl_genero" style="visibility: visible;">
          <option value="0">Seleccione</option>
            <option value="m" class="1">Hombre</option>
            <option value="f" class="2">Mujer</option>
            <option value="o" class="3">Otro</option>
        </select>
      </td>
      <td class="labelName">
          Nacionalidad:<br>
          <div id="ddl_paises"></div>
       
      </td>
    </tr>
    </table>
 <table cellpadding="3" class="table table-bordered" border="0" align="center" cellspacing="2" width="800px">
    <tr>
      <td class="labelName" colspan = "2">
        Dirección:<br>
        <input type="text" name="texto_direccion" align="right" value="" maxlength="200" id="texto_direccion" class='form-control boton'>
      </td>
    </tr>
    
    <tr>
      <td class="labelName" style="width: 50%">
      Region:<br>
          <div id="ddl_region"></div>
      </td style="width: 50%">
      <td class="labelName">
      Comuna:<br>
          <div id="ddl_comuna"></div>
       
      </td>
    </tr>
  <tr>
      <td class="labelName" style="width: 50%">
          Prosecución :<br>
          <div id="ddl_admisiones"></div>

      </td style="width: 50%">
      <td class="labelName" id="instConv">
          Institución Origen:<br>
          <div id="ddl_convalidantes"></div>
       
      </td>
    </tr>



    <tr>
      <td class="labelName" style="width: 50%">
          Jornada:<br>
        <select class="shadow-lg p-1 bg-white form-control" name="jornada" id="jornada" onchange="llamarProgCarrera();" style="visibility: visible;">
          <option value="0">Seleccione</option>
            <option value="D" class="1">Diurno</option>
            <option value="V" class="2">Vespertino</option>
        </select>
       
      </td>
      <td class="labelName" style="width: 50%">
          Carrera/Programa<br>
          <div id="ddl_carreras">
          <select class="shadow-lg p-1 bg-white form-control" style="visibility: visible;">
          <option value="0">Seleccione</option>

        </select>
          </div>
       
      </td>
    </tr>



    <tr>
    <td class="labelName" >
        Modalidad:<br>
        <input type="text" name="texto_modalidad" align="right" value="" maxlength="200" id="texto_modalidad" class='form-control boton' disabled>
      </td>
     <td class="labelName" >
        Regimen:<br>
        <input type="text" name="texto_regimen" align="right" value="" maxlength="200" id="texto_regimen" class='form-control boton' disabled>
     </td>
    </tr>
    <tr>
  
     <td  align="center" colspan="2">
     <br>
      <input type="button" class="btn btn-warning boton" name="btnGrabaPaso1" value="Guardar Paso 1" onclick="imprimirContrato()"/> 

     </td>
    </tr>
</table>
</form>

<!-- --------------------------- ARCHIVOS --------------------------- -->
<br/>

<table cellpadding="2" class="table table-bordered" border="0" align="center" cellspacing="2" width="800px">
<tr>
<tr>
      <td align="center"  class="Titulo2" width="50%"><h5 class="text-center">Documentos</h5>
      <select class="shadow-lg p-1 bg-white form-control" id="ddl_archivos" onchange="llamarTipoDoc();" name="ddl_archivos" style="visibility: visible;">
                <option value="0">Seleccione</option>
                  <option value="Regular" class="1">Alumno regular</option>
                  <option value="2" class="2">Licenciaturas</option>
                  <option value="Prosecución" class="2">Convalidaciones</option>
              </select>

    </td>
    <td align="center"  class="Titulo2" width="50%"><h5 class="text-center">Tipos Documentos</h5>
        <div id="subArchivos"></div>
    </td>
      
    </tr>
<tr>
      <td class="labelName text-center" colspan="2">
      <label for="docAlmunReg"><div id="textoAdjunto"></div></label><br>
      <input type="file" id="docUdate" onchange="return fileValidation()" accept=".jpg, .jpeg, .pdf"> 

      </td>

    </tr>
</table>

<div id="ddl_tableArchivo"></div>



<br/>

<table cellpadding="2" class="table table-bordered" border="0" align="center" cellspacing="2" width="800px">
<tr>
<tr>
      <td align="center"  class="Titulo2" colspan="2" ><h5 class="text-center">Forma de Pago</h5>
      <select class="shadow-lg p-1 bg-white form-control" id="ddl_TipoPago" onchange="llamarTipoDocMat()" name="ddl_TipoPago" style="visibility: visible;">
                <option value="0">Seleccione Forma de Pago</option>
                  <option value="1">Webpay</option>
                  <option value="2">Otros</option>
              </select>

    </td>
    </tr>
    <tr id="Mat">
        <td>Monto: <input type="text" name="" class='form-control boton' value=""  id="matAnual" class='boton' disabled></td>
        <td>Monto: <input type="text" name="" class='form-control boton' value=""  id="aranAnual" class='boton' disabled></td>
    </tr>
    <tr id="MatPay">
    <td align="center" colspan="2" >
    <button type="button" class="btn btn-danger" onclick="pagarMatricula();">$ PAGAR</button>
    </td>
    </tr>
    <tr id="otros">
        <td>Monto: <input type="text" name="" class='form-control boton' value=""  id="matSemestral" class='boton' disabled></td>
        <td>Monto: <input type="text" name="" class='form-control boton' value=""  id="aranCreditoSemes" class='boton' disabled></td>
    </tr>
    <tr id="otrosPay">
    <td align="center" colspan="2" >
    <button type="button" class="btn btn-danger" onclick="pagarMatriculaOtros();">$ PAGAR</button>
    </td>
    </tr>
</table>

<br>
<table cellpadding="2" class="table table-bordered" border="0" align="center" cellspacing="2" width="800px">
<tr>
<tr>
      <td align="center"  class="Titulo2" colspan="2" >
      <button type="button" id="btnSubir" onclick="finalizarCarga()" class="btn btn-default boton">Finalizar Matricula</button> 

    </td>
    </tr>
  </table>

</body>

<!-- MATRICULA ANUAL web-->


<br/>
<br/>
<!-- MATRICULA SEMESTRAL otros-->
<input type="hidden" name=""  value="" size="20" id="matSemestral" class='boton'>
<input type="hidden" name=""  value="" size="20" id="aranCreditoSemes" class='boton'>

<!-- MATRICULA SEMESTRAL-->
<input type="hidden" name=""  value="<?php echo $_SESSION['id_usuario']; ?>" size="20" id="idUsuario" class='boton'>



<!-- Fin: <?php echo($modulo); ?> -->

<script src="/MatriculaOnline/js/matricula.js"></script>
<script src="/MatriculaOnline/js/test.js"></script>
<script src="/MatriculaOnline/js/switchery/dist/switchery.min.js"></script>
<script src="/MatriculaOnline/js/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<script src="/MatriculaOnline/js/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>