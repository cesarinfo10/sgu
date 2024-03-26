<?php
include('conexion.php');
/*=============================================
INSERTAR MATRICULA
=============================================*/
if (isset($_GET['postInsertar'])){

 
      $rut = $_POST['rut']; //
      $pasaporte = $_POST['pasaporte']; //
      $NDocumento = $_POST['NDocumento']; //
      $tipoDocumento = $_POST['tipoDocumento'];//
      $nombre=$_POST['nombre']; //
      $apellido = $_POST['apellido']; //
      $email = $_POST['email']; //
      $tel_movil = $_POST['tel_movil']; //
      $fnacimiento = $_POST['fnacimiento']; //
      $estadoCivil= $_POST['estadoCivil']; //
      $genero = $_POST['genero']; //
      $pais= $_POST['pais']; //
      $direccion= $_POST['direccion']; //
      $comuna = $_POST['comunas']; 
      $region = $_POST['regiones']; 
      $viaAdmision = $_POST['viaAdmision'];
      $convalidante =$_POST['convalidante'];//
      $id_jornada= $_POST['id_jornada'];
      $carrera = $_POST['carrera']; //
      $modalidad1_post = $_POST['modalidad1_post']; //
      $regimen = $_POST['regimen']; //

      $sql = "INSERT INTO pap (rut,nombres,apellidos,fec_nac,direccion, comuna, region, tipo_docto_ident, email, 
                              tel_movil, nacionalidad, est_civil, genero, carrera3_post, 
                              id_inst_edsup_proced, nro_docto_ident, pasaporte, modalidad1_post, regimen)
                              VALUES ('".$rut."', '".$nombre."', '".$apellido."', '".$fnacimiento."', '".$direccion."',
                              '".$comuna."','".$region."','".$tipoDocumento."','".$email."', '".$tel_movil."','".$pais."', '".$estadoCivil."',
                              '".$genero."', '".$carrera."', '".$convalidante."','".$NDocumento."', '".$pasaporte."', '".$modalidad1_post."',
                              '".$regimen."')";    
      
        // Ejecutamos la sentencia preparada
        $result = pg_query($dbconn, $sql);
      
        if($result){ 
         /* $query = "SELECT id FROM usuarios where rut = '".$rut."'";
         
          $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());*/
          echo 1;
          while ($row = pg_fetch_row($result)) {
            echo $row[0];
          }

        } else {
            echo "<br>Hubo un problema y no se guardó el archivo. " . pg_last_error($dbconn) . "<br/>";
            echo 2;
        }
      //echo $result ;
       pg_close($dbconn);

     }