<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    // Méthodes de recherche personnalisée peuvent être ajoutées ici,
    // par exemple :
    // /**
    //  * @return Offre[] Returns an array of Offre objects
    //  */
    // public function findBySomeField($value): array
    // {
    //     return $this->createQueryBuilder('o')
    //         ->andWhere('o.someField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('o.id', 'ASC')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
}
