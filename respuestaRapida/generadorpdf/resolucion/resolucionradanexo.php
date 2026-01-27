<?php
  require_once($ruta_raiz . '/tcpdf/tcpdf.php');

  class MYPDF extends TCPDF {

      protected $radicadoOrfeo;
      protected $fechaOrfeo;
      protected $epigrafe;

      public function __construct($radicadoOrfeo, $fechaOrfeo, $epigrafe) {
          $this->radicadoOrfeo = $radicadoOrfeo;
          $this->fechaOrfeo    = $fechaOrfeo;
          $this->epigrafe      = $epigrafe;

          parent::__construct(
              PDF_PAGE_ORIENTATION,
              PDF_UNIT,
              PDF_PAGE_FORMAT,
              true,
              'UTF-8',
              false
          );
      }

      /* =========================
        HEADER
      ========================= */
      public function Header() {
          if ($this->page == 1) {
              $this->SetY(5);
              $this->SetFont('helvetica', 'B', 14);
              $this->Cell(0, 10, 'UNIVERSIDAD MILITAR NUEVA GRANADA', 0, 1, 'C');
              
              $logoPath = dirname(__FILE__) . '/../../../bodega/sys_img/logo.png';
              $this->Image($logoPath, ($this->getPageWidth() - 25) / 2, 15, 25, 0, 'PNG');
          }

          if ($this->page > 1) {
              $this->SetY(10);
              $this->SetFont('helvetica', '', 10);
              $this->Cell(
                  0,
                  6,
                  'Continuación de la Resolución No '.$this->radicadoOrfeo.' de '.$this->fechaOrfeo . ' Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(),
                  0,
                  1,
                  'C'
              );

              $this->SetFont('helvetica', 'B', 10);
              $this->Cell(0, 6, $this->epigrafe, 0, 1, 'C');

              $this->Line(22, $this->GetY(), $this->getPageWidth() - 22, $this->GetY());
          }
          $this->Ln(5);
      }

      /* =========================
        FOOTER
      ========================= */
      public function Footer() {
          $this->SetY(-15);
          $this->SetFont('helvetica', '', 8);
      }
  }

  /* =========================
    USO DEL PDF
  ========================= */

  // VARIABLES ORFEO (ejemplo)
  $numradNofi = $numradNofi;   // Número de resolución
  $anho       = $anho;         // Fecha (texto)
  $radi_asun  = $radi_asun;    // Epígrafe
  $asu        = $asu;          // HTML del cuerpo
  $ruta2      = $ruta2;        // Ruta de salida

  $pdf = new MYPDF($numradNofi, $anho, $radi_asun);

  // METADATOS
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor($setAutor);
  $pdf->SetTitle($SetTitle);
  $pdf->SetSubject($SetSubject);
  $pdf->SetKeywords($SetKeywords);

  // CONFIGURACIÓN
  $pdf->SetMargins(22, 55, 22);
  $pdf->SetAutoPageBreak(true, 20);
  $pdf->AddPage();

  // CONTENIDO
  $pdf->writeHTML($asu, true, false, true, false, '');

  // SALIDA
  $pdf->Output($ruta_raiz.$ruta2, 'F');
?>