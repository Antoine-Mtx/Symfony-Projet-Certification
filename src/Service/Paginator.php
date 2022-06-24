<?php

namespace App\Service;

class Paginator
{
    // targetDirectory est l'emplacement du dossier où se trouveront les fichiers chargés
    private $targetDirectory;
    // le slugger transforme une chaîne de caractères en une nouvelle ne comportant que des caractères ASCII conformes
    private $slugger;

    // SluggerInterface permet d'injecter directement un slugger grâce au câblage automatique des services
    public function __construct(string $targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function paginate()
    {
        $nbItems = $doctrine->getRepository(Composant::class)->count([]);

        // configuration de la pagination
        $page = isset($page) ? $page : 1;
        dd($page);
        $nbItemsParPage = 3;
        $nbPages = ceil($nbItems / $nbItemsParPage);
        $ItemsDeLaPage = $doctrine->getRepository(Composant::class)->findBy([], ['date_creation' => 'DESC'], $nbItemsParPage, ($page - 1) * $nbItemsParPage);  // on désigne le repository de la classe "composant" à notre gestionnaire $doctrine puis on utilise la méthode findBy() pour récupérer tous les Items de la page concernée

        $indexPremierComposant = ($page - 1) * $nbItemsParPage + 1;
        $indexDernierComposant = min($indexPremierComposant + $nbItemsParPage - 1, $nbItems);

        try {
            $file->move($this->getTargetDirectory().$subdirectoryPath, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }
}