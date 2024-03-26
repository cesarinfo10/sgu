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
                  WHERE true   AND (lower(a.nombres||' '||a.apellidos) ~* 'b[aá]ss[aá]n[oó]' OR  a.rut ~* 'b[aá]ss[aá]n[oó]' OR  lower(a.email) ~* 'b[aá]ss[aá]n[oó]' OR  text(a.id) ~* 'b[aá]ss[aá]n[oó]' ) AND (lower(a.nombres||' '||a.apellidos) ~* '[aá]l[ií][aá]g[aá]' OR  a.rut ~* '[aá]l[ií][aá]g[aá]' OR  lower(a.email) ~* '[aá]l[ií][aá]g[aá]' OR  text(a.id) ~* '[aá]l[ií][aá]g[aá]' ) AND (lower(a.nombres||' '||a.apellidos) ~* 'gl[oó]r[ií][aá]' OR  a.rut ~* 'gl[oó]r[ií][aá]' OR  lower(a.email) ~* 'gl[oó]r[ií][aá]' OR  text(a.id) ~* 'gl[oó]r[ií][aá]' )  AND NOT dd.eliminado) TO stdout WITH CSV HEADER