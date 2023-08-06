<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Warehouse;
use App\Entity\WarehouseItem;
use App\Form\AddItemFormType;
use App\Form\AddUserFormType;
use App\Form\AddWarehouseItemFormType;
use App\Form\ReceiveItemFormType;
use App\Form\ReleaseItemWarehouseFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class WarehouseItemController extends AbstractController
{
    #[Route("/warehouse-items/receive", name: "app_warehouse_items_receive")]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function receiveWarehouseItem(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $warehouseId = $request->get('warehouseId');

        $form = $this->createForm(ReceiveItemFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quantity = $form->get('quantity')->getData();

            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder ->update(WarehouseItem::class, 'i')
                ->set('i.quantity', 'i.quantity + :quantity')
                ->where('i.warehouse = :warehouse')
                ->setParameter('warehouse', $warehouseId)
                ->setParameter('quantity', $quantity)
                ->setFirstResult(0)
                ->setMaxResults($quantity);

            $queryBuilder->getQuery()->execute();

            $invoiceFile = $form->get('invoice')->getData();

            if($invoiceFile){
                $originalFileName = pathinfo($invoiceFile->getClientOriginalName(), PATHINFO_FILENAME);
                $saveFileName = $slugger->slug($originalFileName);
                $newFileName = $saveFileName.'-'.uniqid().'.'.$invoiceFile->guessExtension();

                try{
                    $invoiceFile->move(
                        $this->getParameter('invoices_directory'),
                        $newFileName
                    );
                }catch (FileException $e){

                }
            }


            return $this->redirectToRoute('app_index');
        }

        return $this->render("warehouseItems/receive_item.html.twig", [
            "ReceiveItemForm" => $form->createView()
        ]);
    }

    #[Route("/warehouse-items/release", name: "app_warehouse_items_release")]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function releaseWarehouseItem(Request $request, EntityManagerInterface $entityManager): Response
    {
        $warehouseItemId = $request->get('warehouseItemId');

        $form = $this->createForm(ReleaseItemWarehouseFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quantity = $form->get('quantity')->getData();

            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder ->update(WarehouseItem::class, 'i')
                ->set('i.quantity', 'i.quantity - :quantity')
                ->where('i.id = :warehouseItemId')
                ->setParameter('warehouseItemId', $warehouseItemId)
                ->setParameter('quantity', $quantity)
                ->setFirstResult(0)
                ->setMaxResults($quantity);

            $queryBuilder->getQuery()->execute();

            return $this->redirectToRoute('app_index');
        }

        return $this->render("warehouseItems/release_item.html.twig", [
            "ReleaseItemWarehouseForm" => $form->createView()
        ]);
    }

    #[Route("/warehouse-items/add", name: "app_warehouse_items_add")]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function addWarehouseItem(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $warehouseId = $request->get('warehouseId');

        $form = $this->createForm(AddWarehouseItemFormType::class, null, [
            'warehouseId' => $warehouseId
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $warehouseRepository = $doctrine->getRepository(Warehouse::class);
            $warehouse = $warehouseRepository->find($warehouseId);


            $warehouseItem = new WarehouseItem();
            $warehouseItem->setItem($data['item']);
            $warehouseItem->setWarehouse($warehouse);
            $warehouseItem->setPrice($data['price']);
            $warehouseItem->setQuantity($data['quantity']);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($warehouseItem);
            $entityManager->flush();

            $invoiceFile = $form->get('invoice')->getData();

            if($invoiceFile){
                $originalFileName = pathinfo($invoiceFile->getClientOriginalName(), PATHINFO_FILENAME);
                $saveFileName = $slugger->slug($originalFileName);
                $newFileName = $saveFileName.'-'.uniqid().'.'.$invoiceFile->guessExtension();

                try{
                    $invoiceFile->move(
                        $this->getParameter('invoices_directory'),
                        $newFileName
                    );
                }catch (FileException $e){

                }
            }

            return $this->redirectToRoute('app_index');
        }

        return $this->render('warehouseItems/add.html.twig', [
            'AddWarehouseItemForm' => $form->createView()
        ]);
    }
}
