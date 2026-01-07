<?php
/**
 * @author JOHANS GONZALEZ MONTERO 
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright
*/

require_once '../tcpdf/tcpdf.php';

class ConstanciaPDF extends TCPDF {

    public function Header() {

        $tbl = '<table border="1">
        <tbody>
            <tr>
                <td style="text-align:center;width:25%" rowspan="3">
                <div><br />
                <img src="'.$_SESSION['ABSOL_PATH'].'/bodega/sys_img/Logo-Supersalud-2024.jpg" style="width:80px" /></div>
                </td>
                <td style="text-align:center;width:50%;line-height: 15px"><strong>GOBIERNO Y GESTI&Oacute;N DE DATOS E<br />
                INFORMACI&Oacute;N</strong></td>
                <td style="text-align:center;width:12.5%;line-height: 30px;"><strong>C&Oacute;DIGO</strong></td>
                <td style="text-align:center;width:12.5%;line-height: 30px;">DIFT03</td>
            </tr>
            <tr>
                <td rowspan="2" style="text-align:center;line-height: 15px"><strong>CONSTANCIA DE EJECUTORIA ACTO<br />
                ADMINISTRATIVO</strong></td>
                <td style="text-align:center"><strong>VERSI&Oacute;N</strong></td>
                <td style="text-align:center">1</td>
            </tr>
            <tr>
                <td style="text-align:center"><strong>FECHA</strong></td>
                <td style="text-align:center">31/01/2023</td>
            </tr>
        </tbody></table><br>';
        $this->SetY(10);
        $this->SetFont ('helvetica', '', 10 , '', 'default', true );
        $this->writeHTML($tbl, true, false, false, false, '');

    }

    public function Footer() {

        $this->SetFont('helvetica', '', 10);
        $tbl = '<p align="right">pág. 1</p>';
        $this->SetY(-20);
        $this->writeHTML($tbl, true, false, false, false, '');

    }

}

?>
