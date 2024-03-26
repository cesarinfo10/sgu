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
                     WHERE (lower(pap.nombres||' '||pap.apellidos) ~* 'm[eé]n[aá]nt[eé][aá][uú]' OR  pap.rut ~* 'm[eé]n[aá]nt[eé][aá][uú]' OR lower(a.nombres||' '||a.apellidos) ~* 'm[eé]n[aá]nt[eé][aá][uú]' OR  a.rut ~* 'm[eé]n[aá]nt[eé][aá][uú]' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'm[eé]n[aá]nt[eé][aá][uú]' OR  av.rf_rut ~* 'm[eé]n[aá]nt[eé][aá][uú]' OR  text(c.id) ~* 'm[eé]n[aá]nt[eé][aá][uú]' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* 's[eé]p[uú]lv[eé]d[aá]' OR  pap.rut ~* 's[eé]p[uú]lv[eé]d[aá]' OR lower(a.nombres||' '||a.apellidos) ~* 's[eé]p[uú]lv[eé]d[aá]' OR  a.rut ~* 's[eé]p[uú]lv[eé]d[aá]' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 's[eé]p[uú]lv[eé]d[aá]' OR  av.rf_rut ~* 's[eé]p[uú]lv[eé]d[aá]' OR  text(c.id) ~* 's[eé]p[uú]lv[eé]d[aá]' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* 'h[eé][ií]dy' OR  pap.rut ~* 'h[eé][ií]dy' OR lower(a.nombres||' '||a.apellidos) ~* 'h[eé][ií]dy' OR  a.rut ~* 'h[eé][ií]dy' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* 'h[eé][ií]dy' OR  av.rf_rut ~* 'h[eé][ií]dy' OR  text(c.id) ~* 'h[eé][ií]dy' ) AND (lower(pap.nombres||' '||pap.apellidos) ~* '[aá]ndr[eé][aá]' OR  pap.rut ~* '[aá]ndr[eé][aá]' OR lower(a.nombres||' '||a.apellidos) ~* '[aá]ndr[eé][aá]' OR  a.rut ~* '[aá]ndr[eé][aá]' OR lower(av.rf_nombres||' '||av.rf_apellidos) ~* '[aá]ndr[eé][aá]' OR  av.rf_rut ~* '[aá]ndr[eé][aá]' OR  text(c.id) ~* '[aá]ndr[eé][aá]' ) 
                     ORDER BY c.fecha DESC ) to stdout WITH CSV HEADER