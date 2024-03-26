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
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* 'm[aá]rq[uú][eé]z' OR  a.rut ~* 'm[aá]rq[uú][eé]z' OR  lower(a.email) ~* 'm[aá]rq[uú][eé]z' OR  text(a.id) ~* 'm[aá]rq[uú][eé]z' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'g[oó]nz[aá]l[eé]z' OR  a.rut ~* 'g[oó]nz[aá]l[eé]z' OR  lower(a.email) ~* 'g[oó]nz[aá]l[eé]z' OR  text(a.id) ~* 'g[oó]nz[aá]l[eé]z' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'p[eé]dr[oó]' OR  a.rut ~* 'p[eé]dr[oó]' OR  lower(a.email) ~* 'p[eé]dr[oó]' OR  text(a.id) ~* 'p[eé]dr[oó]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'm[aá]n[uú][eé]l' OR  a.rut ~* 'm[aá]n[uú][eé]l' OR  lower(a.email) ~* 'm[aá]n[uú][eé]l' OR  text(a.id) ~* 'm[aá]n[uú][eé]l' )  AND NOT dd.eliminado) TO stdout WITH CSV HEADER