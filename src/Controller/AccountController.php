<?php

namespace App\Controller;

use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    #[Route('/user', name: 'app_account')]
    public function index(WarehouseRepository $repo, UserInterface $user): Response
    {
        $warehouses = $this->isGranted('ROLE_SUPER_ADMIN') ? $repo->findAll() : $repo->findUserWarehouses($user->getId());

        return $this->render('account/user.html.twig', [
            'warehouses' => $warehouses
        ]);
    }
}
