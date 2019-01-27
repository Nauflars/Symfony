<?php

// src/OC/PlatformBundle/Controller/AdvertController.php
namespace OC\PlatformBundle\Controller;
use OC\PlatformBundle\Entity\Advert;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{

    
    public function indexAction($page)
    {
        $mailer = $this->container->get('mailer'); 
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
        // On récupère le repository
        $repository = $this->getDoctrine()
        ->getManager()
        ->getRepository('OCPlatformBundle:Advert')
        ;
        
        // On récupère l'entité correspondante à l'id $id
        $advert = $repository->find($id);
        
        // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        
        // Le render ne change pas, on passait avant un tableau, maintenant un objet
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert
        ));
    }
    

    public function addAction(Request $request)
    {
        // Cr�ation de l'entit�
        $advert = new Advert();
        $advert->setTitle('Recherche dveloppeur Symfony.');
        $advert->setAuthor('Alexandre');
        $advert->setContent("Nous recherchons un dveloppeur Symfony dbutant sur Lyon. Blabla");
        $advert->setDate(new \Datetime());
        // On peut ne pas d�finir ni la date ni la publication,
        // car ces attributs sont d�finis automatiquement dans le constructeur
        
        // On r�cup�re l'EntityManager
        $em = $this->getDoctrine()->getManager();
        
        // �tape 1 : On � persiste � l'entit�
        $em->persist($advert);
        
        // �tape 2 : On � flush � tout ce qui a �t� persist� avant
        $em->flush();
        
        // Reste de la m�thode qu'on avait d�j� �crit
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistr�e.');
            
            // Puis on redirige vers la page de visualisation de cettte annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }
        
        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
    }
    

    public function editAction($id, Request $request)
    {      
        $advert = array(
            'title'   => 'Recherche d�velopppeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un d�veloppeur Symfony d�butant sur Lyon. Blabla�',
            'date'    => new \Datetime()
        );
        
        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
        ));
    }
    public function deleteAction($id)
    {
        // Ici, on r�cup�rera l'annonce correspondant � $id

        // Ici, on g�rera la suppression de l'annonce en question
        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}