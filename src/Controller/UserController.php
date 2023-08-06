<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddUserFormType;
use App\Repository\UserRepository;
use App\Repository\WarehouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_users_list')]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function listUsers(UserRepository $userRepository): Response
    {
        return $this->render("users/list.html.twig", [
            "users" => $userRepository->findAll()
        ]);
    }

    #[Route('/users/add', name: "app_users_add")]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddUserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $user = new User();

            $user->setUsername($formData["username"]);
            $user->setRoles([$formData["roles"]]);
            $user->setPassword(password_hash($formData["password"], PASSWORD_DEFAULT));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_users_list');
        }

        return $this->render("users/add.html.twig", [
            "addUserForm" => $form->createView()
        ]);
    }
}
