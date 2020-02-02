<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserQuestionAnswerDeletedRepository")
 */
class UserQuestionAnswerDeleted
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserToken")`
     * @ORM\JoinColumn(unique=false, nullable=false, referencedColumnName="token")
     */
    private $userToken;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Answer")
     * @ORM\JoinColumn(unique=false, nullable=false)
     */
    private $answer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $answeredAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textAnswer;

    /**
     * UserQuestionAnswerDeleted constructor.
     * @param UserQuestionAnswer $userQuestionAnswer
     */
    public function __construct(UserQuestionAnswer $userQuestionAnswer)
    {
        $this->setUserToken($userQuestionAnswer->getUserToken());
        $this->setAnswer($userQuestionAnswer->getAnswer());
        $this->setAnsweredAt($userQuestionAnswer->getAnsweredAt());
        $this->setTextAnswer($userQuestionAnswer->getTextAnswer());
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return UserToken|null
     */
    public function getUserToken(): ?UserToken
    {
        return $this->userToken;
    }

    /**
     * @param UserToken|null $userToken
     * @return UserQuestionAnswerDeleted
     */
    public function setUserToken(?UserToken $userToken): self
    {
        $this->userToken = $userToken;

        return $this;
    }

    /**
     * @return Answer|null
     */
    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    /**
     * @param Answer|null $answer
     * @return UserQuestionAnswerDeleted
     */
    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getAnsweredAt(): ?DateTimeInterface
    {
        return $this->answeredAt;
    }

    /**
     * @param DateTimeInterface $answeredAt
     * @return UserQuestionAnswerDeleted
     */
    public function setAnsweredAt(DateTimeInterface $answeredAt): self
    {
        $this->answeredAt = $answeredAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTextAnswer(): ?string
    {
        return $this->textAnswer;
    }

    /**
     * @param string|null $textAnswer
     * @return UserQuestionAnswerDeleted
     */
    public function setTextAnswer(?string $textAnswer): self
    {
        $this->textAnswer = $textAnswer;

        return $this;
    }
}
