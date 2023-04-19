<?php

namespace App\Controller;


use App\Form\ReleaseItemFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Item;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class ReleaseItemController extends AbstractController
{
    #[Route('/release/item', name: 'app_release_item')]
    public function index(Request $request, ManagerRegistry $doctrine, UserRepository $user): Response
    {
        $item = new Item();
        $user = $this->getUser();
        $form = $this->createForm(ReleaseItemFormType::class, $item);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $item = $form->getData();

            $item->setName($item->getName());
            $item->setQuantity($item->getQuantity());
            $item->setUnit($item->getUnit());
            $item->setVat((float)$item->getVat());
            $item->setPrice((float)$item->getPrice());
            $item->setWarehouse($item->getWarehouse($user));


            $entityManager = $doctrine->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_account_user');

        }

        return $this->render('release_item/index.html.twig', [
            'ReleaseItemForm' => $form->createView()
        ]);
    }
}
