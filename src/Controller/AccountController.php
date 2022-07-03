<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="index_account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/account/edit_account", name="edit_account")
     */
    public function changePassword(ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();
        // on crée un formulaire dévolu à la modification du compte
        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $oldPassword = $form->get('oldPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            if ($userPasswordHasher->isPasswordValid($user, $oldPassword)) {            
                $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));
            }

            $entityManager->flush();

            return $this->redirectToRoute('index_profile');
        }

        return $this->render('account/changePassword.html.twig', [
            'formChangePassword' => $form->createView(),
        ]);
    }
}
