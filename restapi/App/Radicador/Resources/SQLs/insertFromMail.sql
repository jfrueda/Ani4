INSERT INTO RADICADO_FROM_MAIL (
    RADI_NUME_RADI,
    MAIL_ID,
    LOG_DATE,
    DESTINATARIO,
    descripcion,
    depe_codi,
    usua_codi,
    remitente
)
VALUES (
 '{{radicado}}',
 '{{mailId}}',
 GETDATE(),
 '{{destinatario}}',
 '{{descripcion}}',
 '{{dependencia}}',
 '{{usuaCodi}}',
 '{{remitente}}'
)