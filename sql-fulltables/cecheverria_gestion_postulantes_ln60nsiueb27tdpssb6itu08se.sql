COPY (SELECT trim(pap.rut) AS rut,vp.id,vp.nombre,to_char(vp.fecha_post,'DD-MM-YYYY') AS fecha_post,
                           trim(vp.carrera1)||'-'||pap.jornada1_post AS carrera1,
                           trim(vp.carrera2)||'-'||pap.jornada2_post AS carrera2,
                           trim(vp.carrera3)||'-'||pap.jornada3_post AS carrera3,
                           trim(c4.alias)||'-'||pap.jornada4_post AS carrera4,
                           trim(c5.alias)||'-'||pap.jornada5_post AS carrera5,
                           trim(c6.alias)||'-'||pap.jornada6_post AS carrera6,
                           pap.estado_carpeta_doctos,pap.semestre_cohorte||'-'||pap.cohorte AS cohorte_post,pap.telefono,pap.tel_movil,pap.email,
                           CASE WHEN vp.cert_nacimiento THEN 'Si' ELSE 'No' END AS cert_nacimiento,
                           CASE WHEN vp.copia_ced_iden  THEN 'Si' ELSE 'No' END AS copia_ced_iden,
                           CASE WHEN vp.conc_notas_em   THEN 'Si' ELSE 'No' END AS conc_notas_em,
                           CASE WHEN pap.conc_notas_em_comp_solic THEN 'Si' ELSE 'No' END AS conc_notas_em_comp_solic,
                           CASE WHEN vp.licencia_em     THEN 'Si' ELSE 'No' END AS licencia_em,
                           CASE WHEN pap.licencia_em_comp_solic   THEN 'Si' ELSE 'No' END AS licencia_em_comp_solic,
                           CASE pap.fotografias WHEN true THEN 'Si' ELSE 'No' END AS fotografias,
                           CASE WHEN vca.rut IS NOT NULL THEN 'Si' ELSE 'No' END AS matriculado,pap.comentarios,pap.promedio_col,
                           CASE WHEN (SELECT dd.id FROM doctos_digitalizados dd LEFT JOIN doctos_digital_tipos ddt ON dd.id_tipo=ddt.id WHERE rut=vp.rut AND ddt.alias IN ('evid_cert_titulo_grado','cert_tit') AND NOT eliminado LIMIT 1) IS NOT NULL THEN 'Si' ELSE 'No' END AS evid_cert_titulo_grado,
                           CASE WHEN a.id IS NOT NULL THEN 'Si' ELSE 'No' END AS estudiante_regular,
                           CASE WHEN vcp.estado IN ('S','R','A') THEN 'Si' ELSE 'No' END AS desertor, 
                           CASE vcp.estado 
                             WHEN 'E' THEN 'Emitido'
                             WHEN 'S' THEN 'Suspensión'
                             WHEN 'R' THEN 'Retiro'
                             WHEN 'A' THEN 'Abandono'
                           END AS estado_contrato,to_char(vcp.estado_fecha,'DD-tmMon-YYYY HH24:MI') AS estado_contrato_fecha,
                           ae.nombre AS estado_estudiante,pap.regimen,vcp.nombre_usuario AS ejecutivo,
                           pap.referencia,r.nombre AS nombre_referencia,pap.referencia_comentarios,(SELECT dd.id FROM doctos_digitalizados dd LEFT JOIN doctos_digital_tipos ddt ON dd.id_tipo=ddt.id WHERE rut=vp.rut AND ddt.alias='fotos' AND NOT eliminado LIMIT 1) AS id_foto,
                           vp.admision,pap.rbd_colegio,col.dependencia AS dependencia_colegio,pap.admision_subtipo,vp.nacionalidad,vp.comuna
                    FROM vista_pap AS vp
                    LEFT JOIN pap USING (id)
                    LEFT JOIN vista_pap_estados vpe USING (id)
                    LEFT JOIN carreras c1 ON c1.id=pap.carrera1_post
                    LEFT JOIN carreras c2 ON c2.id=pap.carrera2_post
                    LEFT JOIN carreras c3 ON c3.id=pap.carrera3_post
                    LEFT JOIN carreras c4 ON c4.id=pap.carrera4_post
                    LEFT JOIN carreras c5 ON c5.id=pap.carrera5_post
                    LEFT JOIN carreras c6 ON c6.id=pap.carrera6_post
                    LEFT JOIN vista_contratos_anos AS vca ON (vca.rut=pap.rut AND vca.ano=2023)
                    LEFT JOIN vista_contratos_pap AS vcp ON (pap.id= vcp.id_pap AND vcp.ano=2023)
                    LEFT JOIN admision.referencias AS r ON r.id=pap.referencia
                    LEFT JOIN vista_colegios AS col ON col.rbd=pap.rbd_colegio
                    LEFT JOIN alumnos AS a ON (a.id_pap=pap.id AND a.semestre_cohorte=pap.semestre_cohorte AND a.cohorte=pap.cohorte)
                    LEFT JOIN al_estados AS ae ON ae.id=a.estado
                    WHERE (lower(pap.nombres||' '||pap.apellidos) ~* 's[eé]b[aá]st[ií][aá]n' OR  pap.rut ~* 's[eé]b[aá]st[ií][aá]n' OR  text(pap.id) ~* 's[eé]b[aá]st[ií][aá]n' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* 'v[eé]g[aá]' OR  pap.rut ~* 'v[eé]g[aá]' OR  text(pap.id) ~* 'v[eé]g[aá]' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* 'v[eé]g[aá]' OR  pap.rut ~* 'v[eé]g[aá]' OR  text(pap.id) ~* 'v[eé]g[aá]' ) 
                    ORDER BY pap.fecha_post DESC ) to stdout WITH CSV HEADER