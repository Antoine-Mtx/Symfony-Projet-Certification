<?php

namespace App\Controller;

use DateTime;
use App\Entity\Composant;
use App\Form\ComposantType;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ComposantController extends AbstractController
{

    // la liste des composants étant massive, on met en place une pagination et on définit donc l'argument $page par défaut à la valeur 1

    /**
     * @Route("/composant", name="index_composant")
     * @Route("/composant/page/{page}", name="page_composant")
     * 
     * @param integer $page
     */
    public function index(ManagerRegistry $doctrine, Request $request, int $page): Response
    {
        $nbComposants = $doctrine->getRepository(Composant::class)->count([]);

        // configuration de la pagination
        $page = isset($page) ? $page : 1;
        dd($page);
        $nbComposantsParPage = 3;
        $nbPages = ceil($nbComposants / $nbComposantsParPage);
        $composantsDeLaPage = $doctrine->getRepository(Composant::class)->findBy([], ['date_creation' => 'DESC'], $nbComposantsParPage, ($page - 1) * $nbComposantsParPage);  // on désigne le repository de la classe "composant" à notre gestionnaire $doctrine puis on utilise la méthode findBy() pour récupérer tous les composants de la page concernée

        $indexPremierComposant = ($page - 1) * $nbComposantsParPage + 1;
        $indexDernierComposant = min($indexPremierComposant + $nbComposantsParPage - 1, $nbComposants);

        return $this->render('composant/index.html.twig', [
            'composantsDeLaPage' => $composantsDeLaPage,
            'page' => $page,
            'nbPages' => $nbPages,
            'nbComposants' => $nbComposants,
            'indexPremierComposant' => $indexPremierComposant,
            'indexDernierComposant' => $indexDernierComposant,            
        ]);
    }

    /**
     * @Route("/composant/add", name="add_composant")
     * @Route("/composant/update/{id}", name="update_composant")
     */
    public function add(ManagerRegistry $doctrine, Composant $composant = NULL, Request $request, FileUploader $fileUploader) {

        // si le composant existe on est dans le cas "update", sinon on est dans le cas "add" et il faut créer une instance de la classe Composant
        if (! $composant) {
            $composant = new Composant();
        }

        // on va utiliser doctrine pour ajouter / actualiser notre composant au niveau de la base de données
        $entityManager = $doctrine->getManager();

        // on crée un formulaire dévolu à l'ajout de composant
        $form = $this->createForm(ComposantType::class, $composant);
        $form->handleRequest($request);

        // on récupère l'utilisateur connecté
        $user = $this->getUser();
        // on récupère la date du jour
        $aujourdhui = new DateTime();

        // on vérifie que le formulaire rempli est conforme
        if ($form->isSubmitted() && $form->isValid()) {
            // on définit les attributs de notre objet composant avec les données du formulaires
            $composant = $form->getData();
            // on définit l'utilisateur connecté comme concepteur
            $composant->setConcepteur($user);
            // on définit la date du jour comme date de création du composant
            $composant->setDateCreation($aujourdhui);
            
            /** @var UploadedFile $file */
            $file = $form->get('filename')->getData();
            if ($file) {
                $filename = $fileUploader->upload($file, "/composant/graph");
                // on définit le chemin de notre fichier
                $composant->setFilename($filename);
            }

            // on prépare l'objet à l'enregistrement
            $entityManager->persist($composant);
            // on enregistre l'objet créé dans notre base de données
            $entityManager->flush();

            // une fois le composant créé, on redirige l'utilisateur vers la liste des composants
            return $this->redirectToRoute('mes_composants');
        }

        return $this->render('composant/add.html.twig', [
            'formComposant' => $form->createView(),
            'composantId' => $composant->getId(),
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

    /**
     * @Route("/composant/delete/{id}", name="delete_composant")
     */
    public function delete(ManagerRegistry $doctrine, Composant $composant): Response
    {
        $entityManager = $doctrine->getManager();
        $competence = $composant->getCompetence();

        if ($competence) {
            $competence->removeComposant($composant);
        }
        
        $entityManager->remove($composant);
        $entityManager->flush();

        // on se sert de la méthode headers de l'objet request pour rediriger l'utilisateur sur la page d'où il vient
        return $this->redirectToRoute('mes_composants');
    }    
}
