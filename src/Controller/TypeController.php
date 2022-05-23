<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeController extends AbstractController
{
    /**
     * @Route("/type", name="index_type")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $types = $doctrine->getRepository(Type::class)->findAll(); // on désigne le repository de la classe "Type" à notre gestionnaire $doctrine puis on utilise la méthode findAll() pour récupérer toutes les instances de cette classe

        return $this->render('type/index.html.twig', [
            'types' => $types,
        ]);
    }

    /**
     * @Route("/type/add", name="add_type")
     * @Route("/type/update/{id}", name="update_type")
     */
    public function add(ManagerRegistry $doctrine, Type $type = NULL, Request $request) {

        if (! $type) {
            $type = new Type();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(TypeType::class, $type); // on crée un formulaire dévolu à l'ajout de "type" de composant
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = $form->getData();
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('index_type');
        }

        return $this->render('type/add.html.twig', [
            'formType' => $form->createView(),
        ]);
    }

    /**
     * @Route("/type/{id}", name="show_type")
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $type = $doctrine->getRepository(Type::class)->find($id); // on récupère l'objet de la classe "Type" ayant pour id "$id"

        if (! $type) {
            throw $this->createNotFoundException(
                "Aucun type répertorié avec l'id $id"
            );
        }

        return $this->render('type/show.html.twig', [ // notre méthode rend le template "type/show.html.twig" où on pourra afficher les intypes accessibles de notre objet type avec "{{ type }}"
            'type' => $type,
        ]);
    }
}
