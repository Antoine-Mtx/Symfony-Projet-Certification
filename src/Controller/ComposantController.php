<?php

namespace App\Controller;

use App\Entity\Composant;
use App\Form\ComposantType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ComposantController extends AbstractController
{
    /**
     * @Route("/composant", name="index_composant")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $composants = $doctrine->getRepository(Composant::class)->findAll(); // on désigne le repository de la classe "composant" à notre gestionnaire $doctrine puis on utilise la méthode findAll() pour récupérer toutes les instances de cette classe

        return $this->render('composant/index.html.twig', [
            'composants' => $composants,
        ]);
    }

    /**
     * @Route("/composant/add", name="add_composant")
     * @Route("/composant/update/{id}", name="update_composant")
     */
    public function add(ManagerRegistry $doctrine, Composant $composant = NULL, Request $request) {

        if (! $composant) {
            $composant = new Composant();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(ComposantType::class, $composant); // on crée un formulaire dévolu à l'ajout de "composant" de composant
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $composant = $form->getData();
            $entityManager->persist($composant);
            $entityManager->flush();

            return $this->redirectToRoute('index_composant');
        }

        return $this->render('composant/add.html.twig', [
            'formComposant' => $form->createView(),
        ]);
    }

    /**
     * @Route("/composant/{id}", name="show_composant")
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $composant = $doctrine->getRepository(Composant::class)->find($id); // on récupère l'objet de la classe "composant" ayant pour id "$id"

        if (! $composant) {
            throw $this->createNotFoundException(
                "Aucun composant répertorié avec l'id $id"
            );
        }

        return $this->render('composant/show.html.twig', [ // notre méthode rend le template "composant/show.html.twig" où on pourra afficher les incomposants accessibles de notre objet composant avec "{{ composant }}"
            'composant' => $composant,
        ]);
    }
}
