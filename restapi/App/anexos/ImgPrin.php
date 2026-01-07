<?php

namespace App\anexos;

use App\Lib\ADOdb;
use Dompdf\Dompdf;

class ImgPrin
{
    protected $db;
    protected $twig;

    public function __construct()
    {
        $this->db = new ADOdb();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

    }

    public function generatePdf($dep ,$radicado, $usuario, $mailOrg, $asunto)
    {

        $anio = date("Y");
        // instantiate and use the dompdf class
        //require_once('contenido.php');
    
        $html = $this->twig->render('contenido.html',[
            "radicado" => $radicado,
            "usuario" => $usuario,
            "mail" => $mailOrg,
            "asunto" => $asunto
        ]);
        
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        /*
        $options->set('isRemoteEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultMediaType', 'all');
        */
        
        //$options->setDefaultFont('Code3of9');
        $dompdf->setOptions($options);
        //$dompdf->setOptions('isRemoteEnabled', true);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents($_SERVER['DOCUMENT_ROOT']."/bodega/{$anio}/{$dep}/{$radicado}.pdf",$output);
        $path = "/{$anio}/{$dep}/{$radicado}.pdf";

        $sql = "UPDATE RADICADO SET radi_path = '{$path}' WHERE radi_nume_radi = {$radicado}";
        $rs = $this->db->conn->Execute($sql);
        
        //echo json_encode($rs->EOF);
        return;
    }
}