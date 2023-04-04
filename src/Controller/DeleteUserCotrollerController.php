<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\DeleteUserFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
class DeleteUserCotrollerController extends AbstractController
{
    #[Route('/delete/user/cotroller', name: 'app_delete_user_cotroller')]
    public function index(Request $request, ManagerRegistry $doctrine, User $user): Response
    {
        $form = $this->createForm(DeleteUserFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_account_user');
        }
        return $this->render('delete_user_cotroller/index.html.twig', [
            'DeleteUserForm' => $form->createView(),
            'user' => $user,
        ]);
    }
}
