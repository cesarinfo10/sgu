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
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* 'g[oó]nz[aá]l[oó]' OR  a.rut ~* 'g[oó]nz[aá]l[oó]' OR  lower(a.email) ~* 'g[oó]nz[aá]l[oó]' OR  text(a.id) ~* 'g[oó]nz[aá]l[oó]' ) AND (lower(a.nombres||' '||a.apellidos) ~* '[aá]rt[uú]r[oó]' OR  a.rut ~* '[aá]rt[uú]r[oó]' OR  lower(a.email) ~* '[aá]rt[uú]r[oó]' OR  text(a.id) ~* '[aá]rt[uú]r[oó]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'm[eé]nd[eé]z' OR  a.rut ~* 'm[eé]nd[eé]z' OR  lower(a.email) ~* 'm[eé]nd[eé]z' OR  text(a.id) ~* 'm[eé]nd[eé]z' )  AND a.carrera_actual IN (94,97,104,110,98,105,102,91,15,103,126,4)  AND NOT dd.eliminado) TO stdout WITH CSV HEADER