<link href="/assets/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
<?php
  function sacaSala($id_campo_capacitaciones) {
    $ss = "select concat('en sala',' ',coalesce(nombre_largo,nombre),' (',trim(codigo),'), piso ', piso,'°') sala from salas 
    where 
    codigo = (select sala from asiscapac_capacitaciones where id = $id_campo_capacitaciones)
    ";    
    $sql     = consulta_sql($ss);

    //echo("<br>".$ss);


    extract($sql[0]);
    return $sala;

  }
  function sacaEstadoCapacitacion($id_campo_capacitaciones) {

    $ss = "
      select id_asiscapac_estado from asiscapac_capacitaciones
      where id = $id_campo_capacitaciones
    ";
    $sql     = consulta_sql($ss);

    //echo("<br>".$ss);
 

    extract($sql[0]);
    return $id_asiscapac_estado;
}

function existenRegistros($ano, 
        //$id_asiscapac_origen, 
        $id_capacitacion, 
        $id_usuario) {

        try {
        $ss = "
        select count(*) as cuenta from asiscapac_capacitaciones_funcionarios
        where
        ano = $ano 
        and id_asiscapac_capacitaciones = $id_capacitacion
        and id_usuario = $id_usuario
        "; 



        $sqlCuenta     = consulta_sql($ss);

//        echo("<br>".$ss);


        extract($sqlCuenta[0]);
        } catch (Exception $e) {
        $cuenta = 0;
        }

        return $cuenta;

}



if (!$_SESSION['autentificado']) {
	header("Location: index.php");
	exit;
}
 
include("validar_modulo.php");
$modulo_destino = "ver_alumno";

$id_profesores_seleccionados = $_REQUEST['id_profesores_seleccionados'];
$id_estado_evidencia = $_REQUEST['id_estado_evidencia'];
$convocar = $_REQUEST['convocar'];

$ids_carreras = $_SESSION['ids_carreras'];
$id_usuario = $_SESSION['id_usuario'];

$cant_reg = $_REQUEST['cant_reg'];
if (empty($_REQUEST['cant_reg'])) { $cant_reg = 30; }
$tot_reg  = 0;
 
$reg_inicio = $_REQUEST['r_inicio'];
if ($reg_inicio=="") { $reg_inicio = 0; }

$modo = $_REQUEST['modo'];
$grabar      = $_REQUEST['grabar'];

$ano            = $_REQUEST['ano'];
$id_ordenar_apellido= $_REQUEST['id_ordenar_apellido'];

$id_ordenar_validada  = $_REQUEST['id_ordenar_validada'];
$id_ordenar_revocada  = $_REQUEST['id_ordenar_revocada'];
$id_ordenar_ambas     = $_REQUEST['id_ordenar_ambas'];

$id_unidad = $_REQUEST['id_unidad'];
$id_origen      = $_REQUEST['id_origen']; 
$id_tipo      = $_REQUEST['id_tipo'];

$id_usuario_confirmar  = $_REQUEST['id_usuario_confirmar'];
$confirmar = $_REQUEST['confirmar'];

//$id_tipo_general_capacitacion = $_REQUEST['id_tipo_general_capacitacion'];
//$id_subtipo_capacitacion = $_REQUEST['id_subtipo_capacitacion'];


$id_tipo_check      = $_REQUEST['id_tipo_check'];
$id_estado      = $_REQUEST['id_estado'];
$id_descripcion      = $_REQUEST['id_descripcion'];
$fec_ini_asist   = $_REQUEST['fec_ini_asist'];
$fec_fin_asist   = $_REQUEST['fec_fin_asist'];
$duracion_minutos  = $_REQUEST['duracion_minutos'];
$id_recordar      = $_REQUEST['id_recordar'];
$id_link_zoom  = $_REQUEST['id_link_zoom'];

$eliminar_evidencia = $_REQUEST['eliminar_evidencia'];
$confirmar_eliminar_evidencias = $_REQUEST['confirmar_eliminar_evidencias'];
$id_doctos_digitalizados = $_REQUEST['id_doctos_digitalizados'];

$id_campo_capacitaciones  = $_REQUEST['id_campo_capacitaciones'];
$id_estado_check = $_REQUEST['id_estado_check'];


$id_mes      = $_REQUEST['id_mes'];
$id_unidad_dos  = $_REQUEST['id_unidad'];
$id_usuario_seleccionado  = $_REQUEST['id_usuario_seleccionado'];




$SQL = "select to_char(periodo_desde,'YYYY') ano_vigente  from periodo_eval where activo = 't';"; 
$ano_vigente = consulta_sql($SQL);
extract($ano_vigente[0]);

if ($ano == "") {
  $ano = $ano_vigente;
}

$SQL = "select min(ano) ano_min_db from asiscapac_capacitaciones;"; 
$anitos = consulta_sql($SQL);
extract($anitos[0]);

if ($ano_min_db == "") {
  $ano_min_db = $ano_vigente;
} 



if ($eliminar_evidencia=="SI") {  
	$mensaje = "Se eliminará evidencia nº$id_doctos_digitalizados\\n"
		         . "Está seguro(a)?";
    //$linkAquiMismo = "capac_usuarios_evidencias&ano=$ano&id_origen$id_origen&id_estado=$id_estado&id_campo_usuario_capacitaciones=$id_asiscapac_usuario_capacitaciones&ocultar_uno=$accion_etiqueta_uno&ocultar_dos=$accion_etiqueta_dos&id_doctos_digitalizados=$id_doctos_digitalizados&eliminar_evidencia=SI&confirmar_eliminar_evidencias=SI";         
    $linkAquiMismo = "capac_usuarios_evidencias&ano=$ano&id_origen=$id_origen&id_campo_capacitaciones=$id_campo_capacitaciones&id_estado_check=$id_estado_check&id_usuario_confirmar=$id&confirmar=NO&id_doctos_digitalizados=$id_doctos_digitalizados";
		$url_si = "$enlbase=$linkAquiMismo&eliminar_evidencia=&confirmar_eliminar_evidencias=SI";
		$url_no = "$enlbase=$linkAquiMismo&eliminar_evidencia=&confirmar_eliminar_evidencias=NO";
		echo(confirma_js($mensaje,$url_si,$url_no));
  
}
if ($confirmar_eliminar_evidencias=="SI") {  
  $SQL = "delete from capac_doctos_digitalizados where id = $id_doctos_digitalizados;"; 
                         // echo("<br>$SQL");
  consulta_dml($SQL);  
  $eliminar_evidencia = ""; 
  $confirmar_eliminar_evidencias = "";
}  



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

//VERIFICA SI id_actividad corresponde al año, sino entonces id_actividad = ''

if ($confirmar <> "") {
  if ($id_usuario_confirmar <> "") {
    $cuenta = existenRegistros($ano, 
                    //$id_asiscapac_origen, 
                    $id_campo_capacitaciones, 
                    $id_usuario_confirmar);
    //echo("<br>cuenta = $cuenta");
    if ($confirmar == "SI") {
      $bConfirmar = 't';
    } else {
      $bConfirmar = 'f';
    }
    if ($cuenta==0) {

          //$fecha = date("Y-m-d");
          $SQL = "
          insert into asiscapac_capacitaciones_funcionarios
          (ano, 
          id_asiscapac_capacitaciones, 
          id_usuario, 
          convocado,
          confirmado
          ) 
          (select 
          $ano, 
          $id_campo_capacitaciones, 
          id,
          't',
          '$bConfirmar'
          from usuarios where id = $id_usuario_confirmar
          )
          ;";
    //                       echo("<br>$SQL");
          if (consulta_dml($SQL) > 0) {

          } else {
          }                  
    } else {
          $SQL = "
          update asiscapac_capacitaciones_funcionarios
          set 
          confirmado = '$bConfirmar'
          where 
          ano = $ano
          and id_asiscapac_capacitaciones = $id_campo_capacitaciones
          and id_usuario = $id_usuario_confirmar
          ;"; 
    //                        echo("<br>$SQL");
          consulta_dml($SQL);      
    }











  }
}

$estado_actividad = "";
$strActividad = "";
if ($id_campo_capacitaciones<>"") {
  $estado_actividad = sacaEstadoCapacitacion($id_campo_capacitaciones);
  if ($estado_actividad == 1) {
    $strActividad = "PROGRAMADA";
  }
  if ($estado_actividad == 2) {
    $strActividad = "EJECUTADA";
  }
  if ($estado_actividad == 3) {
    $strActividad = "CERRADA";
  }
  if ($estado_actividad == 4) {
    $strActividad = "SUSPENDIDA";
  }

}







if ($id_campo_capacitaciones <> "") {
  $SQL_actCorrige = "select count(*) as cuenta from asiscapac_capacitaciones
  where id = $id_campo_capacitaciones and ano = $ano";
  $actCorrige = consulta_sql($SQL_actCorrige);
  extract($actCorrige[0]);
  if ($cuenta == 0) {
    $id_campo_capacitaciones = "";
  }
  //echo("<br>CUENTA = $cuenta");
}
//////FIN CORRIGE

if ($id_campo_capacitaciones<>"") {
  try {
    $ss_act = "
    select 
    to_char(fecha_inicio,'DD-tmMon-YYYY')  as fecha_inicio, 
    to_char(fecha_termino,'DD-tmMon-YYYY')  as fecha_termino, 
    duracion as duracion_capacitacion
    from asiscapac_capacitaciones
    where
    id = $id_campo_capacitaciones
    "; 
  
  
  
    $sql_act     = consulta_sql($ss_act);
  
          echo("<br>".$ss);
  
    extract($sql_act[0]);
    } catch (Exception $e) {
      $fecha_inicio = "";
      $fecha_termino = "";
      $duracion_actividad = "";
    }
  
}



/*********************************************************************************************************************************** */
/*********************************************************************************************************************************** */
/*********************************************************************************************************************************** */
if ($convocar <> "") {
  if ($id_profesores_seleccionados == "") {
    echo(msje_js("No tiene convocados para enviár correo."));          
  } else {
//    echo("*** *** ***");

    $puedeSeguir = true;
    if ($puedeSeguir) {
//      echo("<br>id_profesores_seleccionados = $id_profesores_seleccionados");
      $usuarios = explode(",",trim($id_profesores_seleccionados));
//      echo("count usuarios = ".count($usuarios));
      for ($x=0;$x<count($usuarios);$x++) {
             // $id_usuario_seleccionado 	= $usuarios[$x];
//              echo("<br>usuario seleccionado = ".$id_usuario_seleccionado);



              $cuenta = existenRegistros($ano, 
                                    //$id_asiscapac_origen, 
                                    $id_campo_capacitaciones, 
                                    $usuarios[$x]);
                //echo("<br>cuenta = $cuenta");
              if ($cuenta==0) {

                      //$fecha = date("Y-m-d");
                      $SQL = "
                      insert into asiscapac_capacitaciones_funcionarios
                      (ano, 
                      id_asiscapac_capacitaciones, 
                      id_usuario, 
                      convocado
                      ) 
                      (select 
                      $ano, 
                      $id_campo_capacitaciones, 
                      id,
                      't'
                      from usuarios where id = $usuarios[$x]
                      )
                      ;";
//                       echo("<br>$SQL");
                      if (consulta_dml($SQL) > 0) {

                      } else {
                      }                  
              } else {
                      $SQL = "
                      update asiscapac_capacitaciones_funcionarios
                      set 
                      convocado = 't'
                      where 
                      ano = $ano
                      and id_asiscapac_capacitaciones = $id_campo_capacitaciones
                      and id_usuario = $usuarios[$x]
                      ;"; 
//                        echo("<br>$SQL");
                      consulta_dml($SQL);      
              }


              //
              //BUSCAR  A LOS USUARIOS PARA ENVIAR CORREO DERIVACION DE AREA
              //
              //if ($id_area_derivacion <> "") {
                      $SQL_correo = "select email as email_usuario, 
                      nombre_usuario as nombre_usuario_operador, 
                      nombre as nombre_operador, 
                      apellido as apellido_operador  
                      from usuarios where id in ($usuarios[$x])
                      and email is not null
                      ";

                      $envio_correo = consulta_sql($SQL_correo);
                      $envioMensaje = false;
                      for ($y=0;$y<count($envio_correo);$y++) {
                              extract($envio_correo[$y]);
                              //AQUI DEBE ENVIAR CORREO
                              $sql_act = "select descripcion act_descripcion, 
                                    to_char(fecha_inicio,'DD \"de\" tmMonth \"de\" YYYY') act_fecha_inicio, 
                                    to_char(fecha_termino,'DD \"de\" tmMonth \"de\" YYYY') act_fecha_termino, 
                                      link_capacitaciones act_link 
                                      from asiscapac_capacitaciones 
                                      where id = $id_campo_capacitaciones";
                              $my_act = consulta_sql($sql_act);
                              extract($my_act[0]);


                              $asunto = "SGU: Convocatoria para $act_fecha_inicio : $act_descripcion";
/*
                              $cuerpo = "Sr(a) $nombre_operador $apellido_operador le informa que se ha creado una nueva convocatoria relacionada con con la actividad '$act_descripcion' \n";
                              $cuerpo .= "la cual comienza el $act_fecha_inicio \n";
                              $cuerpo .= "presione el siguiente enlace para unirse : $act_link_zoom \n";            
                              $cuerpo .= "\n\n\n";
                              $cuerpo .= "\n\n\n";
                              $cuerpo .= "\n\n";
                              $cuerpo .= "Este es un correo automático, favor no responder.";
*/
$prox_ano = $ano; //($ANO);
if ($act_link!= "") { //OBLIGATORIA ONLINE
  $cuerpo = "Sr(a) $nombre_operador $apellido_operador, \n\n";
  $cuerpo .= "Informamos que se ha creado una nueva convocatoria de Capacitación, relacionada con '$act_descripcion'\n";
  $cuerpo .= "la cual estará comprendida entre $act_fecha_inicio y $act_fecha_termino.\n\n";
  $cuerpo .= "Recuerde ingresar a la inscripción con su correo institucional (@corp.umc.cl) y no compartir el link. Presione el siguiente enlace para unirse $act_link \n\n";
  $cuerpo .= "Agradecemos desde ya su participación. Esta capacitación es parte integral de la Evaluación del Desempeño $prox_ano.\n\n";
  $cuerpo .= "Saludos cordiales.\n\nUnidad de Recursos Humanos\nUniversidad Miguel de Cervantes";
} else {
  //OBLIGATORIA PRESENCIAL
  $sala = sacaSala($id_campo_capacitaciones);
  $cuerpo = "Sr(a) $nombre_operador $apellido_operador, \n\n";
  $cuerpo .= "Informamos que se ha creado una nueva convocatoria de capacitación, relacionada con '$act_descripcion' ";
  $cuerpo .= "la cual estará comprendida entree $act_fecha_inicio y $act_fecha_termino.\n\n";
  //$cuerpo .= "Esta será de carácter presencial en la Universidad Miguel de Cervantes, <<<Salón auditorio Bernado Leighton, piso 7>>>.\n\n";
  $cuerpo .= "Esta será de carácter presencial en la Universidad Miguel de Cervantes, $sala.\n\n";
  $cuerpo .= "Agradecemos desde ya su participación. Esta capacitación es parte integral de la Evaluación del Desempeño $prox_ano.\n\n";
  $cuerpo .= "Saludos cordiales.\n\nUnidad de Recursos Humanos\nUniversidad Miguel de Cervantes";
}

                              $cabeceras = "From: SGU" . "\r\n"
                                          . "Content-Type: text/plain;charset=utf-8" . "\r\n";

                              //                mail($email_usuario,$asunto,$cuerpo,$cabeceras);
                              //if ($y == 0) {
                                //mail("rmazuela@corp.umc.cl",$asunto,$cuerpo,$cabeceras);
                                mail("dcarreno@corp.umc.cl",$asunto,$cuerpo,$cabeceras);
                                $envioMensaje = true;
                              //}

                      }
          

              //}
              }

              if ($envioMensaje) {
                        echo(msje_js("Se ha se han enviado correctamente los correos con la convocatoria."));
//                echo("VAMOOOO");
              }
                
      }


      
  }
}

if ($id_origen != "") {
//  echo("<br>estoy en UNO");
//  echo("<br>estoy en id_campo_capacitaciones = $id_campo_capacitaciones");
  if ($id_campo_capacitaciones != "") 

  {
    //echo("<br>estoy en DOS");

        $SQL_funcionarios = "
        select
        concat(gu.alias,' - ', gu.nombre) nombre_unidad, 
        $ano ano,
        $id_campo_capacitaciones,
'' campo_glosa_actividades,
        (select glosa from asiscapac_origen where id = $id_origen) origen, 
        (
          select id from asiscapac_capacitaciones_funcionarios
        where ano = $ano
        and id_asiscapac_capacitaciones = $id_campo_capacitaciones
        and id_usuario = u.id
        ) id_asiscapac_capacitaciones,
0  duracion_minutos_funcionario,
0 id_campo_check,        
        (coalesce(
                (select glosa from asiscapac_actividades_funcionarios_check
                where id = 
                        (
                              select id_asiscapac_actividades_funcionarios_check from asiscapac_capacitaciones_funcionarios
                              where ano = $ano
                              and id_asiscapac_capacitaciones = $id_campo_capacitaciones
                              and id_usuario = u.id 
                        )
                )
                ,'Sin Estado'
                )
        ) glosa_campo_check,     
        (
          select observacion from asiscapac_capacitaciones_funcionarios
        where ano = $ano
        and id_asiscapac_capacitaciones = $id_campo_capacitaciones
        and id_usuario = u.id
       ) observacion,
       (
        select observacion_revocar from asiscapac_capacitaciones_funcionarios
      where ano = $ano
      and id_asiscapac_capacitaciones = $id_campo_capacitaciones
      and id_usuario = u.id
     ) observacionrevocar,


               (

                     select CASE convocado WHEN 't' THEN 'SI' ELSE 'NO' END AS convocado
                     from asiscapac_capacitaciones_funcionarios
                     where ano = $ano
                     and id_asiscapac_capacitaciones = $id_campo_capacitaciones
                     and id_usuario = u.id 



               ) convocado,
(
  select 
        CASE when ((
          select count(*) 
          from asiscapac_capacitaciones_funcionarios
          where confirmado = 't' and fecha_aceptacion is not null
          and id = cf.id
        )> 0) THEN 'SI' 
        ELSE (
          CASE when ((
            select count(*) 
            from asiscapac_capacitaciones_funcionarios
            where confirmado = 'f' and fecha_revocar is not null
            and id = cf.id
          ) > 0) THEN 'NO' 
          ELSE 
            'NADA'
          END
          )
        END AS confirmado
        from asiscapac_capacitaciones_funcionarios cf
        where ano = $ano
        and id_asiscapac_capacitaciones = $id_campo_capacitaciones
        and id_usuario = u.id 
        ) confirmado,




               u.id id,
               u.nombre_usuario nombre_usuario,
               u.nombre nombre,
               u.apellido apellido,
               u.email email
               

        from usuarios u, gestion.unidades gu
        where 
          u.tipo <> 3 
          and u.activo
          and u.id_unidad is not null
          and gu.id = u.id_unidad

            AND u.fecha_ingreso::date <= (SELECT fecha_inicio::date
                                    FROM   asiscapac_capacitaciones
                                    WHERE  id = $id_campo_capacitaciones)							   
            AND coalesce(u.fecha_desvinculacion::date, 
                                                  (SELECT fecha_inicio::date
                                                  FROM   asiscapac_capacitaciones
                                                  WHERE  id = $id_campo_capacitaciones)			
                        
                          ) >= (SELECT fecha_inicio::date
                                          FROM   asiscapac_capacitaciones
                                          WHERE  id = $id_campo_capacitaciones)
            and exists (select 1 from asiscapac_capacitaciones_funcionarios
                    where ano = $ano
                    and id_asiscapac_capacitaciones = $id_campo_capacitaciones
                    and id_usuario = u.id      
                    and  convocado = 't'
                    )                  
           ";
      
      
        if ($id_unidad!="" ) {
            $SQL_funcionarios = $SQL_funcionarios." and u.id_unidad = $id_unidad";
        }

        if ($id_estado_check!="") {
          if ($id_estado_check!="1") {
            if ($id_estado_check!="0") {
              $SQL_funcionarios = $SQL_funcionarios." 
              AND (SELECT id_asiscapac_actividades_funcionarios_check
              FROM   asiscapac_capacitaciones_funcionarios
              WHERE  ano = $ano
                      AND id_asiscapac_capacitaciones = $id_campo_capacitaciones
                      AND id_usuario = u.id) = $id_estado_check
               
              ";

            } else {
              $SQL_funcionarios = $SQL_funcionarios." 
              and (not exists (
                SELECT id_asiscapac_actividades_funcionarios_check
                            FROM   asiscapac_capacitaciones_funcionarios
                            WHERE  ano = $ano
                                  AND id_asiscapac_capacitaciones = $id_campo_capacitaciones
                                  AND id_usuario = u.id			
              )
              )		
              ";

            }

          } else { //CONVOCADO = 1
            $SQL_funcionarios = $SQL_funcionarios." 
            AND (SELECT CASE convocado WHEN 't' THEN 'SI' ELSE 'NO' END 
            FROM   asiscapac_capacitaciones_funcionarios
            WHERE  ano = $ano
                    AND id_asiscapac_capacitaciones = $id_campo_capacitaciones
                    AND id_usuario = u.id) = 'SI'
             
            ";

          }

           // }
        }
        
        if ($id_ordenar_validada<>"") {
                $SQL_funcionarios = $SQL_funcionarios."
                and 
                (
                  select 
                        CASE when ((
                          select count(*) 
                          from asiscapac_capacitaciones_funcionarios
                          where confirmado = 't' and fecha_aceptacion is not null
                          and id = cf.id
                        )> 0) THEN 'SI' 
                        ELSE (
                          CASE when ((
                            select count(*) 
                            from asiscapac_capacitaciones_funcionarios
                            where confirmado = 'f' and fecha_revocar is not null
                            and id = cf.id
                          ) > 0) THEN 'NO' 
                          ELSE 
                            'NADA'
                          END
                          )
                        END AS confirmado
                        from asiscapac_capacitaciones_funcionarios cf
                        where ano = $ano
                        and id_asiscapac_capacitaciones = $id_campo_capacitaciones
                        and id_usuario = u.id 
                        ) = 'SI'
                ";         
        }
        if ($id_ordenar_revocada<>"") {
          $SQL_funcionarios = $SQL_funcionarios."
          and 
          (
            select 
                  CASE when ((
                    select count(*) 
                    from asiscapac_capacitaciones_funcionarios
                    where confirmado = 't' and fecha_aceptacion is not null
                    and id = cf.id
                  )> 0) THEN 'SI' 
                  ELSE (
                    CASE when ((
                      select count(*) 
                      from asiscapac_capacitaciones_funcionarios
                      where confirmado = 'f' and fecha_revocar is not null
                      and id = cf.id
                    ) > 0) THEN 'NO' 
                    ELSE 
                      'NADA'
                    END
                    )
                  END AS confirmado
                  from asiscapac_capacitaciones_funcionarios cf
                  where ano = $ano
                  and id_asiscapac_capacitaciones = $id_campo_capacitaciones
                  and id_usuario = u.id 
                  ) = 'NO'
          ";         
  }
  

  if ($id_estado_evidencia <> "") {
    //  if ($id_estado_evidencia == 0) { //TODOS  
    
    //  }
      if ($id_estado_evidencia == 1) { //VALIDADAS
        $SQL_funcionarios = $SQL_funcionarios." 
        and (
          select 
                CASE when ((
                  select count(*) 
                  from asiscapac_capacitaciones_funcionarios
                  where confirmado = 't' and fecha_aceptacion is not null
                  and id = cf.id
                )> 0) THEN 'SI' 
                ELSE (
                  CASE when ((
                    select count(*) 
                    from asiscapac_capacitaciones_funcionarios
                    where confirmado = 'f' and fecha_revocar is not null
                    and id = cf.id
                  ) > 0) THEN 'NO' 
                  ELSE 
                    'NADA'
                  END
                  )
                END AS confirmado
                from asiscapac_capacitaciones_funcionarios cf
                where ano = $ano
                and id_asiscapac_capacitaciones = $id_campo_capacitaciones
                and id_usuario = u.id 
                ) = 'SI'
                ";
        
      }
      if ($id_estado_evidencia == 2) { //REVOCADAS
        $SQL_funcionarios = $SQL_funcionarios." 
        and (
          select 
                CASE when ((
                  select count(*) 
                  from asiscapac_capacitaciones_funcionarios
                  where confirmado = 't' and fecha_aceptacion is not null
                  and id = cf.id
                )> 0) THEN 'SI' 
                ELSE (
                  CASE when ((
                    select count(*) 
                    from asiscapac_capacitaciones_funcionarios
                    where confirmado = 'f' and fecha_revocar is not null
                    and id = cf.id
                  ) > 0) THEN 'NO' 
                  ELSE 
                    'NADA'
                  END
                  )
                END AS confirmado
                from asiscapac_capacitaciones_funcionarios cf
                where ano = $ano
                and id_asiscapac_capacitaciones = $id_campo_capacitaciones
                and id_usuario = u.id 
                ) = 'NO'
                ";
    
      }
      if ($id_estado_evidencia == 3) { //SIN ESTADO
        $SQL_funcionarios = $SQL_funcionarios." 
        and (
          select 
                CASE when ((
                  select count(*) 
                  from asiscapac_capacitaciones_funcionarios
                  where confirmado = 't' and fecha_aceptacion is not null
                  and id = cf.id
                )> 0) THEN 'SI' 
                ELSE (
                  CASE when ((
                    select count(*) 
                    from asiscapac_capacitaciones_funcionarios
                    where confirmado = 'f' and fecha_revocar is not null
                    and id = cf.id
                  ) > 0) THEN 'NO' 
                  ELSE 
                    'NADA'
                  END
                  )
                END AS confirmado
                from asiscapac_capacitaciones_funcionarios cf
                where ano = $ano
                and id_asiscapac_capacitaciones = $id_campo_capacitaciones
                and id_usuario = u.id 
                ) = 'NADA'
                ";
    
      }
    }
    


        if ($id_ordenar_apellido<>"") {
          $SQL_funcionarios = $SQL_funcionarios." order by u.apellido, u.nombre";  
        } else {
          $SQL_funcionarios = $SQL_funcionarios." order by gu.alias, u.apellido, u.nombre";  
        }



        //echo("<br>ver_sql_funcionarios = $SQL_funcionarios");
        $funcionarios = consulta_sql($SQL_funcionarios);
  }
}

$sql_origen = "select id, glosa nombre from asiscapac_origen where id = $id_origen order by orden";
$origenes = consulta_sql($sql_origen);

$sql_tipo = "select id, glosa nombre from asiscapac_tipo where id > 0 order by orden";
$tipos = consulta_sql($sql_tipo);

//$sql_subtipo = "select id, glosa nombre from asiscapac_subtipo where id > 0 order by orden";
//$subtipos = consulta_sql($sql_subtipo);


$sql_estados = "select id, glosa nombre from asiscapac_estado order by orden";
$estados = consulta_sql($sql_estados);

$sql_recordar = "select id, glosa nombre from asiscapac_recordar order by orden";
$recordars = consulta_sql($sql_recordar);




$sql_estados_check = "select id, glosa nombre from asiscapac_actividades_funcionarios_check ";
$estados_check = consulta_sql($sql_estados_check);





$sql_campo_capacitaciones = "
select
id,                    
descripcion      as nombre
from asiscapac_capacitaciones 
where 
ano = $ano
";
if ($id_origen != "") {
  $sql_campo_capacitaciones = $sql_campo_capacitaciones." and id_asiscapac_origen = $id_origen";
}
$sql_campo_capacitaciones = $sql_campo_capacitaciones." order by descripcion" ;
//echo("<br>$sql_campo_capacitaciones");
$campos_capacitaciones = consulta_sql($sql_campo_capacitaciones);

$sql_unidades = "select id, concat(alias,' - ', nombre) nombre from gestion.unidades order by alias";
$unidades = consulta_sql($sql_unidades);


$sql_estado_evidencia = "
select id, nombre from (
	select 0 id, 'Todas' nombre
	union
	select 1 id, 'Validada' nombre
	union
	select 2 id, 'Revocada' nombre
	union
	select 3 id, 'Sin estado' nombre
	) as a
	order by id
";
$estados_evidencias = consulta_sql($sql_estado_evidencia);

?>

<!-- Inicio: <?php echo($modulo); ?> -->

<div class="tituloModulo">
  <?php echo($nombre_modulo); ?>
</div>
<div class="texto" style='margin-top: 5px'>
  <form name="formulario" action="principal.php" method="get">
    <input type="hidden" name="modulo" value="<?php echo($modulo); ?>">
    <input type="hidden" name="eliminar_evidencia" id="eliminar_evidencia" value="<?php echo($eliminar_evidencia); ?>">    
    <input type="hidden" name="confirmar_eliminar_evidencias" id="confirmar_eliminar_evidencias" value="<?php echo($confirmar_eliminar_evidencias); ?>">        
    <input type="hidden" name="id_doctos_digitalizados" id="id_doctos_digitalizados" value="<?php echo($id_doctos_digitalizados); ?>">
    <input type="hidden" name="id_mes" id="id_mes" value="<?php echo($id_mes); ?>">    
    <input type="hidden" name="id_unidad_dos" id="id_unidad_sos" value="<?php echo($id_unidad); ?>">        
    <input type="hidden" name="id_usuario_seleccionado" id="id_usuario_seleccionado" value="<?php echo($id_usuario_seleccionado); ?>">


    <input type='hidden' id='id_profesores_seleccionados' name='id_profesores_seleccionados'>
<!--    <input type='hidden' id='id_current_url' name='id_current_url' value=<?php echo($id_current_url); ?>> -->

    <table cellpadding="1" border="0" cellspacing="2" width="auto">
      <tr>


        <td class="celdaFiltro">
          Año: <br>
          <select name='ano' id='id_ano' onChange="submitform();">
            <?php 
                    $ss = "";
                    for ($x=$ano;$x<=($ano);$x++) {
                      if ($x == $ano) {
                        $ss = "selected";
                      } else {
                        $ss = "";
                      }

                      echo("<option value=$x $ss>$x</option>");
                    }
            ?>
          </select>
        </td>
        
<td class="celdaFiltro" style='display:none;'>
  Origen: <br>
  <select class="filtro" name="id_origen" id="id_origen" onChange="submitform();">
    <!--<option value="">Todos</option>-->
    <?php 
      echo(select($origenes,$id_origen)); 
    ?>    
  </select>
  <input type="button" name='volver' value="volver" style='font-size: 9pt' onclick="window.location.href='<?php echo($enlbase); ?>=capac_buscar&ano=<?php echo($ano); ?>&id_origen=2&id_estado_check=<?php echo($id_estado_check); ?>&id_campo_capacitaciones=<?php echo($id_campo_capacitaciones); ?>&id_mes=<?php echo($id_mes); ?>&id_unidad=<?php echo($id_unidad_dos); ?>&id_usuario_seleccionado=<?php echo($id_usuario_seleccionado); ?>';"/>
  <!--<input type="button" name='gestionar' value="gestionar" style='font-size: 9pt' onclick="window.location.href='https://sgu.umc.cl/sgu/principal.php?modulo=../sgu_rc/EFIMERO/asiscapac_actividades_buscar&ano=<?php echo($ano); ?>&id_origen=2&id_estado_check=<?php echo($id_estado_check); ?>&id_campo_capacitaciones=<?php echo($id_campo_capacitaciones); ?>';"/>-->
  <!--<input type="button" name='gestionar' value="gestionar" style='font-size: 9pt' onclick="window.location.href=getCurrentURL();"/>-->
</td>

        <td class="celdaFiltro">
          Capacitaciones: <br>
          <select class="filtro" name="id_campo_capacitaciones" id="id_campo_capacitaciones" onChange="submitform();">
            <option value="">(Seleccione)</option>
            <?php 
              echo(select($campos_capacitaciones,$id_campo_capacitaciones)); 
            ?>    
          </select>
        </td>

          <select class="filtro" name="id_tipo_general_capacitacion" id="id_tipo_general_capacitacion" onChange="submitform();">
                    <option value="">Seleccione</option>
                    <?php 
                      echo(select($tipos,$id_tipo_general_capacitacion)); 
                    ?>    
                  </select> 

        <td class="celdaFiltro">
          Unidad: <br>
          <select class="filtro" name="id_unidad" id="id_unidad" onChange="submitform();">
            <option value="">(Todas)</option>
            <?php 
              echo(select($unidades,$id_unidad)); 
            ?>    
          </select>
        </td>
        <td class="celdaFiltro">
          Estado Evidencia: <br>
          <select class="filtro" name="id_estado_evidencia" id="id_estado_evidencia" onChange="submitform();">
            <option value="">(Seleccione)</option>
            <?php 
              echo(select($estados_evidencias,$id_estado_evidencia)); 
            ?>    
          </select>
        </td>

<?php
if ($id_ordenar_apellido<>"") {
  $chk_selected = "checked";
} else {
  $chk_selected = "";
}
?>


        <td class="celdaFiltro">
          Acción: <br>
          <input type="button" name='volver' value="volver" onclick="window.location.href='<?php echo($enlbase); ?>=capac_buscar&ano=<?php echo($ano); ?>&id_origen=2&id_estado_check=<?php echo($id_estado_check); ?>&id_campo_capacitaciones=<?php echo($id_campo_capacitaciones); ?>&id_mes=<?php echo($id_mes); ?>&id_unidad=<?php echo($id_unidad_dos); ?>&id_usuario_seleccionado=<?php echo($id_usuario_seleccionado); ?>';"/>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type='checkbox' id='id_ordenar_apellido' name='id_ordenar_apellido' onChange="submitform();" <?php echo($chk_selected); ?>> Ordenar x Apellido
        </td>     
      </tr>
    </table>
    <input type="hidden" name="modo" id="modo" value="<?php echo($modo); ?>">


    <!--</table>-->
</div>
  </form>

  <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive m-t-40">
                                    <table bgcolor="#ffffff" cellspacing="1" cellpadding="2"  id='id_tabla_profesores' class="display nowrap table table-hover table-striped table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th class='tituloTabla' style="display:none;">Año</th>
                                                <th class='tituloTabla' style="display:none;">Origen</th>
                                                <th class='tituloTabla' style="display:none;">Id Actividad</th>
                                                <th class='tituloTabla' style="display:none;">Actividad</th>
                                                <th class='tituloTabla'>Unidad</th>
                                                <th class='tituloTabla'>ID Usuario</th>
                                                <th class='tituloTabla' style="display:none;">username</th>
                                                <th class='tituloTabla'>Apellido</th>
                                                <th class='tituloTabla'>Nombre</th>
                                                <th class='tituloTabla'>Email</th> 
                                                <th class='tituloTabla'>Evidencias</th> 
                                                <th class='tituloTabla'>Estado evidencia </th>
                                                <th class='tituloTabla'>Acción</th> 
                                                <!--<th class='tituloTabla'>Estado evidencia <br>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($funcionarios as $key => $value) {
                                         // var_dump($value);
                                          echo '<tr>
                                            <td class="textoTabla" style="display:none;">'.$value['ano'].'</td>
                                            <td class="textoTabla" style="display:none;">'.$value['origen'].'</td>
                                            <td class="textoTabla" style="display:none;">'.$value['id_campo_capacitaciones'].'</td>
                                            <td class="textoTabla" style="display:none;">'.$value['campo_glosa_actividades'].'</td>
                                            <td class="textoTabla">'.$value['nombre_unidad'].'</td>
                                            <td class="textoTabla">'.$value['id'].'</td>
                                            <td class="textoTabla" style="display:none;">'.$value['nombre_usuario'].'</td>
                                            <td class="textoTabla">'.$value['apellido'].'</td>
                                            <td class="textoTabla">'.$value['nombre'].'</td>
                                            <td class="textoTabla">'.$value['email'].'</td>
                                            <td class="textoTabla">';              


                                                      $SQL_evidencias = "SELECT 
                                                      id id_doctos_digitalizados,
                                                      to_char(fecha,'DD-tmMon-YYYY HH24:MI') fecha, 
                                                      id_asiscapac_usuario_capacitaciones,
                                                      id_asiscapac_capacitaciones,
                                                      nombre_archivo,
                                                      --mime,
                                                      --archivo,
                                                      eliminado,
                                                      id_usuario
                                                      from 
                                                      capac_doctos_digitalizados
                                                      where 
                                                      id_usuario = $id
                                                      and id_asiscapac_capacitaciones = $id_campo_capacitaciones
                                                      and eliminado = 'f'
                                                      order by fecha desc
                                                      ";
                                                      $mis_evidencias = consulta_sql($SQL_evidencias);
                                                      //echo("$SQL_evidencias<br>");
                                                      for ($vv=0;$vv<count($mis_evidencias);$vv++) {
                                                        extract($mis_evidencias[$vv]);
                                                        //$HTML_alumnos .= $nombre_archivo."<br>";              
                                                      //lagarto  
                                                       echo"<a href='capac_ver_evidencia.php?id_doctos_digitalizados=$id_doctos_digitalizados' target='_blank' class='enlaces'><small>Ver evidencia ($fecha)</small>
                                                        &nbsp;&nbsp<a href='principal.php?modulo=capac_usuarios_evidencias&ano=$ano&id_origen=$id_origen&id_campo_capacitaciones=$id_campo_capacitaciones&id_estado_check=$id_estado_check&id_usuario_confirmar=$id&confirmar=NO&id_doctos_digitalizados=$id_doctos_digitalizados&eliminar_evidencia=SI' class='enlaces'><small>Elim</small></a><br>";
                                                      }
                                         echo '</td>';


                                         if ($value['confirmado']=="SI") {
                                          echo "  <td class='textoTabla'><span style='color: green'><b> ✓ </b></span>(Validada)</td>";   
                                        } else {
                                                if ($value['confirmado']=="NO") {
                                        //          $HTML_formar .= "  <td class='textoTabla'></td>";   
                                                echo "<td class='textoTabla'><span style='color: red'><b> ✗ </b></span></span>(Revocada)</td>";   
                                                } else {
                                        //          $HTML_formar .= "  <td class='textoTabla'></td>";   
                                                 echo "  <td class='textoTabla'></td>";   
                                                }
                                        }
                                        
                                        
                                        
                                        if ($value['confirmado']=="SI") {
                                          echo "  <td class='textoTabla'><a href='$enlbase_sm=capac_revocar&ano=$ano&id_origen=$id_origen&id_campo_capacitaciones=$id_campo_capacitaciones&id_estado_check=$id_estado_check&id_usuario_confirmar=$id&confirmar=NO&param_observacion=$observacion&param_observacionrevocar=$observacionrevocar' class='text'  id='sgu_fancybox_small'>Ver</a></td>";   
                                        } else {
                                                if ($value['confirmado']=="NO") {
                                                  echo "  <td class='textoTabla'><a href='$enlbase_sm=capac_revocar&ano=$ano&id_origen=$id_origen&id_campo_capacitaciones=$id_campo_capacitaciones&id_estado_check=$id_estado_check&id_usuario_confirmar=$id&confirmar=SI&param_observacion=$observacion&param_observacionrevocar=$observacionrevocar' class='text'  id='sgu_fancybox_small'>Ver</a></td>";   
                                                } else {
                                                  echo "  <td class='textoTabla'><a href='$enlbase_sm=capac_revocar&ano=$ano&id_origen=$id_origen&id_campo_capacitaciones=$id_campo_capacitaciones&id_estado_check=$id_estado_check&id_usuario_confirmar=$id&confirmar=NADA&param_observacion=$observacion&param_observacionrevocar=$observacionrevocar' class='text'  id='sgu_fancybox_small'>Establecer</a></td>";   
                                                }
                                        }
                                         echo '</tr>';
                                        }
                                        ?>                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


<!-- Fin: <?php echo($modulo); ?> -->
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
    <script src="/assets/styleswitcher/jQuery.style.switcher.js"></script>

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

         /*  table.clear().draw();

    //destroy datatable
    table.destroy()*/
    
      $('#id_tabla_profesores').DataTable({
        "pageLength": 20,
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
            'excel', 'csv'
         ]
      });
   }, 3000);

    </script>

<script type="text/javascript">

  function marcarTodos() {
    console.log("estot en marcarTodos");

    var profSeleccionados = "";
    var sql_actualizar_curso_tmp = "";

    $("#id_profesores_seleccionados").val(profSeleccionados);

    maxFilas = sacaMaxFilas();
    usuarios_seleccionados = "";
    for (let i = 0; i <= maxFilas; i++) {
            try {
                  todosCheck = document.getElementById("id_todos_check");
                  if (todosCheck.checked == true){
                    opcionMarcarTodos = true;
                  } else {
                    opcionMarcarTodos = false;
                  }

                  idCheckBox = "id_incluir_"+i;
                  console.log("number one i="+i);
                  controlIdCheckBox = "#"+idCheckBox;
                  console.log("control = "+controlIdCheckBox);
                  if (opcionMarcarTodos) {
                    $(controlIdCheckBox).prop( "checked", true );
                  } else {
                    $(controlIdCheckBox).prop( "checked", false );
                  }
                  
                  console.log("number two");
                  cursoSelected = document.getElementById(idCheckBox);
                  
                  if (cursoSelected.checked == true){
                    //console.log("seleccionado = " + idCheckBox);
                    id_usuario = sacaValorColumna(i);
                    //console.log("id_usuario = "+id_usuario);
                    usuarios_seleccionados = usuarios_seleccionados + id_usuario + ",";
                  } else {
                    //console.log("debe cambiar color FONDO, inactivo");
                    cambiaColorFondoRow(i, false);
                  }
          } catch (error) {
              //SE HIZO PO>R LOS BLANCOS
              //console.error(error);
          }

    }
        


    var ss = usuarios_seleccionados;
    if (ss.length > 1) {
      ss = ss.substr(0,ss.length - 1); 
      //console.log(profSeleccionados);
      //console.log(sql_actualizar_curso_tmp);
      $("#id_profesores_seleccionados").val(ss);
      //$("#sql_actualizar_curso_tmp").val(sql_actualizar_curso_tmp);
      //$("#sql_eliminar_curso_tmp").val(sql_eliminar_curso_tmp);
      //$("#sql_crear_curso_tmp").val(sql_crear_curso_tmp);

    } else {
      $("#id_profesores_seleccionados").val("");
    }
  }
  function sacaMaxFilas() {
      var maxFilas = 0;
      $("#id_tabla_profesores tr").each(function (index) {
          if (!index) return;
          i = 1;
/*          
          $(this).find("td").each(function () {
  //            if (i == 1) {
                  //primera fila
  //            }
              //var id = $(this).text().toLowerCase().trim();
              //console.log("id="+id);
              maxFilas++;
          });
          */
          maxFilas++;
      });
      //console.log("*****regs totales : " + maxFilas);
      //maxFilas = maxFilas / 6; //MAX-ROWS 
      //console.log("*****maxFilas : " + maxFilas);
      return maxFilas;
    }  
    function sacaValorColumna(fila) {
      //console.log("estoy en sacaValorColumna("+fila+")");
      var maxFilas = 0;
      var idSeleccionados = "";
      $("#id_tabla_profesores tr").each(function (index) {
          if (!index) return;
          if (maxFilas == fila) {
            i = 0;            
                  $(this).find("td").each(function () {
          //            if (i == 1) {
                          //primera fila
          //            }
                      if (i == 5) { //id_uario
                        var id_usuario = $(this).text().toLowerCase().trim();
                        //console.log("maxFilas = " + maxFilas + ", i=" + i + "---* * *>id_usuario="+id_usuario);
                        idSeleccionados = idSeleccionados + id_usuario;
                      }
                      //var id = $(this).text().toLowerCase().trim();
                      //console.log("id="+id);
                      i++;
                  });
                  //break;
          }

          maxFilas++;
      });
      //console.log("*****regs totales : " + maxFilas);
      //console.log("*****maxFilas : " + maxFilas);
      //console.log("idSeleccionado = "+idSeleccionados);


      return idSeleccionados;
    }  

function armarQuerys() {
  //console.log("estoy en armarQuerys");
    var profSeleccionados = "";
    var sql_actualizar_curso_tmp = "";

    $("#id_profesores_seleccionados").val(profSeleccionados);
    //$("#sql_actualizar_curso_tmp").val(sql_actualizar_curso_tmp);
    
    maxFilas = sacaMaxFilas();
    usuarios_seleccionados = "";
    for (let i = 0; i <= maxFilas; i++) {
            try {
                  idCheckBox = "id_incluir_"+i;
                  cursoSelected = document.getElementById(idCheckBox);
                  if (cursoSelected.checked == true){
                    //console.log("seleccionado = " + idCheckBox);
                    id_usuario = sacaValorColumna(i);
                    //console.log("id_usuario = "+id_usuario);
                    usuarios_seleccionados = usuarios_seleccionados + id_usuario + ",";
                  } else {
                    //console.log("debe cambiar color FONDO, inactivo");
                    cambiaColorFondoRow(i, false);
                  }
          } catch (error) {
              //SE HIZO PO>R LOS BLANCOS
              //console.error(error);
          }

    }
    



    var ss = usuarios_seleccionados;
    if (ss.length > 1) {
      ss = ss.substr(0,ss.length - 1); 
      //console.log(profSeleccionados);
      //console.log(sql_actualizar_curso_tmp);
      $("#id_profesores_seleccionados").val(ss);
      //$("#sql_actualizar_curso_tmp").val(sql_actualizar_curso_tmp);
      //$("#sql_eliminar_curso_tmp").val(sql_eliminar_curso_tmp);
      //$("#sql_crear_curso_tmp").val(sql_crear_curso_tmp);

    } else {
      $("#id_profesores_seleccionados").val("");
    }

  }
/*
function setCurrentURL () {
  $("#id_current_url").val(window.location.href);
}
*/

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


  function confirmarEvidenciaUsuario() {
          var bb = false;
          var r = confirm("Está seguro(a) de realizar esta acción?");
          if (r == true) {
            bb = true;
          } else {
            bb = false;
          }
          return bb;
  }

  /*
  $("#id_origen").change(function(){
  
    alert($(this).val());
  
      if ($(this).val()==1) {
          //capacitacion
          //$('#id_tipo').prop('disabled', true);
          //DESAHILITAR
          //$('#id_tipo').attr('disabled', 'disabled');
          $('#id_tipo').prop('disabled', true);
          alert("inhabilitado");
      } else {
        //$('#id_tipo').prop('disabled', false);
        //HABILITAR
        $('#id_tipo').removeAttr('disabled');
        alert("habiliatado");
      }

  });
*/

//setCurrentURL();
}

);
</script>
