<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="index_profile")
     */
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    /**
     * @Route("/profile/update", name="update_profile")
     */
    public function update(ManagerRegistry $doctrine, Request $request): Response
    {
    $entityManager = $doctrine->getManager();
    $user = $this->getUser();
    // on crée un formulaire dévolu à la modification de profil
    $form = $this->createForm(ProfileType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $profile = $form->getData();
        $entityManager->persist($profile);
        $entityManager->flush();

        return $this->redirectToRoute('index_profile');
    }

    return $this->render('profile/components/update.html.twig', [
        'formProfile' => $form->createView(),
    ]);
}

    /**
     * @Route("/profile/delete", name="delete_profile")
     */
    public function deleteProfile(): Response
    {
        return $this->render('profile/components/delete.html.twig');
    }
}
