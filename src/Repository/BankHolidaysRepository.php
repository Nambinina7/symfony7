<?php

namespace App\Repository;

use App\Entity\BankHolidays;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BankHolidays>
 */
class BankHolidaysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankHolidays::class);
    }

    public function countBankHolidaysBetweenDates(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): int
    {
        $qb = $this->createQueryBuilder('h')
            ->select('COUNT(h.id)')
            ->where('h.date >= :startDate')
            ->andWhere('h.date <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery();

        return (int) $qb->getSingleScalarResult();
    }
}
