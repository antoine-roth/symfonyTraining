<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadAdvert.php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Category;
use OC\PlatformBundle\Entity\Image;

class LoadAdvert implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    
    $adverts =  array(
        (object)array(
            'title' => "First Advert",
            'author' => "Antoine ROTH First of The Name",
            'content' => "This is some kind of stupid test I'm doing",
        ),
        (object)array(
            'title' => "Second Advert",
            'author' => "Antoine ROTH Second of The Name",
            'content' => "This is some kind of stupid test I'm doing",
        ),
        (object)array(
            'title' => "Third Advert",
            'author' => "Antoine ROTH Third of The Name",
            'content' => "This is some kind of stupid test I'm doing",
        ),
        (object)array(
            'title' => "Fourth Advert",
            'author' => "Antoine ROTH ThFourthird of The Name",
            'content' => "This is some kind of stupid test I'm doing",
        ),
        (object)array(
            'title' => "Fifth Advert",
            'author' => "Antoine ROTH Fifth of The Name",
            'content' => "This is some kind of stupid test I'm doing",
        ),
        (object)array(
            'title' => "Sixt Advert",
            'author' => "Antoine ROTH Sixt of The Name",
            'content' => "This is some kind of stupid test I'm doing",
        ),
        (object)array(
            'title' => "Seventh Advert",
            'author' => "Antoine ROTH Seventh of The Name",
            'content' => "This is some kind of stupid test I'm doing",
        ),
        (object)array(
            'title' => "Eighth Advert",
            'author' => "Antoine ROTH Eighth of The Name",
            'content' => "This is some kind of stupid test I'm doing",
        ),

    );

    foreach ($adverts as $advert) {


      $adv = new Advert();
      $adv->setTitle($advert->title);
      $adv->setAuthor($advert->author);
      $adv->setContent($advert->content);
      /*
      $image = new Image();
      $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
      $image->setAlt('Job de rêve');
      $advert->setImage($image);
      */
      // On la persiste
      $manager->persist($adv);
    }

    // On déclenche l'enregistrement de toutes les compétences
    $manager->flush();
  }
}
