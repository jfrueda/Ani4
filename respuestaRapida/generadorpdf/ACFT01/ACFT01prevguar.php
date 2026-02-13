<?php
   class MYPDF extends TCPDF
      {          
          public function Header()
          {
            
            $tbl = '
            <table border="1" >
            <tbody>
            <tr>
                <td rowspan="3" style="width:118.8pt">
                      <div style="text-align:center">
                           <br><img src="'.$_SESSION['ABSOL_PATH'].'/bodega/sys_img/logo.png" width="95px" />
                      </div> 
                </td>
                <td style="width:216.95pt">
                <p style="text-align:center"><strong><span style="font-size:11.0pt">Actuaciones Disciplinarias</span></strong></p>
                </td>
                <td style="width:73.0pt">
                <p><strong><span style="font-size:10.0pt">&nbsp;C&Oacute;DIGO</span></strong></p>
                </td>
                <td style="width:66.5pt">
                <p><span style="font-size:10.0pt">&nbsp;ACFT01</span></p>
                </td>
            </tr>
            <tr>
                <td rowspan="2" style="width:216.95pt">
                <p style="text-align:center"><strong><span style="font-size:11.0pt">Autos actuaciones <br>disciplinarias</span></strong></p>
                </td>
                <td style="width:73.0pt">
                <p><strong><span style="font-size:10.0pt">&nbsp;VERSI&Oacute;N</span></strong></p>
                </td>
                <td style="width:66.5pt">
                <p><span style="font-size:10.0pt">&nbsp;02</span></p>
                </td>
            </tr>
            <tr>
                <td style="width:73.0pt">
                <p><strong><span style="font-size:10.0pt">&nbsp;FECHA</span></strong></p>
                </td>
                <td style="width:66.5pt">
                <p><span style="font-size:10.0pt">&nbsp;12/07/2024</span></p>
                </td>
            </tr>
        </tbody>         
            </table>';
            $this->SetY(10);
            $this->SetFont ('helvetica', '', 8 , '', 'default', true );
            $this->writeHTML($tbl, true, false, false, false, '');
            $this->SetFont ('helvetica', 'B', 11 , '', 'default', true );    
            $this->Cell(0, 15, 'AUTO N° RA_NOTI_S DE DIA_S-MES_S-ANHO_S', 0, false, 'C', 0, '', 0, false, 'M', 'M'); 
          }

        public function Footer() {         
              // Position at 15 mm from bottom
            $this->SetY(-15);
            // Set font
            $this->SetFont ('helvetica', '', 8 , '', 'default', true );
            // Page number
            $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'M', 'M');                 
        }

      }

            
      $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, 3);
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor(AUTOR_PDF);
      $pdf->SetTitle(TITULO_PDF);
      $pdf->SetSubject(ASUNTO_PDF);
      $pdf->SetKeywords(KEYWORDS_PDF);
      
      $pdf->SetFont ('helvetica', '', 11 , '', 'default', true );
      $pdf->SetMargins(22, 51, 22);
      $pdf->AddPage();    

     /*$style = array(
        'position' => '',
        'align' => 'C',
        'stretch' => true,
        'fitwidth' => true,
        'cellfitalign' => '',
        'border' => false,
        'hpadding' => 'auto',
        'vpadding' => 'auto',
        'fgcolor' => array(0,0,0),
        'bgcolor' => false, //array(255,255,255),
        'text' => false,
        'font' => 'helvetica',
        'fontsize' => 8,
        'stretchtext' => 4
    );
      $style['position'] = 'R';
      $pdf->write1DBarcode($radicado_salida, 'C39', '', '', '', 7, 0.2, $style, 'N');*/

      // output the HTML content
      $pdf->writeHTML($respuesta, true, false, true, false, '');

      // Close and output PDF document
      // This method has several options, check the source code documentation for more information.
      $pdf_result = $pdf->Output($archivo_grabar, 'F');    
?>