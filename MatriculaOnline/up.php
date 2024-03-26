



<?php

if(filter_input(INPUT_POST, 'btnGuardar')){
    // Propiedades del archivo
  echo"entrando rre llamado";
    $archivo_nombre = $_FILES['archivo']['name'];
    $archivo_tipo = $_FILES['archivo']['type'];
    $archivo_temp = $_FILES['archivo']['tmp_name'];

    // Conexión con PostgreSQL
    $conn = pg_connect("host=10.1.1.88 dbname=regacad user=sgu ") or die(" Error al conectar a PostgreSQL");

    // Verificamos si no hay error en la conexión
    if(!$conn){
        $error = pg_last_error($conn);
        die("ERROR: " . $error);
    }

    echo "<br>Ya guardamos el archivo en la base de datos<br/>";
    
    // Convertir la imagen en código binario
    $archivo_binario = pg_escape_bytea(file_get_contents($archivo_temp));

    //$sql = "INSERT INTO archivos (nombre, tipo, archivo) VALUES ('1', '2', '3')";
    //$params = array($archivo_nombre, $archivo_tipo, $archivo_binario);

    $sql = "INSERT INTO archivos (nombre, tipo, archivo) VALUES ($1, $2, $3)";
    $params = array($archivo_nombre, $archivo_nombre, $archivo_binario);

    // Ejecutamos la sentencia preparada
    $result = pg_query_params($conn, $sql, $params);

    if($result){
        $last_insert_id = pg_last_oid($result);
        echo "s<br>Ya guardamos el archivo en la base de datos<br/>";
        echo "&Uacute;ltimo id insertado: <a href='ver.php?id=" . $last_insert_id . "'>" . $last_insert_id . "</a>";
    } else {
        echo "s<br>Chanfle, hubo un problema y no se guardó el archivo. " . pg_last_error($conn) . "<br/>";
    }

    pg_close($conn);
}
?>
		<h3>Guardar un archivo en MySQL</h3>
    <form action="/MatriculaOnline/up.php" method="POST" target='_self'>
      <input type="file" name="archivo" /><br/><br/>
      <input type="submit" name="btnGuardar" value="Guardar" />
    </form>


    