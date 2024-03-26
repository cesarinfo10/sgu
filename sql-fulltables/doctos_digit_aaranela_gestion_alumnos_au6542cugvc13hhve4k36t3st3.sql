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
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* 'v[aá]l[eé]nz[uú][eé]l[aá]' OR  a.rut ~* 'v[aá]l[eé]nz[uú][eé]l[aá]' OR  lower(a.email) ~* 'v[aá]l[eé]nz[uú][eé]l[aá]' OR  text(a.id) ~* 'v[aá]l[eé]nz[uú][eé]l[aá]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'c[eé]r[eé]c[eé]d[aá]' OR  a.rut ~* 'c[eé]r[eé]c[eé]d[aá]' OR  lower(a.email) ~* 'c[eé]r[eé]c[eé]d[aá]' OR  text(a.id) ~* 'c[eé]r[eé]c[eé]d[aá]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'l[uú][ií]s' OR  a.rut ~* 'l[uú][ií]s' OR  lower(a.email) ~* 'l[uú][ií]s' OR  text(a.id) ~* 'l[uú][ií]s' ) AND (lower(a.nombres||' '||a.apellidos) ~* '[eé]d[uú][aá]rd[oó]' OR  a.rut ~* '[eé]d[uú][aá]rd[oó]' OR  lower(a.email) ~* '[eé]d[uú][aá]rd[oó]' OR  text(a.id) ~* '[eé]d[uú][aá]rd[oó]' )  AND NOT dd.eliminado) TO stdout WITH CSV HEADER