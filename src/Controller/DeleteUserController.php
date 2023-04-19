<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\DeleteUserFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class DeleteUserController extends AbstractController
{
    #[Route('/delete/user/', name: 'app_delete_user')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeleteUserFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $users = $form->get('username')->getData();
//            dd($form, $username, $user);
            if($users)
            {
                foreach ($users as $user) {
                    $entityManager->remove($user);
                    $entityManager->flush();
                }

            }
            else
            {
                $this->addFlash('error', 'Nie można usunąć użytkownika.');
            }

            return $this->redirectToRoute('app_account_user');
        }
        return $this->render('delete_user/index.html.twig', [
            'DeleteUserForm' => $form->createView(),
        ]);
    }
}
