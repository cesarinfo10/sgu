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
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* 'v[ií]v[ií][aá]n[aá]' OR  a.rut ~* 'v[ií]v[ií][aá]n[aá]' OR  lower(a.email) ~* 'v[ií]v[ií][aá]n[aá]' OR  text(a.id) ~* 'v[ií]v[ií][aá]n[aá]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 's[oó]l[eé]d[aá]d' OR  a.rut ~* 's[oó]l[eé]d[aá]d' OR  lower(a.email) ~* 's[oó]l[eé]d[aá]d' OR  text(a.id) ~* 's[oó]l[eé]d[aá]d' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'c[uú]r[ií]h[uú][aá]n' OR  a.rut ~* 'c[uú]r[ií]h[uú][aá]n' OR  lower(a.email) ~* 'c[uú]r[ií]h[uú][aá]n' OR  text(a.id) ~* 'c[uú]r[ií]h[uú][aá]n' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'r[ií]q[uú][eé]lm[eé]' OR  a.rut ~* 'r[ií]q[uú][eé]lm[eé]' OR  lower(a.email) ~* 'r[ií]q[uú][eé]lm[eé]' OR  text(a.id) ~* 'r[ií]q[uú][eé]lm[eé]' )  AND NOT dd.eliminado) TO stdout WITH CSV HEADER