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
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* 'm[aá]r[ií][aá]' OR  a.rut ~* 'm[aá]r[ií][aá]' OR  lower(a.email) ~* 'm[aá]r[ií][aá]' OR  text(a.id) ~* 'm[aá]r[ií][aá]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'c[oó]ns[uú][eé]l[oó]' OR  a.rut ~* 'c[oó]ns[uú][eé]l[oó]' OR  lower(a.email) ~* 'c[oó]ns[uú][eé]l[oó]' OR  text(a.id) ~* 'c[oó]ns[uú][eé]l[oó]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'p[oó]bl[eé]t[eé]' OR  a.rut ~* 'p[oó]bl[eé]t[eé]' OR  lower(a.email) ~* 'p[oó]bl[eé]t[eé]' OR  text(a.id) ~* 'p[oó]bl[eé]t[eé]' )  AND NOT dd.eliminado) TO stdout WITH CSV HEADER