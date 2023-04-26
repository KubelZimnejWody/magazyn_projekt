<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Warehouse;
use App\Form\AssignUserWarehouseFormType;
use App\Form\AssignUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WarehouseRepository;


class AssignUsersWarehouseController extends AbstractController
{
    #[Route('/assign/users/warehouse', name: 'app_assign_users_warehouse')]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(AssignUserWarehouseFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $warehouseId = $form->get('warehouse')->getData()->getId();

            return $this->redirectToRoute('app_assign_users_user', ['warehouseId'=>$warehouseId]);
        }

        return $this->render('assign_users_warehouse/index.html.twig', [
            'AssignUserWarehouseForm' => $form->createview(),
        ]);
    }

    #[Route('/assign/item/user/user', name: 'app_assign_users_user', methods: ['POST', 'GET'])]
    public function assignUsers(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, WarehouseRepository $wr) : Response
    {
        $warehouseId = $request->query->get("warehouseId");

        $form = $this->createForm(AssignUserFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $id = $form->get('id')->getData()->getId();
            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->find($id);

            $warehouse = $wr->find($warehouseId);
            $user->addWarehouse($warehouse);
//            $warehouse = $doctrine->getRepository(Warehouse::class)->find($warehouseId);

//            $id->addWarehouse($warehouse);


//            $entityManager = $doctrine->getManager();
//            $entityManager->persist();
            $entityManager->flush();

            return $this->redirectToRoute('app_account');
        }

        return $this->render('assign_users_warehouse/assignUsers.html.twig', [
            'AssignUserForm' => $form->createview(),
            'warehouses' => $warehouseId,
        ]);
    }

}


