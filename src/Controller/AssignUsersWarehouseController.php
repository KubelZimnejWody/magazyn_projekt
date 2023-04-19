<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssignUsersWarehouseController extends AbstractController
{
    #[Route('/assign/users/warehouse', name: 'app_assign_users_warehouse')]
    public function index(): Response
    {
        return $this->render('assign_users_warehouse/index.html.twig', [
            'controller_name' => 'AssignUsersWarehouseController',
        ]);
    }
}
