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

    public function getLastUsers(User $user, int $n = 5)
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->setMaxResults($n)
            ->orderBy('u.id', 'DESC')
            ;

        if (in_array('ROLE_SUPERVISOR', $user->getRoles(), true)) {
            $affiliate = $user->getAffiliate();
            $queryBuilder
                ->where('u.affiliate = :affiliate')
                ->setParameter('affiliate', $affiliate->getId())
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneByToken(string $token): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.validationToken = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
