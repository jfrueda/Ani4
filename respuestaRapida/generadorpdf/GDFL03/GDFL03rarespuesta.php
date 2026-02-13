<?php
   class MYPDF extends TCPDF
      {          

          protected $radicadoOrfeo;
          protected $fechaOrfeo;

          function __construct($radicadoOrfeo, $fechaOrfeo) {
             $this->radicadoOrfeo = $radicadoOrfeo;
             $this->fechaOrfeo = $fechaOrfeo;
             parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, 3);
          }        

          public function Header()
          {
            
              $tbl = '<table border="1" style="text-align:center; width:100%">
              <tbody>
                  <tr>
                      <td rowspan="3">
                      <div><img src="'.$_SESSION['ABSOL_PATH'].'/bodega/sys_img/logo.png" style="width:100px" /></div>
                      </td>
                      <td>
                      <div>GESTI&Oacute;N JUR&Iacute;DICA</div>
                      </td>
                      <td>
                      <div><strong>C&Oacute;DIGO:</strong></div>
                      </td>
                      <td>
                      <div>GJFT09</div>
                      </td>
                  </tr>
                  <tr>
                      <td rowspan="2">
                      <div>CIRCULAR EXTERNA</div>
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
          </table><br><br><br>';
            $this->SetY(10);
            $this->SetFont ('helvetica', '', 11 , '', 'default', true );
            $this->writeHTML($tbl, true, false, false, false, '');
            $this->SetY(40);
            $this->SetFont ('helvetica', 'B', 11 , '', 'default', true );

              if($this->page == 1) {    
                $this->Cell(0, 15, 'CIRCULAR EXTERNA ' . $this->radicadoOrfeo . ' DE ' . $this->fechaOrfeo, 0, false, 'C', 0, '', 0, false, 'M', 'M');              
              } else {
                $this->Cell(0, 15, $this->radicadoOrfeo, 0, false, 'C', 0, '', 0, false, 'M', 'M');              
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

            
      $pdf = new MYPDF($numradNofi, $anho);
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
      $pdf->write1DBarcode($nurad, 'C39', '', '', '', 7, 0.2, $style, 'N');*/
      $pdf->writeHTML($respuesta, true, false, true, false, '');
      $pdf->Output($ruta_raiz.$ruta2, 'F');   
?>