<?php

namespace App\Controller;


use App\Form\EraseItemWarehouseFormType;
use App\Form\ReleaseItemFormType;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Item;
use App\Repository\UserRepository;

class ReleaseItemController extends AbstractController
{
    #[Route('/release/item', name: 'app_release_item', methods: ['POST', 'GET'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function index(Request $request, ManagerRegistry $doctrine, UserRepository $user): Response
    {
//        $item = new Item();
//        $user = $this->getUser();
        $form = $this->createForm(ReleaseItemFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $warehouse = $form->get('name')->getData();

            return $this->redirectToRoute('app_release_item_item', ['warehouseId'=>$warehouse->getId()]);

//            $item = $form->getData();
//
//            $item->setName($item->getName());
//            $item->setQuantity($item->getQuantity());
//            $item->setUnit($item->getUnit());
//            $item->setVat((float)$item->getVat());
//            $item->setPrice((float)$item->getPrice());
//            $item->setWarehouse($item->getWarehouse($user));
//
//
//            $entityManager = $doctrine->getManager();
//            $entityManager->persist($item);
//            $entityManager->flush();

//            return $this->redirectToRoute('app_account_user');
        }

        return $this->render('release_item/index.html.twig', [
            'ReleaseItemForm' => $form->createView()
        ]);
    }
    #[Route('/release/item/item', name: 'app_release_item_item')]
    #[Security("is_granted('ROLE_ADMIN')")]
    function releaseItem(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, WarehouseRepository $wr) : Response
    {
        $warehouseId = $request->query->get('warehouseId');

        $form = $this->createForm(EraseItemWarehouseFormType::class, [], [
            "warehouseId" => $warehouseId,
        ]);
        $form->handleRequest($request);

        $name = $session->get('name');

        if($form->isSubmitted() && $form->isValid())
        {
            $id = $form->get('id')->getData();
            $quantity = $form->get('quantity')->getData();
            $unit = $form->get('unit')->getData();


            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder ->update(Item::class, 'i')
                ->set('i.quantity', 'i.quantity + :quantity')
                ->where('i.id = :id' )
                ->setParameter('id', $id->getId())
                ->setParameter('quantity', $quantity)
                ->setFirstResult(0)
                ->setMaxResults($quantity);


            $queryBuilder->getQuery()->execute();

            return $this->redirectToRoute('app_account_user');
        }

        return $this->render('release_item/releaseItem.html.twig',[
            'ReleaseItemWarehouseForm' => $form->createView(),
            'name' => $name
        ]);
    }
}

