<?php

namespace GaussBundle\Repository;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCollection(){
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.category != :param and u.statusProduct = :param2 order by u.id desc ')
            ->setParameter('param', 5)
            ->setParameter('param2', 2)
            ->setMaxResults(8);
        return $qb->getQuery()->getResult();
    }
    
    public function getProductCateg($name){
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.category = :param ')
            ->setParameter('param', $name);
        return $qb->getQuery()->getResult();
    }
}
