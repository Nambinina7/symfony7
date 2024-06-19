<?php

namespace App\Repository;

use App\Entity\Holyday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Holyday>
 *
 * @method Holyday|null find($id, $lockMode = null, $lockVersion = null)
 * @method Holyday|null findOneBy(array $criteria, array $orderBy = null)
 * @method Holyday[]    findAll()
 * @method Holyday[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HolydayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Holyday::class);
    }
}
