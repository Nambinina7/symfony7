<?php

namespace App\Repository;

use App\Entity\BannerItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BannerItems>
 *
 * @method BannerItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method BannerItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method BannerItems[]    findAll()
 * @method BannerItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannerItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BannerItems::class);
    }
}
