<?php

namespace App\Model\UserToken;

use App\Entity\UserQuestionAnswer;
use App\Entity\UserToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SetUserQuestionAnswer
 * @package App\Model\UserQuestionAnswer
 * @Assert\Callback("validateUserTokenReviewRequirements")
 */
class SetUserTokenReview
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserToken
     */
    private $userToken;

    /**
     * @var string
     */
    private $review;

    /**
     * SetUserTokenReview constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserToken $userToken
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ?UserToken $userToken
    )
    {
        $this->em = $entityManager;
        $this->userToken = $userToken;
    }

    /**
     * @return UserToken
     */
    public function getUserToken(): UserToken
    {
        return $this->userToken;
    }

    /**
     * @param UserToken $userToken
     */
    public function setUserToken(UserToken $userToken): void
    {
        $this->userToken = $userToken;
    }

    /**
     * @return string
     */
    public function getReview(): ?string
    {
        return $this->review;
    }

    /**
     * @param string $review
     */
    public function setReview(?string $review): void
    {
        $this->review = $review;
    }

    /**
     * @param ExecutionContextInterface $context
     */
    public function validateUserTokenReviewRequirements(ExecutionContextInterface $context)
    {
        if(!$this->userToken->getFinishedSurveyAt()){
            $context
                ->buildViolation('userTokenReview.this-user-has-not-completed-the-survey')
                ->addViolation();
            return;
        }

        if (!$this->findLastUserQuestionAnswer() || !$this->userToken->getStartedSurveyAt()){
            $context
                ->buildViolation('userTokenReview.survey-is-not-even-started')
                ->addViolation();

            return;
        }
    }

    public function findLastUserQuestionAnswer()
    {
        return $this->em->getRepository(UserQuestionAnswer::class)
            ->findOneBy(
                ['userToken' => $this->userToken],
                ['answeredAt' => 'DESC']
            );
    }
}