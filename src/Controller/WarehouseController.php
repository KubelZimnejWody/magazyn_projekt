<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Warehouse;
use App\Form\AddWarehouseFormType;
use App\Form\AssignUserFormType;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WarehouseController extends AbstractController
{
    #[Route("/warehouses", name: "app_warehouses_list")]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function listWarehouses(WarehouseRepository $warehouseRepository): Response
    {
        return $this->render("warehouses/list.html.twig", [
            "warehouses" => $warehouseRepository->findAll()
        ]);
    }

    #[Route("/warehouses/add", name: "app_warehouses_add")]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function addWarehouses(Request $request, ManagerRegistry $doctrine): Response
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

            return $this->redirectToRoute('app_warehouses_list');

        }

        return $this->render('warehouses/add.html.twig', [
            'addWarehouseForm' => $form->createView()
        ]);
    }

    #[Route("/warehouses/delete", name: "app_warehouses_delete")]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function deleteWarehouses(Request $request, EntityManagerInterface $entityManager, WarehouseRepository $wr): Response
    {
        $warehouseName = $request->get('warehouseName');
        $warehouse = $wr->findOneBy(['name' => $warehouseName]);

        if ($warehouse !== null) {
            $entityManager->remove($warehouse);
            $entityManager->flush();
        }


        return $this->redirectToRoute("app_warehouses_list");
    }

    #[Route("/warehouses/assign", name: "app_warehouses_assign")]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function assignUserToWarehouse(Request $request,  WarehouseRepository $wr, EntityManagerInterface $entityManager): Response
    {
        $warehouseId = $request->get('warehouseId');
        $warehouse = $wr->findOneBy(['id' => $warehouseId]);

        $form = $this->createForm(AssignUserFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $id = $form->get('id')->getData()->getId();
            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->find($id);

            $warehouse = $wr->find($warehouseId);
            $user->addWarehouse($warehouse);

            $entityManager->flush();

            return $this->redirectToRoute('app_warehouses_list');
        }

        return $this->render('assign_users_warehouse/assign_user.html.twig', [
            'AssignUserForm' => $form->createview(),
            'warehouses' => $warehouseId,
        ]);
    }

    #[Route("/warehouses/delete_user", name: "app_warehouses_delete_user")]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function deleteUserFromWarehouse(Request $request, EntityManagerInterface $entityManager, WarehouseRepository $wr, UserRepository $ur):Response
    {
        $warehouseId = $request->get('warehouseId');
        $userId = $request->get('userId');
        $warehouse = $wr->find($warehouseId);
        $user = $ur->find($userId);
        //$user = $ur->findOneBy(['users' => $userId]);
        if (!empty($user) && !empty($warehouse)){
            $warehouse->removeUser($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_warehouses_list');
        }

        return $this->render('assign_users_warehouse/assign_user.html.twig', [
            'warehouses' => $warehouseId,
        ]);

    }
}