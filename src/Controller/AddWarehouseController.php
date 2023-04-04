<?php

namespace App\Controller;

use App\Entity\Warehouse;
use App\Form\AddWarehouseFormType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WarehouseRepository;

class AddWarehouseController extends AbstractController
{
    #[Route('/add/warehouse', name: 'app_add_warehouse')]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $warehouse = new Warehouse();
        $form = $this->createForm(AddWarehouseFormType::class, $warehouse);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $warehouse->setName($warehouse->getName());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($warehouse);
            $entityManager->flush();

            return $this->redirectToRoute('app_account');

        }

        return $this->render('add_warehouse/index.html.twig', [
            'addWarehouseForm' => $form->createView()
        ]);
    }
}
