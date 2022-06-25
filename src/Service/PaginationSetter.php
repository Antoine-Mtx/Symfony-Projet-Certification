<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationSetter
{

    public function paginate($query, Request $request, int $nbItemsParPage): Paginator
    {
        $page = $request->query->getInt('p') ?: 1;
        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult($nbItemsParPage * ($page - 1))
            ->setMaxResults($nbItemsParPage);

        return $paginator;
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