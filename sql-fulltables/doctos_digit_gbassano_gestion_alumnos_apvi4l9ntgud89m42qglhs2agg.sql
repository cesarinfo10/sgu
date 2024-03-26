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
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* '[eé]sc[aá]rl[eé]th' OR  a.rut ~* '[eé]sc[aá]rl[eé]th' OR  lower(a.email) ~* '[eé]sc[aá]rl[eé]th' OR  text(a.id) ~* '[eé]sc[aá]rl[eé]th' ) AND (lower(a.nombres||' '||a.apellidos) ~* '' OR  a.rut ~* '' OR  lower(a.email) ~* '' OR  text(a.id) ~* '' ) AND (lower(a.nombres||' '||a.apellidos) ~* 't[oó]rr[eé]s' OR  a.rut ~* 't[oó]rr[eé]s' OR  lower(a.email) ~* 't[oó]rr[eé]s' OR  text(a.id) ~* 't[oó]rr[eé]s' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'r[oó]dr[ií]g[uú][eé]z' OR  a.rut ~* 'r[oó]dr[ií]g[uú][eé]z' OR  lower(a.email) ~* 'r[oó]dr[ií]g[uú][eé]z' OR  text(a.id) ~* 'r[oó]dr[ií]g[uú][eé]z' )  AND NOT dd.eliminado) TO stdout WITH CSV HEADER