<?php

namespace App\Repository;

use App\Entity\UserQuestionAnswerDeleted;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserQuestionAnswerDeleted|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserQuestionAnswerDeleted|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserQuestionAnswerDeleted[]    findAll()
 * @method UserQuestionAnswerDeleted[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserQuestionAnswerDeletedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserQuestionAnswerDeleted::class);
    }
}
