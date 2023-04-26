<?php

namespace App\Controller;

use App\Form\AddInvoiceFormType;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddInvoiceController extends AbstractController
{
    #[Route('/add/invoice', name: 'app_add_invoice')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $html = $this->renderView('add_invoice/index.html.twig');

        $pdf= new \Dompdf\Dompdf();

        $pdf->load_html($html);

        $pdf->setPaper('A4', 'Portrait');

        $pdfContent = $pdf->output();

        $resposne = new Response($pdfContent);

        $resposne->headers->set('Content-Disposition', 'attachment; filename="document.pdf');

        return $resposne;
    }
//    public function generatePdf(): Response
//    {
//
//    }
}
