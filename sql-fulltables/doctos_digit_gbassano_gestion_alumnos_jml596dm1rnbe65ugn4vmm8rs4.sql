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
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* 'j[uú][aá]n' OR  a.rut ~* 'j[uú][aá]n' OR  lower(a.email) ~* 'j[uú][aá]n' OR  text(a.id) ~* 'j[uú][aá]n' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'p[aá]bl[oó]' OR  a.rut ~* 'p[aá]bl[oó]' OR  lower(a.email) ~* 'p[aá]bl[oó]' OR  text(a.id) ~* 'p[aá]bl[oó]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'p[eé]z[oó][aá]' OR  a.rut ~* 'p[eé]z[oó][aá]' OR  lower(a.email) ~* 'p[eé]z[oó][aá]' OR  text(a.id) ~* 'p[eé]z[oó][aá]' ) AND (lower(a.nombres||' '||a.apellidos) ~* '[aá]c[eé]v[eé]d[oó]' OR  a.rut ~* '[aá]c[eé]v[eé]d[oó]' OR  lower(a.email) ~* '[aá]c[eé]v[eé]d[oó]' OR  text(a.id) ~* '[aá]c[eé]v[eé]d[oó]' )  AND NOT dd.eliminado) TO stdout WITH CSV HEADER