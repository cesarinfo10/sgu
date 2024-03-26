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
                     WHERE (lower(pap.nombres||' '||pap.apellidos) ~* 'fl[oó]r[eé]s' OR  pap.rut ~* 'fl[oó]r[eé]s' OR lower(a.nombres||' '||a.apellidos) ~* 'fl[oó]r[eé]s' OR  a.rut ~* 'fl[oó]r[eé]s' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'fl[oó]r[eé]s' OR  av.rf_rut ~* 'fl[oó]r[eé]s' OR  text(c.id) ~* 'fl[oó]r[eé]s' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* '[oó]s[oó]r[ií][oó]' OR  pap.rut ~* '[oó]s[oó]r[ií][oó]' OR lower(a.nombres||' '||a.apellidos) ~* '[oó]s[oó]r[ií][oó]' OR  a.rut ~* '[oó]s[oó]r[ií][oó]' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* '[oó]s[oó]r[ií][oó]' OR  av.rf_rut ~* '[oó]s[oó]r[ií][oó]' OR  text(c.id) ~* '[oó]s[oó]r[ií][oó]' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* 'c[aá]rl[oó]s' OR  pap.rut ~* 'c[aá]rl[oó]s' OR lower(a.nombres||' '||a.apellidos) ~* 'c[aá]rl[oó]s' OR  a.rut ~* 'c[aá]rl[oó]s' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'c[aá]rl[oó]s' OR  av.rf_rut ~* 'c[aá]rl[oó]s' OR  text(c.id) ~* 'c[aá]rl[oó]s' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* '[aá]ndr[eé]s' OR  pap.rut ~* '[aá]ndr[eé]s' OR lower(a.nombres||' '||a.apellidos) ~* '[aá]ndr[eé]s' OR  a.rut ~* '[aá]ndr[eé]s' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* '[aá]ndr[eé]s' OR  av.rf_rut ~* '[aá]ndr[eé]s' OR  text(c.id) ~* '[aá]ndr[eé]s' ) 
                     ORDER BY c.fecha DESC ) to stdout WITH CSV HEADER