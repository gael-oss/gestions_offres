<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // Vous pouvez ajouter ici des méthodes de recherche personnalisées, par exemple :
    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    // public function findByRole(string $role): array
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')
    //         ->setParameter('role', json_encode($role))
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
}
