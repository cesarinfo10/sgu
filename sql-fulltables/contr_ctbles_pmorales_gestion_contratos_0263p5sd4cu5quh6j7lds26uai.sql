COPY (SELECT to_char(c.fecha,'DD-MM-YYYY') as fecha_emision,
                            (SELECT to_char(max(fecha_venc),'DD-MM-YYYY') FROM finanzas.cobros where id_contrato=c.id) as fecha_venc,
                            '' as cta_ctble,
                            coalesce(c.arancel_efectivo,0)+coalesce(c.arancel_pagare_coleg,0)+coalesce(c.arancel_cheque,0)+coalesce(c.arancel_tarjeta_credito,0) as monto,
                            coalesce(va.rut,vp.rut) as rut,coalesce(va.nombre,vp.nombre) as razon_social,
                            c.id as nro_contrato,pc.id as nro_pagare 
                     FROM finanzas.contratos AS c 
                     LEFT JOIN vista_contratos AS vc USING (id)
                     LEFT JOIN vista_alumnos AS va ON va.id=c.id_alumno 
                     LEFT JOIN vista_pap     AS vp ON vp.id=c.id_pap 
                     LEFT JOIN finanzas.pagares_colegiatura AS pc ON pc.id_contrato=c.id
                     LEFT JOIN carreras        AS car ON car.id=c.id_carrera
                     WHERE (lower(pap.nombres||' '||pap.apellidos) ~* 'm[aá]rc[eé]l[oó]' OR  pap.rut ~* 'm[aá]rc[eé]l[oó]' OR lower(a.nombres||' '||a.apellidos) ~* 'm[aá]rc[eé]l[oó]' OR  a.rut ~* 'm[aá]rc[eé]l[oó]' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'm[aá]rc[eé]l[oó]' OR  av.rf_rut ~* 'm[aá]rc[eé]l[oó]' OR  text(c.id) ~* 'm[aá]rc[eé]l[oó]' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* 'j[aá]v[ií][eé]r' OR  pap.rut ~* 'j[aá]v[ií][eé]r' OR lower(a.nombres||' '||a.apellidos) ~* 'j[aá]v[ií][eé]r' OR  a.rut ~* 'j[aá]v[ií][eé]r' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'j[aá]v[ií][eé]r' OR  av.rf_rut ~* 'j[aá]v[ií][eé]r' OR  text(c.id) ~* 'j[aá]v[ií][eé]r' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* 'q[uú][eé]z[aá]d[aá]' OR  pap.rut ~* 'q[uú][eé]z[aá]d[aá]' OR lower(a.nombres||' '||a.apellidos) ~* 'q[uú][eé]z[aá]d[aá]' OR  a.rut ~* 'q[uú][eé]z[aá]d[aá]' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'q[uú][eé]z[aá]d[aá]' OR  av.rf_rut ~* 'q[uú][eé]z[aá]d[aá]' OR  text(c.id) ~* 'q[uú][eé]z[aá]d[aá]' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* 'g[oó]m[eé]z' OR  pap.rut ~* 'g[oó]m[eé]z' OR lower(a.nombres||' '||a.apellidos) ~* 'g[oó]m[eé]z' OR  a.rut ~* 'g[oó]m[eé]z' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'g[oó]m[eé]z' OR  av.rf_rut ~* 'g[oó]m[eé]z' OR  text(c.id) ~* 'g[oó]m[eé]z' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* '' OR  pap.rut ~* '' OR lower(a.nombres||' '||a.apellidos) ~* '' OR  a.rut ~* '' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* '' OR  av.rf_rut ~* '' OR  text(c.id) ~* '' ) 
                     ORDER BY c.fecha DESC ) to stdout WITH CSV HEADER