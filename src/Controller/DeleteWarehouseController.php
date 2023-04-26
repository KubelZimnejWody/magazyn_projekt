<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\DeleteWarehouseFormType;

class DeleteWarehouseController extends AbstractController
{
    #[Route('/delete/warehouse', name: 'app_delete_warehouse')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeleteWarehouseFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $warehouses = $form->get('name')->getData();

            if($warehouses)
            {
                foreach ($warehouses as $warehouse){
                    $entityManager->remove($warehouse);
                    $entityManager->flush();
                }

            }
            else
            {
                $this->addFlash('error', 'Nie można usunąć magazynu.');
            }

            return $this->redirectToRoute('app_account_user');
        }
        return $this->render('delete_warehouse/index.html.twig', [
            'DeleteWarehouseForm' => $form->createview(),
        ]);
    }
}
