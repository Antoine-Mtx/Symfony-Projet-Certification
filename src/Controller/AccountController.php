<?php

namespace App\Controller;

use App\Form\ResetPasswordType;
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
     * @Route("/account/update_password", name="update_account")
     */
    public function updatePassword(ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();
        // on crée un formulaire dévolu à la modification du compte
        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $oldPassword = $form->get('oldPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            if (!$userPasswordHasher->isPasswordValid($user, $oldPassword)) {            
                $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));
            }

            $entityManager->flush();

            return $this->redirectToRoute('index_profile');
        }

        return $this->render('account/updatePassword.html.twig', [
            'formUpdatePassword' => $form->createView(),
        ]);
    }
}
