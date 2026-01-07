INSERT INTO anexos(
                    anex_radi_nume,
                    anex_codigo,
                    anex_tipo,
                    anex_solo_lect,
                    anex_creador,
                    anex_desc,
                    anex_nomb_archivo,
                    anex_borrado,
                    anex_radi_fech,
                    anex_fech_anex,
                    anex_estado,
                    anex_tamano,
                    anex_numero
                )
                VALUES
                ({{anexRad}},{{anxCode}},{{anexExt}},'N','{{usuario}}','{{anexDesc}}','{{nombArch}}','N',NOW(),NOW(),1,{{size}},{{anexNum}})