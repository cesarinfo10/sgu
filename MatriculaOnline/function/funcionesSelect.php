<?php
include('conexion.php');
/*=============================================
LLAMAR A TODOS LOS PAISES
=============================================*/
if (isset($_GET['getAllPais'])){

  $query = 'SELECT localizacion, nacionalidad FROM public.pais  ORDER BY nombre ASC';
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
   
  echo '<select class="shadow-lg p-1 bg-white form-control"  name="ddl_pais" id="ddl_pais">
  <option value="">Seleccione</option>';

  while ($row = pg_fetch_row($result)) {
    echo '<option value="'.$row[0].'">'.$row[1].'</option>';
  }
  echo '</select>';
   }
/*=============================================
LLAMAR VIA ADMISIÓN
=============================================*/
if (isset($_GET['getAllAdmision'])){

  $query = "SELECT id, nombre from public.admision_tipo where id in (1,2)";
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
   
  echo '<select class="shadow-lg p-1 bg-white form-control" name="ddl_viaAdmision" id="ddl_viaAdmision"
  onchange="mostrarInstituciones()">
  <option value="">Seleccione</option>';

  while ($row = pg_fetch_row($result)) {
    echo '<option value="'.$row[0].'">'.$row[1].'</option>';
  }
  echo '</select>';
   }

/*=============================================
LLAMAR INSTUTUCIONES CONVALIDANTES
=============================================*/
if (isset($_GET['getAllInstitucion'])){

  $query = "SELECT id, nombre_original from public.inst_edsup where pais='CL' order by nombre_original desc";
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
   
  echo '<select class="shadow-lg p-1 bg-white form-control" name="ddl_convalidante" id="ddl_convalidante">
  <option value="0">Seleccione</option>';

  while ($row = pg_fetch_row($result)) {
    echo '<option value="'.$row[0].'">'.$row[1].'</option>';
  }
  echo '</select>';
   }

/*=============================================
LLAMAR CARRERAS PROGRAMAS
=============================================*/
if (isset($_GET['getAllCarrProg'])){

  date_default_timezone_set("America/Santiago");
  $fecha = date("Y");

  $id_jornada = $_GET['jornada'];

  $query = "SELECT id_carrera, carrera AS nombre,  id_arancel AS id,
  matricula_anual, arancel_contado_anual, matricula_semestral,
  arancel_credito_semestral, modalidad, regimen, id
  FROM vista_aranceles_carreras 
  WHERE ano = '".$fecha."' AND id_jornada = '".$id_jornada."' ORDER BY nombre";
 
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
   
  echo '<select class="shadow-lg p-1 bg-white form-control" name="ddl_carrera" id="ddl_carrera"
  onchange="mostraSaldo()">
  <option value="">Seleccione</option>';

  while ($row = pg_fetch_row($result)) {
    echo '<option value="'.$row[0].'" id="'.$row[2].'" matricula_anual="'.$row[3].'"arancel_contado_anual="'.$row[4].'" 
          matricula_semestral="'.$row[5].'" arancel_credito_semestral="'.$row[6].'"
          modalidad="'.$row[7].'" regimen="'.$row[8].'" regimenID="'.$row[9].'">'.$row[1].'</option>';
  }
  echo '</select>';
   }
  /*=============================================
  LLAMAR TIPOS DOCUMENTOS
  =============================================*/
if (isset($_GET['getAllTipoDoc'])){

  $tipo_admision = $_GET['tipo_admision'];

  $query = "SELECT * FROM vista_doctos_obligatorios WHERE tipo_admision ='".$tipo_admision."' ";
 
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

   echo '<select class="shadow-lg p-1 bg-white form-control" name="ddl_Subirarchivo" id="ddl_Subirarchivo" onchange="mostraSubirArchivo()">
  <option value="">Seleccionar</option>';
  while ($row = pg_fetch_row($result)) {
    echo '<option value="'.$row[0].'" regimen="'.$row[1].'" tipo="'.$row[2].'">'.$row[6].'</option>';
  }
  echo '</select>';
   }


/*=============================================
LLAMAR COMUNAS
=============================================*/
if (isset($_GET['getAllComunas'])){

  $query = "SELECT id, nombre from comunas order by nombre ASC";
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
   
  echo '<select class="shadow-lg p-1 bg-white form-control" name="ddl_comunas" id="ddl_comunas">
  <option value="0">Seleccione</option>';

  while ($row = pg_fetch_row($result)) {
    echo '<option value="'.$row[0].'">'.$row[1].'</option>';
  }
  echo '</select>';
   }
/*=============================================
LLAMAR REGIONES
=============================================*/
if (isset($_GET['getAllRegion'])){

  $query = "SELECT id, nombre from regiones order by nombre ASC";
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
   
  echo '<select class="shadow-lg p-1 bg-white form-control" name="ddl_regiones" id="ddl_regiones">
  <option value="0">Seleccione</option>';

  while ($row = pg_fetch_row($result)) {
    echo '<option value="'.$row[0].'">'.$row[1].'</option>';
  }
  echo '</select>';
   }
