<?php
include('function/conexion.php');

/*=============================================
INSERTAR DOCUMENTOS
=============================================*/
if (isset($_GET['insertArchivo'])){
date_default_timezone_set("America/Santiago");
$fechas = date("Y-m-d");
$nom = $_POST['nombre_archivo'];
$nomFormat = str_replace ( "C:\\fakepath\\", '', $nom);


  
        $rut = $_POST['rut'];
        $id_tipo = $_POST['id_tipo'];
        $nombre_archivo = $nomFormat;
        $mime = $_POST['mime'];
        $id_usuario=$_POST['id_usuario'];
        $fecha = $fechas;
        $archivo = $_POST['archivo'];
        $id_usuario = $_POST['id_usuario']; 

        $query = "SELECT id FROM doctos_digitalizados WHERE rut ='".$rut."' AND id_tipo = '".$id_tipo."'";
 
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $totalRows = pg_num_rows($result);
        
        //var_dump($totalRows);
 if ($totalRows >= 1){
          echo 3;

          }else{
          
          $sql = "INSERT INTO doctos_digitalizados (rut, id_tipo, nombre_archivo, mime, 
                            fecha, archivo, id_usuario)
                            VALUES ('".$rut."', '".$id_tipo."', '".$nombre_archivo."', '".$mime."',
                            '".$fecha."','".$archivo."', '".$id_usuario."')";    

// Ejecutamos la sentencia preparada
$result = pg_query($dbconn, $sql);

if($result){ 
echo 1;
} else {
//echo "<br>Hubo un problema y no se guardó el archivo. " . pg_last_error($dbconn) . "<br/>";
echo pg_last_error($dbconn);
}
//echo $result ;
pg_close($dbconn);
          }
}

  /*=============================================
  LLAMAR DOCUMENTOS POR ID
  =============================================*/
if (isset($_GET['getDocForUser'])){

$rut = $_GET['idTxtNRut'];

  $query = "SELECT id, (SELECT NOMBRE FROM doctos_digital_tipos WHERE id = id_tipo) as Nom, nombre_archivo FROM doctos_digitalizados WHERE rut ='".$rut."'";
 
  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

  echo '<table class="table">
  <thead>
    <tr>
      <th class="labelName">Tipo Documento</th>
      <th class="labelName">Nombre del archivo</th>
      <th class="labelName">Eliminar</th>
    </tr>
  </thead>
  <tbody>';
 while ($row = pg_fetch_row($result)) {
   echo '
   <tr>
   <td>'.$row[1].'</td>
   <td>'.$row[2].'</td>
   <td td align="center"><button type="button" onclick="deleteArchivo('.$row[0].')" class="btn btn-dark"><i class="fas fa-trash"></i></button></td>
 </tr>';

 }
 echo '   </tbody>
 </table>';
  }
/*=============================================
DELETE DOCUMENTO
=============================================*/
if (isset($_GET['eliminarArchivo'])){

  $id = $_GET['id'];
          $sql = "DELETE FROM doctos_digitalizados WHERE id = ".$id."";    
  
    // Ejecutamos la sentencia preparada
    $result = pg_query($dbconn, $sql);
    
    if($result){ 
    echo 1;
    } else {
    //echo "<br>Hubo un problema y no se guardó el archivo. " . pg_last_error($dbconn) . "<br/>";
    echo pg_last_error($dbconn);
    }
    //echo $result ;
    pg_close($dbconn);
    
    }
?>



