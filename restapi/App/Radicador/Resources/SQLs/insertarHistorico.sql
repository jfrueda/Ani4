INSERT INTO hist_eventos (
    depe_codi, 
    hist_fech, 
    usua_codi, 
    radi_nume_radi, 
    hist_obse, 
    usua_codi_dest, 
    usua_doc, 
    usua_doc_old, 
    sgd_ttr_codigo, 
    hist_usua_autor, 
    hist_doc_dest, 
    depe_codi_dest
    )
VALUES(
    {{depe_codi}},  NOW(), {{usua_codi}}, {{radi_nume_radi}},
     '{{hist_obse}}', {{usua_codi_dest}}, '{{usuaDoc}}', '', {{sgd_ttr_codigo}}, '',
      '{{docDest}}', {{depe_codi_dest}});
