<?php

namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class CategoryRepository extends EntityRepository
{
  public function findByName($name)
  {
    $qb = $this->createQueryBuilder('c');

    $qb
      ->where('c.name= :name')
      ->setParameter('name',$name)
    ;

    return $qb
      ->getQuery()
      ->getResult()
    ;
  }
}
