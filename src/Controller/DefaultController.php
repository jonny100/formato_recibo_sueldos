<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Application\ReportBundle\Report\ReportPDF;

class DefaultController extends Controller
{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    public function formatoAction(Request $request) {	
		
        $pdf = new ReportPDF();
        $pdf->AddPage();	

        
        
        $pdf->SetFont('courier', '', 9);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
	$text = $this->renderView('\Formato\formato_recibo_sueldos.html.twig');

//        $text = 'll';
        $pdf->Write(0, $text);
        $file = $pdf->Output('recibos.pdf', 'S');

        $response = new Response($file);

        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'filename="recibos.pdf"');

        return $response;
    }
}