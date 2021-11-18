<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Application\ReportBundle\Report\ReportPDF;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultController extends Controller
{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    public function formatoAction(Request $request) {	
	$form = $this->createFormBuilder()
            ->add('attachment', FileType::class, array('label' => 'Importar TXT', "attr" => array('class' => "form-control-file")))
            ->add('generarRecibos', SubmitType::class, array('label' => 'Procesar Recibos de Sueldos', "attr" => array('class' => "btn btn-success pull-left", 'style' => 'margin-top:15px;')))
            ->add('generarLibro', SubmitType::class, array('label' => 'Procesar Libro Sueldos', "attr" => array('class' => "btn btn-success pull-left", 'style' => 'margin-top:15px;')))
            ->add('generarAportes', SubmitType::class, array('label' => 'Procesar Aportes', "attr" => array('class' => "btn btn-success pull-left", 'style' => 'margin-top:15px;')))
            ->getForm();
        $form->handleRequest($request);

        // Check if we are posting stuff
//        if ($request->getMethod('post') == 'POST') {
            // Bind request to the form
//            $form->bind($request);

            // If form is valid
//            var_dump($form->isSubmitted());die();
            if ($form->isSubmitted() && $form->isValid()) {
                // Get file
                $file = $form['attachment']->getData();

                header('Content-type: text/plain');
                $txt = file_get_contents($file);
                
                if($form->get('generarRecibos')->isClicked()){
        
                    $pdf = new ReportPDF();
                    $pdf->AddPage();

                    $pdf->SetFont('courier', '', 7.2);
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                    $text = $this->renderView('\Formato\formato_recibo_sueldos.html.twig', array('texto' => $txt));

                    $pdf->SetMargins(8, 13);
                    
                    $pdf->Write(0, $text);
                    $file = $pdf->Output('recibos.pdf', 'S');

                    $response = new Response($file);

                    $response->headers->set('Content-Type', 'application/pdf');
                    $response->headers->set('Content-Disposition', 'filename="recibos.pdf"');

                    return $response;
                }else if($form->get('generarLibro')->isClicked()){
        
                    $pdf = new ReportPDF();
                    
                    $pdf->setPrintHeader(false); 
                    
                    $pdf->AddPage();

                    $pdf->SetFont('courier', '', 7.2);
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                    $text = $this->renderView('\Formato\formato_recibo_sueldos.html.twig', array('texto' => $txt));
                    
                    $pdf->SetMargins(2, 13);
                    
                    $pdf->Write(0, $text);
                    $file = $pdf->Output('recibos.pdf', 'S');

                    $response = new Response($file);

                    $response->headers->set('Content-Type', 'application/pdf');
                    $response->headers->set('Content-Disposition', 'filename="recibos.pdf"');

                    return $response;
                }else if($form->get('generarAportes')->isClicked()){
        
                    $pdf = new ReportPDF();
                    
                    $pdf->setPrintHeader(false); 
                    $pdf->AddPage();

                    $pdf->SetFont('courier', '', 7);
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                    $text = $this->renderView('\Formato\formato_recibo_sueldos.html.twig', array('texto' => $txt));
                    
                    $pdf->SetMargins(2, 13);
                    
                    $pdf->Write(0, $text);
                    $file = $pdf->Output('recibos.pdf', 'S');

                    $response = new Response($file);

                    $response->headers->set('Content-Type', 'application/pdf');
                    $response->headers->set('Content-Disposition', 'filename="recibos.pdf"');

                    return $response;
                }
            }
//        }
        return $this->render('\Formato\importarTxt.html.twig',
            array('form' => $form->createView(),)
        );
    }
}