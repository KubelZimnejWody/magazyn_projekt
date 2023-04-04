<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Form\AddUserFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class AddUserController extends AbstractController
{
    #[Route('/add/user', name: 'app_add_user')]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = new User();

        $form = $this->createForm(AddUserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            $user->setUserName($user->getUsername());
            $user->setRoles($user->getRoles());
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            $user->setPassword($hashedPassword);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('app_account');

        }

        return $this->render('add_user/index.html.twig', [
            'addUserForm' => $form->createView()
        ]);
    }
}
