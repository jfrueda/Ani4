<?php

class RadicadoFilter
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Verifica si una dependencia dada está en las dependencias filtradas para un radicado.
     *
     * @param string $radicado El número de radicado a verificar.
     * @param string $dependencia La dependencia a verificar.
     * @return bool True si la dependencia está en la lista filtrada, false en caso contrario.
     */
    public function isDependenciaInFilter($radicado, $dependencia)
    {

        // Validar si sgd_spub_codigo != 2 en el radicado más reciente
        $sqlRadicado = "SELECT sgd_spub_codigo FROM public.radicado WHERE radi_nume_radi = ? ORDER BY id DESC LIMIT 1";
        $rsRadicado = $this->db->conn->Execute($sqlRadicado, [$radicado]);

        if ($rsRadicado && $rsRadicado->fields['SGD_SPUB_CODIGO'] != 2) {
            $sqlFiltHist = "SELECT DEPE_CODI_DEST, DEPE_CODI FROM hist_eventos WHERE radi_nume_radi = ? AND sgd_ttr_codigo not in (110,8)";
            $rsHist = $this->db->conn->Execute($sqlFiltHist, [$radicado]);
        } else {
            $rsHist = false;
        }

        if (!$rsHist) {
            // Si SGD_SPUB_CODIGO == 2 y el usuario es jefe de grupo y su dependencia está en el filtro
            if ($rsRadicado && $rsRadicado->fields['SGD_SPUB_CODIGO'] == 2 && 
                isset($_SESSION["USUA_JEFE_DE_GRUPO"]) && $_SESSION["USUA_JEFE_DE_GRUPO"]) {
                
                // Obtener dependencias del hist_eventos para construir $arrFitDep
                $sqlFiltHist = "SELECT DEPE_CODI_DEST, DEPE_CODI FROM hist_eventos WHERE radi_nume_radi = ? AND sgd_ttr_codigo not in (110,8)";
                $rsHistForFilter = $this->db->conn->Execute($sqlFiltHist, [$radicado]);
                
                if ($rsHistForFilter) {
                    $arrFitDep = [];
                    
                    foreach ($rsHistForFilter as $value) {
                        $arrFitDep[] = $value['DEPE_CODI_DEST'];
                        $arrFitDep[] = $value['DEPE_CODI'];

                        // Add parent dependency of DEPE_CODI_DEST to the array
                        $sqlParentDep = "SELECT depe_codi_territorial FROM dependencia WHERE depe_codi = ?";
                        $rsParentDep = $this->db->conn->Execute($sqlParentDep, [$value['DEPE_CODI_DEST']]);

                        if ($rsParentDep && $rsParentDep->fields['DEPE_CODI_TERRITORIAL']) {
                            $arrFitDep[] = $rsParentDep->fields['DEPE_CODI_TERRITORIAL'];
                        }
                    }
                    
                    // Verificar si la dependencia del usuario está en el filtro
                    if (isset($_SESSION['dependencia']) && in_array($_SESSION['dependencia'], $arrFitDep)) {
                        return true;
                    }
                }
            }
            
            return false;
        }

        $arrFitDep = [];

        foreach ($rsHist as $value) {
            $arrFitDep[] = $value['DEPE_CODI_DEST'];
            $arrFitDep[] = $value['DEPE_CODI'];

            // Add parent dependency of DEPE_CODI_DEST to the array
            $sqlParentDep = "SELECT depe_codi_territorial FROM dependencia WHERE depe_codi = ?";
            $rsParentDep = $this->db->conn->Execute($sqlParentDep, [$value['DEPE_CODI_DEST']]);

            if ($rsParentDep && $rsParentDep->fields['DEPE_CODI_TERRITORIAL']) {
                $arrFitDep[] = $rsParentDep->fields['DEPE_CODI_TERRITORIAL'];
            }
        }

        return in_array($dependencia, $arrFitDep);
    }
}
