<?php
   class MYPDF extends TCPDF
      {          
          public function Header()
          {
            
              $tbl = '<table border="1" style="text-align:center; width:100%">
              <tbody>
                  <tr>
                      <td rowspan="3">
                      <div><img src="'.$_SESSION['ABSOL_PATH'].'/bodega/sys_img/Logo-Supersalud-2024.jpg" style="width:100px" /></div>
                      </td>
                      <td>
                      <div>GESTI&Oacute;N JUR&Iacute;DICA</div>
                      </td>
                      <td>
                      <div><strong>C&Oacute;DIGO:</strong></div>
                      </td>
                      <td>
                      <div>GJFT08</div>
                      </td>
                  </tr>
                  <tr>
                      <td rowspan="2">
                      <div>CIRCULAR INTERNA</div>
                      </td>
                      <td>
                      <div><strong>VERSI&Oacute;N:</strong></div>
                      </td>
                      <td>
                      <div>1</div>
                      </td>
                  </tr>
                  <tr>
                      <td><strong>FECHA</strong></td>
                      <td>29/05/2023</td>
                  </tr>
              </tbody>
          </table>
          <br><br><br>';
              $this->SetY(10);
              $this->SetFont ('helvetica', '', 11 , '', 'default', true );
              $this->writeHTML($tbl, true, false, false, false, '');
              $this->SetY(40);
              $this->SetFont ('helvetica', 'B', 11 , '', 'default', true );

              if($this->page == 1) {    
                $this->Cell(0, 15, 'CIRCULAR INTERNA RA_NOTI_S DE ANHO_S', 0, false, 'C', 0, '', 0, false, 'M', 'M');              
              } else {
                $this->Cell(0, 15, 'RA_NOTI_S', 0, false, 'C', 0, '', 0, false, 'M', 'M');              
              }

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

    /* $style = array(
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