<?php

// src/OC/PlatformBundle/Controller/AdvertController.php
namespace OC\PlatformBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{

    
    public function indexAction($page)
    {
        // Notre liste d'annonce en dur
        $listAdverts = array(
            array(
                'title' => 'Recherche d�velopppeur Symfony',
                'id' => 1,
                'author' => 'Alexandre',
                'content' => 'Nous recherchons un d�veloppeur Symfony d�butant sur Lyon Blabl',
                'date' => new \Datetime()
            ),
            array(
                'title' => 'Mission de webmaster',
                'id' => 2,
                'author' => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet Blabl',
                'date' => new \Datetime()
            ),
            array(
                'title' => 'Offre de stage webdesigner',
                'id' => 3,
                'author' => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner Blabl',
                'date' => new \Datetime()
            )
        );
        
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }
    
    
    public function menuAction()
    {
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la r�cup�rera depuis la BDD !
        $listAdverts = array(
            array(
                'id' => 2,
                'title' => 'Recherche d�veloppeur Symfony'
            ),
            array(
                'id' => 5,
                'title' => 'Mission de webmaster'
            ),
            array(
                'id' => 9,
                'title' => 'Offre de stage webdesigner'
            )
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(

            'listAdverts' => $listAdverts
        ));
    }

    public function viewAction($id)
    {
        $advert = array(
            'title' => 'Recherche d�velopppeur Symfony2',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous recherchons un d�veloppeur Symfony2 d�butant sur Lyon Blabl',
            'date' => new \Datetime()
        );

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert
        ));
    }

    public function addAction(Request $request)
    {
        // La gestion d'un formulaire est particuli�re, mais l'id�e est la suivante :

        // Si la requ�te est en POST, c'est que le visiteur a soumis le formulaire
        if ($request->isMethod('POST')) {
            // Ici, on s'occupera de la cr�ation et de la gestion du formulaire

            $request->getSession()
                ->getFlashBag()
                ->add('notice', 'Annonce bien enregistr�e.');

            // Puis on redirige vers la page de visualisation de cettte annonce
            return $this->redirectToRoute('oc_platform_view', array(
                'id' => 5
            ));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request)
    {
        // Ici, on r�cup�rera l'annonce correspondante � $id

        // M�me m�canisme que pour l'ajout
        if ($request->isMethod('POST')) {
            $request->getSession()
                ->getFlashBag()
                ->add('notice', 'Annonce bien modifi�e.');

            return $this->redirectToRoute('oc_platform_view', array(
                'id' => 5
            ));
        }

        return $this->render('OCPlatformBundle:Advert:edit.html.twig');
    }

    public function deleteAction($id)
    {
        // Ici, on r�cup�rera l'annonce correspondant � $id

        // Ici, on g�rera la suppression de l'annonce en question
        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}