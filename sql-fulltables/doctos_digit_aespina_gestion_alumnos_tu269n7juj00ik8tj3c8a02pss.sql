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
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* 'v[ií]v[ií][aá]n[aá]' OR  a.rut ~* 'v[ií]v[ií][aá]n[aá]' OR  lower(a.email) ~* 'v[ií]v[ií][aá]n[aá]' OR  text(a.id) ~* 'v[ií]v[ií][aá]n[aá]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'f[eé]rn[aá]nd[aá]' OR  a.rut ~* 'f[eé]rn[aá]nd[aá]' OR  lower(a.email) ~* 'f[eé]rn[aá]nd[aá]' OR  text(a.id) ~* 'f[eé]rn[aá]nd[aá]' ) AND (lower(a.nombres||' '||a.apellidos) ~* '[aá]v[ií]l[aá]' OR  a.rut ~* '[aá]v[ií]l[aá]' OR  lower(a.email) ~* '[aá]v[ií]l[aá]' OR  text(a.id) ~* '[aá]v[ií]l[aá]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'l[ií]g[uú][eé]ñ[oó]' OR  a.rut ~* 'l[ií]g[uú][eé]ñ[oó]' OR  lower(a.email) ~* 'l[ií]g[uú][eé]ñ[oó]' OR  text(a.id) ~* 'l[ií]g[uú][eé]ñ[oó]' )  AND NOT dd.eliminado) TO stdout WITH CSV HEADER