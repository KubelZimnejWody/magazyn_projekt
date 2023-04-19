<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Warehouse;
use App\Form\EraseItemFormType;
use App\Form\EraseItemWarehouseFormType;
use App\Form\ReleaseItemFormType;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use MongoDB\Driver\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class EraseItemController extends AbstractController
{
    #[Route('/erase/item', name: 'app_erase_item')]
    public function index(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $form = $this->createForm(EraseItemFormType::class);
        $form->handleRequest($request);
//        $name = $session ->get('name');

        if($form->isSubmitted() && $form->isValid())
        {
            $warehouse = $form->get('name')->getData();
//            $session = $request->getSession();
//            $session->set('name', $warehouse);

//            dd($warehouse);
            return $this->redirectToRoute('app_release_item_user', ['warehouseId'=>$warehouse[0]->getId()]);

        }

        return $this->render('erase_item/index.html.twig', [
            'EraseItemForm' => $form->createView(),
        ]);
    }

    #[Route('/erase/item/user', name: 'app_release_item_user', methods: ['POST', 'GET'])]
    public function releaseItem(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, WarehouseRepository $wr) : Response
    {
//        dd($request);
        $warehouseId = $request->query->get('warehouseId');
        $warehouse = $wr->find($warehouseId) ;

//        dd($warehouse);
        $form = $this->createForm(EraseItemWarehouseFormType::class, [], [
            "warehouseId" => $warehouseId,
        ]);
        $form->handleRequest($request);

//        $warehouse = $session->get('name');
        $name = $session->get('name');

        if($form->isSubmitted() && $form->isValid())
        {
            $id = $form->get('id')->getData();
            $quantity = $form->get('quantity')->getData();


//            $entityManager->remove($item);
//            $entityManager->flush();
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder ->update(Item::class, 'i')
                ->set('i.quantity', 'i.quantity - :quantity')
                ->where('i.id = :id' )
                ->setParameter('id', $id->getId())
                ->setParameter('quantity', $quantity)
                ->setFirstResult(0)
                ->setMaxResults($quantity);


            $queryBuilder->getQuery()->execute();
        }

        return $this->render('erase_item/eraseItem.html.twig',[
            'EraseItemWarehouseForm' => $form->createView(),
            'name' => $name
        ]);

    }
}