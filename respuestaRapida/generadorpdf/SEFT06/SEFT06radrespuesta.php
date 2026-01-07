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
            
            $tbl = '
            <table border="1" >
            <tbody>
            <tr>
                <td rowspan="3" style="width:118.8pt">
                      <div style="text-align:center">
                           <br><img src="'.$_SESSION['ABSOL_PATH'].'/bodega/sys_img/Logo-Supersalud-2024.jpg" width="95px" />
                      </div> 
                </td>
                <td style="width:216.95pt">
                <p style="text-align:center"><strong><span style="font-size:11.0pt">PROCESO SEGUIMIENTO Y EVALUACIÓN AL VIGILADO</span></strong></p>
                </td>
                <td style="width:73.0pt">
                <p><strong><span style="font-size:10.0pt">&nbsp;C&Oacute;DIGO</span></strong></p>
                </td>
                <td style="width:66.5pt">
                <p><span style="font-size:10.0pt">&nbsp;SEFT06</span></p>
                </td>
            </tr>
            <tr>
                <td rowspan="2" style="width:216.95pt">
                <p style="text-align:center"><strong><span style="font-size:11.0pt">AUTO DE SEGUIMIENTO EN CAMPO</span></strong></p>
                </td>
                <td style="width:73.0pt">
                <p><strong><span style="font-size:10.0pt">&nbsp;VERSI&Oacute;N</span></strong></p>
                </td>
                <td style="width:66.5pt">
                <p><span style="font-size:10.0pt">&nbsp;01</span></p>
                </td>
            </tr>
            <tr>
                <td style="width:73.0pt">
                <p><strong><span style="font-size:10.0pt">&nbsp;FECHA</span></strong></p>
                </td>
                <td style="width:66.5pt">
                <p><span style="font-size:10.0pt">&nbsp;05/02/2024</span></p>
                </td>
            </tr>
        </tbody>         
            </table>';
              $this->SetY(10);
              $this->SetFont ('helvetica', '', 8 , '', 'default', true );
              $this->writeHTML($tbl, true, false, false, false, '');
              $this->SetFont ('helvetica', 'B', 11 , '', 'default', true );
              if($this->PageNo() == 1) {
                 $this->Cell(0, 15, 'AUTO ' .$this->getAliasNumPage() . ' '. $this->radicadoOrfeo . ' DE ' . $this->fechaOrfeo, 0, false, 'C', 0, '', 0, false, 'M', 'M');
              } else {
                 $this->Cell(0, 15, 'AUTO ' .$this->getAliasNumPage() . ' '.  $this->radicadoOrfeo ,0, false, 'C', 0, '', 0, false, 'M', 'M');
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
      $pdf->SetMargins(22, 55, 22);
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
      $pdf->write1DBarcode($nurad, 'C39', '', '', '', 7, 0.2, $style, 'N');*/
      $pdf->writeHTML($respuesta, true, false, true, false, '');
      $pdf->Output($ruta_raiz.$ruta2, 'F');   

?>