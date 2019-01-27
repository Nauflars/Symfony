<?php
// src/OC/PlatformBundle/Entity/Advert.php

namespace OC\PlatformBundle\Entity;

// On définit le namespace des annotations utilisées par Doctrine2
// En effet, il existe d'autres annotations, on le verra par la suite,
// qui utiliseront un autre namespace
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Advert_old
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="date", type="date")
     */
    protected $date;
    
    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;
    
    /**
     * @ORM\Column(name="author", type="string", length=255)
     */
    protected $author;
    
    /**
     * @ORM\Column(name="content", type="text")
     */
    protected $content;
    
    // Les getters
    // Les setters
}