<?php

namespace App\Repository;

use App\Entity\DealerAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DealerAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method DealerAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method DealerAddress[]    findAll()
 * @method DealerAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DealerAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DealerAddress::class);
    }
}
