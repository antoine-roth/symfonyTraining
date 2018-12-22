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


  
  public function hasApplication(Advert $advert)
  {
    return $advert->getNbApplications()>0;
  }

  public function deleteAdvert($advert){

    $advertskills = $this->em->getRepository('OCPlatformBundle:AdvertSkill')->findBy(
      array('advert'=> $advert)
    );
    
    foreach ($advertskills as $advertskill){
      $this->em->remove($advertskill);
    }

    $this->em->remove($advert);
    $this->em->flush();
  }

  public function purge($days){

    $adverts=$this->em->getRepository('OCPlatformBundle:Advert')->findAll();
    
    $date = date_create(date("Y-m-d H:i:s"));
    $intervalLimite=date_interval_create_from_date_string(strval($days).' days');
    $deletedAdverts = array();
    
    foreach ($adverts as $advert){

      if($this->hasApplication($advert)){}
      else{
        $dateTest = $advert->getDate();
        $interval=date_diff($date,$dateTest);
        
        if(intval($interval->format('%d'))>intval($intervalLimite->format('%d'))){
          
          $deletedAdverts[]=$advert->getTitle();
          $this->deleteAdvert($advert);
        }
      }



    }
    $endText = "les annonces suivantes ont été supprimées : ";
    if(sizeof($deletedAdverts)>0){
      foreach ($deletedAdverts as $deletedAdvert){
        $endText= $endText.$deletedAdvert.", ";

      }
      return $endText;

    }
    return "aucune annonce à supprimer";
    
  }



}
