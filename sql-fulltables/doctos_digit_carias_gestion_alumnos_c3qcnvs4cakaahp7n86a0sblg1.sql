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
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* 'c[aá]rl[aá]' OR  a.rut ~* 'c[aá]rl[aá]' OR  lower(a.email) ~* 'c[aá]rl[aá]' OR  text(a.id) ~* 'c[aá]rl[aá]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'v[aá]rg[aá]s' OR  a.rut ~* 'v[aá]rg[aá]s' OR  lower(a.email) ~* 'v[aá]rg[aá]s' OR  text(a.id) ~* 'v[aá]rg[aá]s' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'c[aá]t[aá]l[aá]n' OR  a.rut ~* 'c[aá]t[aá]l[aá]n' OR  lower(a.email) ~* 'c[aá]t[aá]l[aá]n' OR  text(a.id) ~* 'c[aá]t[aá]l[aá]n' )  AND NOT dd.eliminado) TO stdout WITH CSV HEADER