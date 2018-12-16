<?php
// src/OC/PlatformBundle/Purge/OCPurgeAdvert.php

namespace OC\PlatformBundle\PurgeAdvert;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Application;

class OCPurgeAdvert
{
  private $mailer;
  private $locale;
  private $minLength;

  public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
  {
    $this->mailer    = $mailer;
    $this->locale    = $locale;
    $this->minLength = (int) $minLength;
  }

  /**
   * Vérifie si le texte est un spam ou non
   *
   * @param string $text
   * @return bool
   */
  public function isSpam($text)
  {
    return strlen($text) < $this->minLength;
  }
}
