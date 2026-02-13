<?php

   class MYPDF extends TCPDF
      {
          protected $processId = 0;
          protected $header = '';
          protected $footer = '';
          static $errorMsg = '';

          protected $radicadoOrfeo;
          protected $fechaOrfeo;
          protected $epigrafe;
          protected $tableEpi;

          function __construct($radicadoOrfeo, $fechaOrfeo, $epigrafe) {
             $this->radicadoOrfeo = $radicadoOrfeo;
             $this->fechaOrfeo = $fechaOrfeo;
             $this->epigrafe = $epigrafe;
             parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, 3);
          }


          /**
            * This method is used to override the parent class method.
          **/
          public function Header()
          {
              if ($this->page == 1) {
                  $this->SetY(5);
                  $this->SetFont('helvetica', 'B', 14);
                  $this->Cell(0, 10, 'UNIVERSIDAD MILITAR NUEVA GRANADA', 0, 1, 'C');
                  
                  $logoPath = dirname(__FILE__) . '/../../../bodega/sys_img/logo.png';
                  $this->Image($logoPath, ($this->getPageWidth() - 25) / 2, 15, 25, 0, 'PNG');
              }
            
              if($this->page > 1) {    
                  $this->SetY(10);
                  $this->SetFont('helvetica', '', 10);
                  $this->Cell(0, 10, 'RESOLUCIÓN No RA_NOTI_S DE ANHO_S Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 1, 'C'); 

                  $this->tableEpi = '<table border="0">
                       <tr align="center">
                            <td>Continuación de la resolución, <b>' . $this->epigrafe  . '</b></td>
                       </tr> 
                  </table>';
                  $this->writeHTML($this->tableEpi, true, false, true, false, '');                   
                  $this->Line(22, $this->getY(), $this->getPageWidth() - 22,  $this->getY());
              }
          }

        public function Footer() {         
            $this->SetY(-15);
            $this->SetFont ('helvetica', '', 8 , '', 'default', true );
            $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'M', 'M');       
        }          

      }

      $sqlGetExtrInfo = "select radi_nume_radi, radi_fech_radi, ra_asun  from radicado   where  radi_nume_radi = '$radPadre'";

      $rs = $db->conn->Execute($sqlGetExtrInfo);

      if (!$rs->EOF) {
          $radi_fecha_radi = $rs->fields["RADI_FECH_RADI"];
          $pieces = explode(" ", $radi_fecha_radi);
          $radi_fecha_radi =  $pieces[0];          
          $radi_asun = $rs->fields["RA_ASUN"];
      }

      $pdf = new MYPDF($radPadre, $radi_fecha_radi, $radi_asun);
      //$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, 3);
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor(AUTOR_PDF);
      $pdf->SetTitle(TITULO_PDF);
      $pdf->SetSubject(ASUNTO_PDF);
      $pdf->SetKeywords(KEYWORDS_PDF);
      
      $pdf->SetMargins(22, 56, 22);
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
      $pdf->write1DBarcode($radicado_salida, 'C39', '', '', '', 7, 0.2, $style, 'N');  */    
      $pdf->writeHTML($respuesta, true, false, true, false, '');

      $pdf_result = $pdf->Output($archivo_grabar, 'F');

?>
