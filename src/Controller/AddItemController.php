<?php

namespace App\Controller;

use App\Entity\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\AddItemFormType;

class AddItemController extends AbstractController
{
    #[Route('/add/item', name: 'app_add_item')]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $item = new Item();


        $form = $this->createForm(AddItemFormType::class, $item);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $item = $form->getData();

            $item->setWarehouse($item->getWarehouse());
            $item->setName($item->getName());
            $item->setQuantity($item->getQuantity());
            $item->setUnit($item->getUnit());
            $item->setVat($item->getVat());
            $item->setPrice($item->getPrice());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_account');

        }

        return $this->render('add_item/index.html.twig', [
            'addItemForm' => $form->createView()
        ]);
    }
}
