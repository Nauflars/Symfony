<?php

// src/OC/PlatformBundle/Controller/AdvertController.php
namespace OC\PlatformBundle\Controller;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OC\PlatformBundle\Entity\AdvertSkill;

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
        $em = $this->getDoctrine()->getManager();
        
        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
        
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        
        // On récupère la liste des candidatures de cette annonce
        $listApplications = $em
        ->getRepository('OCPlatformBundle:Application')
        ->findBy(array('advert' => $advert))
        ;
        
        // On récupère la liste des skills de cette annonce
        $listSkills = $em
        ->getRepository('OCPlatformBundle:AdvertSkill')
        ->findBy(array('advert' => $advert))
        ;
        
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert'           => $advert,
            'listApplications' => $listApplications,
            'listSkills' => $listSkills
        ));
    }
    

    public function addAction(Request $request)
    {
      
   
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        
        // Création de l'entité Advert
        $advert = new Advert();
        $advert->setTitle('Recherche dveloppeur Symfony.');
        $advert->setAuthor('Alexandre');
        $advert->setContent("Nous recherchons un dveloppeur Symfony dbutant sur Lyon. Blabl");
        $advert->setDate(new \Datetime());
        // On récupère toutes les compétences possibles
        $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();
        
        // Pour chaque compétence
        foreach ($listSkills as $skill) {
            // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
            $advertSkill = new AdvertSkill();
            
            // On la lie à l'annonce, qui est ici toujours la même
            $advertSkill->setAdvert($advert);
            // On la lie à la compétence, qui change ici dans la boucle foreach
            $advertSkill->setSkill($skill);
            
            // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
            $advertSkill->setLevel('Expert');
            
            // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
            $em->persist($advertSkill);
        }
        
        // Doctrine ne connait pas encore l'entité $advert. Si vous n'avez pas défini la relation AdvertSkill
        // avec un cascade persist (ce qui est le cas si vous avez utilisé mon code), alors on doit persister $advert
        $em->persist($advert);
        
        // On déclenche l'enregistrement
        $em->flush();
    }
    

    public function editAction($id, Request $request)
    {      
        $em = $this->getDoctrine()->getManager();
        
        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
        
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        
        // La méthode findAll retourne toutes les catégories de la base de données
        $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();
        
        // On boucle sur les catégories pour les lier à l'annonce
        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }
        
        // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
        // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine
        
        // Étape 2 : On déclenche l'enregistrement
        $em->flush();
        
    }
    public function deleteAction($id)
    {
        // Ici, on r�cup�rera l'annonce correspondant � $id

        // Ici, on g�rera la suppression de l'annonce en question
        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}