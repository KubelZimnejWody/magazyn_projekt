<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\WarehouseItem;
use App\Form\AddItemFormType;
use App\Form\AssignItemFormType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    #[Route("/items", name: "app_items_list")]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function listItems(ItemRepository $itemRepository): Response
    {
        return $this->render("items/list.html.twig", [
            "items" => $itemRepository->findAll()
        ]);
    }

    #[Route("/items/add", name: "app_items_add")]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function addItem(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(AddItemFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $item = new Item();
            $item->setName($data['name']);
            $item->setUnit($data['unit']);
            $item->setVat($data['vat']);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_items_list');
        }

        return $this->render("items/add.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route("/items/assign", name: "app_items_assign")]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function assignItem(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, ItemRepository $ir, ManagerRegistry $doctrine) : Response
    {
        $warehouseItem = new WarehouseItem();
        $itemId = $request->get('itemId');

        $form = $this->createForm(AssignItemFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
//            $warehouseItem->setItem($itemId->getItem());
            $item = $ir->find($itemId);
            $warehouseItem->setItem($item);
            $warehouseItem->setQuantity($form->get('quantity')->getData());
            $warehouseItem->setWarehouse($form->get('id')->getData());
            $warehouseItem->setPrice($form->get('price')->getData());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($warehouseItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_items_list');
        }

        return $this->render('items/add.html.twig', [
            'AssignItemForm' => $form->createView(),
            'itemId' => $itemId,
        ]);
    }
}