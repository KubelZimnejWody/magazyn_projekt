<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\WarehouseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountUserController extends AbstractController
{
    #[Route('/user', name: 'app_account_user')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function index(WarehouseRepository $repo, UserInterface $user, ItemRepository $repo2, ): Response
    {
        
        $warehouses = $repo->findUserWarehouses($user->getId());
//        $items = $repo2->findItems();
//        dd($warehouses);

        if (in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles(), true))
        {
            return $this->redirectToRoute('app_account');
        }
        return $this->render('account_user/index.html.twig', [
            'warehouses' => $warehouses,
//            'items' => $items,
        ]);
    }
}
