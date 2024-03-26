<?php
$dbconn = pg_connect("host=10.1.1.88 dbname=regacad user=sgu")
         or die('No se ha podido conectar: ' . pg_last_error());