<?php

namespace App\Repository;

use App\Entity\Loan;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Loan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loan[]    findAll()
 * @method Loan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoanRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Loan::class);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findOneByLegacyId(int $id)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.legacyLoanId = :legacyId')
            ->setParameter('legacyId', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param Loan $loan
     * @param Status $status
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateLoanStatus(Loan $loan, Status $status): void
    {
        $loan->setStatus($status);
        $this->_em->persist($loan);
        $this->_em->flush();
    }

    public function getLastLoans(User $user, int $n = 5)
    {
        $queryBuilder = $this->createQueryBuilder('l')
            ->setMaxResults($n)
            ->orderBy('l.id', 'DESC')
        ;

        if (in_array('ROLE_SUPERVISOR', $user->getRoles(), true)) {
            $affiliate = $user->getAffiliate();
            $queryBuilder
                ->innerJoin('l.user', 'user')
                ->where('user.affiliate = :affiliate')
                ->setParameter('affiliate', $affiliate->getId())
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function updateLoan(Loan $loan): void
    {
        $this->_em->persist($loan);
        $this->_em->flush();
    }

    public function getAmountsByAffiliate(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('loan')
            ->innerJoin('loan.user', 'user')
            ->innerJoin('user.affiliate', 'affiliate')
            ->select('affiliate.name, COUNT(loan.id) AS total, SUM(loan.amountRequest) AS amount, SUM(loan.commissionAmount) AS commission')
            ->groupBy('affiliate.name')
        ;

        if (count($filters) > 0) {
            $qb
            ->andWhere('loan.createdAt >= :start_date')->setParameter('start_date', $filters['start_date'])
            ->andWhere('loan.createdAt >= :end_date')->setParameter('end_date', $filters['end_date']);
        }


        return $qb ->getQuery()->getArrayResult();
    }

    // /**
    //  * @return Loan[] Returns an array of Loan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Loan
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
