<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserQuestionAnswerRepository")
 */
class UserQuestionAnswer
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\UserToken")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="token")
     */
    private $userToken;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Answer")
     * @ORM\JoinColumn(nullable=false)
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
     * UserQuestionAnswer constructor.
     * @param $userToken
     * @param $answer
     * @param $answeredAt
     * @param $textAnswer
     */
    public function __construct($userToken, $answer, $answeredAt, $textAnswer)
    {
        $this->userToken = $userToken;
        $this->answer = $answer;
        $this->answeredAt = $answeredAt;
        $this->textAnswer = $textAnswer;
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
     * @return UserQuestionAnswer
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
     * @return UserQuestionAnswer
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
     * @return UserQuestionAnswer
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
     * @return UserQuestionAnswer
     */
    public function setTextAnswer(?string $textAnswer): self
    {
        $this->textAnswer = $textAnswer;

        return $this;
    }
}
