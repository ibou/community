<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findCountLoginUsers()
    {
            return $this->createQueryBuilder('u')
            ->andWhere('u.logincount IS NOT NULL')
            ->orderBy('u.logincount', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findUserByEmail(string $email)
    {
        return $this->createQueryBuilder('u')
        ->andWhere('u.email = :val')
        ->setParameter('val', $email)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
}
