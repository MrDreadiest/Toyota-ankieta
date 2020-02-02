<?php


namespace App\Model\UserQuestionAnswer;

use App\Entity\Answer;
use App\Entity\Dealer;
use App\Entity\Question;
use App\Entity\UserQuestionAnswer;
use App\Entity\UserToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class SetUserQuestionAnswer
 * @package App\Model\UserQuestionAnswer
 * @Assert\Callback("validateUserQuestionAnswerRequirements")
 */
class SetUserQuestionAnswer
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
     * @var Answer
     * @Assert\NotBlank()
     */
    private $answer;

    /**
     * @var string
     */
    private $textAnswer;

    /**
     * @var Dealer
     */
    private $dealer;

    /**
     * SetUserQuestionAnswer constructor.
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
    public function getTextAnswer(): ?string
    {
        return $this->textAnswer;
    }

    /**
     * @param string $textAnswer
     */
    public function setTextAnswer(?string $textAnswer): void
    {
        $this->textAnswer = $textAnswer;
    }

    /**
     * @return Answer
     */
    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    /**
     * @param Answer|null $answer
     */
    public function setAnswer(?Answer $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @return Dealer
     */
    public function getDealer(): ?Dealer
    {
        return $this->dealer;
    }

    /**
     * @param Dealer|null $dealer
     */
    public function setDealer(?Dealer $dealer): void
    {
        $this->dealer = $dealer;
    }

    /**
     * @param ExecutionContextInterface $context
     */
    public function validateUserQuestionAnswerRequirements(ExecutionContextInterface $context)
    {

        if(!$this->userToken->getStartedSurveyAt()){
            $context
                ->buildViolation('userQuestionAnswer.survey-is-not-started')
                ->addViolation();

            return;
        }

        if (
            $this->findLastUserQuestionAnswer() &&
            $this->answer->getQuestion() !== $this->findLastUserQuestionAnswer()->getAnswer()->getNextQuestion() &&
            $this->answer->getQuestion() !== $this->findFirstQuestion()
        ) {
            $context
                ->buildViolation('userQuestionAnswer.incorrect-answers-order')
                ->addViolation();

            return;
        }

        if ($this->getDealer() && $this->getTextAnswer()){
            $context
                ->buildViolation('userQuestionAnswer.too-many-parameters-were-sent-text-and-id-cannot-exist-together')
                ->addViolation();

            return;
        }

        if ($this->getTextAnswer() && $this->getAnswer()->getType() === 1) {
            $context
                ->buildViolation('userQuestionAnswer.additional-text-had-given-when-a-single-choice-answer-was-expected')
                ->addViolation();

            return;
        }

        if ($this->getDealer() && $this->getAnswer()->getType() !== 3){
            $context
                ->buildViolation('userQuestionAnswer.dealer-had-given-when-another-answer-type-was-expected')
                ->addViolation();

            return;
        }

        if (!$this->getDealer() && $this->getAnswer()->getType() === 3){
            $context
                ->buildViolation('userQuestionAnswer.no_dealer-found-this-answer-is-mandatory')
                ->addViolation();

            return;
        }

        if ($this->getDealer()){
            $this->setTextAnswer($this->getDealer()->getId());
        }
    }

    /**
     * @return UserQuestionAnswer|object|null
     */
    public function findLastUserQuestionAnswer()
    {
        return $this->em->getRepository(UserQuestionAnswer::class)
            ->findOneBy(
                ['userToken' => $this->userToken],
                ['answeredAt' => 'DESC']
            );
    }

    /**
     * @return Question|object|null
     */
    public function findFirstQuestion()
    {
        return $this->em->getRepository(Question::class)
            ->findOneBy(
                ['survey' => $this->userToken->getSurvey()],
                ['id' => 'ASC']
            );
    }
}