<?php

namespace App\Repository;

use App\Entity\CsrfToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CsrfToken>
 *
 * @method CsrfToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsrfToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsrfToken[]    findAll()
 * @method CsrfToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsrfTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsrfToken::class);
    }

}
