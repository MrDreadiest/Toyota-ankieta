<?php

namespace App\Repository;

use App\Entity\UserQuestionAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserQuestionAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserQuestionAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserQuestionAnswer[]    findAll()
 * @method UserQuestionAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserQuestionAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserQuestionAnswer::class);
    }
}
