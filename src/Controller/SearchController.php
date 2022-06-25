<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="app_search")
     */
    public function index(): Response
    {
        return $this->render('searchItem/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    public function searchBar(Request $request)
    {
        $form = $this->createForm('App\Form\SearchType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
   
            // ... perform some action, such as saving the data to the database or search         
        }
        
        return $this->render('searchItem/searchBar.html.twig', [
            'searchForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/handleSearch", name="handleSearch")
     * @param Request $request
     */
    public function handleSearch(Request $request)
    {
        $query = $request->request->get('form')['query'];
        dd($query);
        if($query) {
            $items = $repo->findItemsByName($query);
        }
        return $this->render('search/index.html.twig', [
            'items' => $items
        ]);
    }
}
