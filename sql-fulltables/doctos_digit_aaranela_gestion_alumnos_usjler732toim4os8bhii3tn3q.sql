COPY (SELECT a.rut,apellidos,nombres,ae.nombre as estado,c.alias||'-'||a.jornada as carrera,
                         ad.nombre AS admision,semestre_cohorte||'-'||cohorte as cohorte,a.nacionalidad,
                         ddt.nombre AS tipo_docto,dd.fecha
                  FROM doctos_digitalizados dd 
                  LEFT JOIN doctos_digital_tipos ddt on ddt.id=dd.id_tipo 
                  LEFT JOIN alumnos a                using(rut) 
                  LEFT JOIN admision_tipo ad         on ad.id=a.admision
                  LEFT JOIN al_estados ae            on ae.id=a.estado
                  LEFT JOIN mallas     AS m ON m.id=a.malla_actual
                  LEFT JOIN carreras c               on c.id=carrera_actual 
                  LEFT JOIN usuarios u               on u.id=dd.id_usuario
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* 'm[aá]t[ií][aá]s' OR  a.rut ~* 'm[aá]t[ií][aá]s' OR  lower(a.email) ~* 'm[aá]t[ií][aá]s' OR  text(a.id) ~* 'm[aá]t[ií][aá]s' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'h[aá]nss' OR  a.rut ~* 'h[aá]nss' OR  lower(a.email) ~* 'h[aá]nss' OR  text(a.id) ~* 'h[aá]nss' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'p[eé]r[eé]z' OR  a.rut ~* 'p[eé]r[eé]z' OR  lower(a.email) ~* 'p[eé]r[eé]z' OR  text(a.id) ~* 'p[eé]r[eé]z' ) AND (lower(a.nombres||' '||a.apellidos) ~* 's[aá]l[aá]s' OR  a.rut ~* 's[aá]l[aá]s' OR  lower(a.email) ~* 's[aá]l[aá]s' OR  text(a.id) ~* 's[aá]l[aá]s' )  AND NOT dd.eliminado) TO stdout WITH CSV HEADER