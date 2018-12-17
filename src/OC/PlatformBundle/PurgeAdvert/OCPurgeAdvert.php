<?php
// src/OC/PlatformBundle/Purge/OCPurgeAdvert.php

namespace OC\PlatformBundle\PurgeAdvert;

use Doctrine\ORM\EntityManager;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Application;

class OCPurgeAdvert
{
    /**
    *
    * @var EntityManager 
    */
   protected $em;

   public function __construct(EntityManager $entityManager)
   {
    $this->em = $entityManager;
   }


  /**
   * Vérifie si une annonce a des applications ou pas
   *
   * @param Advert $advert
   * @return bool
   */
  public function hasApplication(Advert $advert)
  {
    return $advert->getNbApplications()>0;
  }

  public function purge($days){



  }



}
