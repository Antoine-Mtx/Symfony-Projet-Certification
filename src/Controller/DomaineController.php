<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DomaineController extends AbstractController
{
    /**
     * @Route("/domaine", name="index_domaine")
     */
    public function index(ManagerRegistry $doctrine): Response// on injecte le service Doctrine dans la méthode index de notre controller, ce qui nous permettra d'interagir avec notre base de données
    {
        $domaines = $doctrine->getRepository(Domaine::class)->findAll(); // on désigne le repository de la classe "Domaine" à notre gestionnaire $doctrine puis on utilise la méthode findAll() pour récupérer toutes les instances de cette classe

        return $this->render('domaine/index.html.twig', [
            'domaines' => $domaines,
        ]);
    }

    /**
     * @Route("/domaine/add", name="add_domaine")
     * @Route("/domaine/update/{id}", name="update_domaine")
     */
    public function add(ManagerRegistry $doctrine, Domaine $domaine = NULL, Request $request) {

        if (! $domaine) {
            $domaine = new Domaine();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(DomaineType::class, $domaine); // on crée un formulaire dévolu à l'ajout de domaine
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $domaine = $form->getData();
            $entityManager->persist($domaine);
            $entityManager->flush();

            return $this->redirectToRoute('index_domaine');
        }

        return $this->render('domaine/add.html.twig', [
            'formDomaine' => $form->createView(),
        ]);
    }
}
