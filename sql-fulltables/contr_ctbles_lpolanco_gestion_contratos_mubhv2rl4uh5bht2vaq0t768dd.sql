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
                     WHERE (lower(pap.nombres||' '||pap.apellidos) ~* 'm[aá]rt[ií]n[eé]z' OR  pap.rut ~* 'm[aá]rt[ií]n[eé]z' OR lower(a.nombres||' '||a.apellidos) ~* 'm[aá]rt[ií]n[eé]z' OR  a.rut ~* 'm[aá]rt[ií]n[eé]z' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'm[aá]rt[ií]n[eé]z' OR  av.rf_rut ~* 'm[aá]rt[ií]n[eé]z' OR  text(c.id) ~* 'm[aá]rt[ií]n[eé]z' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* 'fl[oó]r[eé]s' OR  pap.rut ~* 'fl[oó]r[eé]s' OR lower(a.nombres||' '||a.apellidos) ~* 'fl[oó]r[eé]s' OR  a.rut ~* 'fl[oó]r[eé]s' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'fl[oó]r[eé]s' OR  av.rf_rut ~* 'fl[oó]r[eé]s' OR  text(c.id) ~* 'fl[oó]r[eé]s' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* '[aá]yl[eé][eé]n' OR  pap.rut ~* '[aá]yl[eé][eé]n' OR lower(a.nombres||' '||a.apellidos) ~* '[aá]yl[eé][eé]n' OR  a.rut ~* '[aá]yl[eé][eé]n' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* '[aá]yl[eé][eé]n' OR  av.rf_rut ~* '[aá]yl[eé][eé]n' OR  text(c.id) ~* '[aá]yl[eé][eé]n' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* 'j[uú]l[ií][eé]t[eé]' OR  pap.rut ~* 'j[uú]l[ií][eé]t[eé]' OR lower(a.nombres||' '||a.apellidos) ~* 'j[uú]l[ií][eé]t[eé]' OR  a.rut ~* 'j[uú]l[ií][eé]t[eé]' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'j[uú]l[ií][eé]t[eé]' OR  av.rf_rut ~* 'j[uú]l[ií][eé]t[eé]' OR  text(c.id) ~* 'j[uú]l[ií][eé]t[eé]' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* '' OR  pap.rut ~* '' OR lower(a.nombres||' '||a.apellidos) ~* '' OR  a.rut ~* '' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* '' OR  av.rf_rut ~* '' OR  text(c.id) ~* '' ) 
                     ORDER BY c.fecha DESC ) to stdout WITH CSV HEADER