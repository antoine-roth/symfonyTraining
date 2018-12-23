<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Skill;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{

    public function indexAction($page)
  {
    if ($page < 1) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }
    // Ici je fixe le nombre d'annonces par page à 3
    // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')

    
    $nbPerPage = 3;

    // On récupère notre objet Paginator
    $listAdverts = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
      ->getAdverts($page, $nbPerPage)
    ;

    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listAdverts) / $nbPerPage);

    // Si la page n'existe pas, on retourne une 404
    if ($page > $nbPages) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

    // On donne toutes les informations nécessaires à la vue
    return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts,
      'nbPages'     => $nbPages,
      'page'        => $page,
    ));
  }
  public function viewAction($id)
  {
    $em = $this->getDoctrine()->getManager();

    // Pour récupérer une seule annonce, on utilise la méthode find($id)
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
    // ou null si l'id $id n'existe pas, d'où ce if :
    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // Récupération de la liste des candidatures de l'annonce
    $listApplications = $em
      ->getRepository('OCPlatformBundle:Application')
      ->findBy(array('advert' => $advert))
    ;

    // Récupération des AdvertSkill de l'annonce
    $listAdvertSkills = $em
      ->getRepository('OCPlatformBundle:AdvertSkill')
      ->findBy(array('advert' => $advert))
    ;

    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
      'advert'           => $advert,
      'listApplications' => $listApplications,
      'listAdvertSkills' => $listAdvertSkills,
    ));
  }

  public function addAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    // On ne sait toujours pas gérer le formulaire, patience cela vient dans la prochaine partie !

    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
      return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
    }

    return $this->render('OCPlatformBundle:Advert:add.html.twig');
  }

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // Ici encore, il faudra mettre la gestion du formulaire
    
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirectToRoute('oc_platform_view', array('id' => $advert.getId()));
    }

    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert
    ));
  }

  public function deleteAction($id)
  {
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // On boucle sur les catégories de l'annonce pour les supprimer
    foreach ($advert->getCategories() as $category) {
      $advert->removeCategory($category);
    }

    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

    // On déclenche la modification
    $em->flush();
    
    return $this->render('OCPlatformBundle:Advert:delete.html.twig');
  }

  public function menuAction($limit)
  {
    // On fixe en dur une liste ici, bien entendu par la suite on la récupérera depuis la BDD !
    $em = $this->getDoctrine()->getManager();

    $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->findBy(
      array(),                 // Pas de critère
      array('date' => 'desc'), // On trie par date décroissante
      $limit,                  // On sélectionne $limit annonces
      0                        // À partir du premier
    );

    return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe les variables nécessaires au template !
      'listAdverts' => $listAdverts
    ));
  }


  public function testAction(){

    $em=$this->getDoctrine()->getManager();

    $adverts = $em->getRepository('OCPlatformBundle:Advert')->findAll();
    $categories = $em->getRepository('OCPlatformBundle:Category')->findAll();
    $skills = $em->getRepository('OCPlatformBundle:Skill')->findAll();
    $levels = array("noob","beginner","pro","expert","ultimate boss");

    foreach ($adverts as $advert) {
      
      $image = new Image();
      $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
      $image->setAlt('Job de rêve');
      $advert->setImage($image);
      
      
      foreach ($skills as $skill){

        $advertSkill = new AdvertSkill();
        $advertSkill->setSkill($skill);
        $advertSkill->setAdvert($advert);
        $advertSkill->setLevel($levels[rand(0,4)]);

        $em->persist($advertSkill);
      }
      
    
      $advert->addCategory($categories[rand(0,5)]);
      if(rand(0,1)){
        $application = new Application();
        $application->setAuthor('zboob');
        $application->setContent('je veux postuler pour le poste proposé dans l\'annonce : ');
        $application->setAdvert($advert);
        $em->persist($application);
        
        $advert->addApplication($application);
      }
    }




    $em->flush();


    $test=$em->getRepository('OCPlatformBundle:Advert')->myFindAllAbove(25);
    return $this->render('OCPlatformBundle:Advert:test.html.twig',array('test' => $test));

  }


  public function purgeAction($days){
    
    $em=$this->getDoctrine()->getManager();
    $purge = $this->container->get('oc_platform.purger.advert');    
    
    $listePurge = $purge->purge($days);

    return $this->render('OCPlatformBundle:Advert:purge.html.twig', array(
      'listePurge' => $listePurge
    ));
  }

}
