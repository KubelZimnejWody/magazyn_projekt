<?php

namespace App\Controller;

use App\Form\DeleteItemFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteItemController extends AbstractController
{
    #[Route('/delete/item', name: 'app_delete_item')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeleteItemFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $items = $form->get('name')->getData();

            if($items)
            {
                foreach ($items as $item)
                {
                    $entityManager->remove($item);
                    $entityManager->flush();
                }


            }
            else
            {
                $this->addFlash('error', 'Nie można usunąć artykulu.');
            }
            return $this->redirectToRoute('app_account_user');
        }
        else
        {
            $this->addFlash('error', 'Nie można usunąć artykulu.');
        }
        return $this->render('delete_item/index.html.twig', [
            'DeleteItemForm' => $form->createview(),
        ]);
    }
}
