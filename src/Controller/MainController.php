<?php

namespace App\Controller;

use App\Repository\WarehouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{
    #[Route("/", name: "app_index")]
    public function index(WarehouseRepository $repo, UserInterface $user): Response
    {
        if ($this->getUser()) {
            $warehouses = $this->isGranted("ROLE_SUPER_ADMIN") ? $repo->findAll() : $repo->findUserWarehouses($user->getId());

            return $this->render("account/user.html.twig", [
                "warehouses" => $warehouses
            ]);
        }

        return $this->redirectToRoute('app_login');
    }
}
