<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"QuestionList"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"QuestionList"})
     */
    private $text;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"QuestionList"})
     */
    private $type;

    /**
     * @var Question|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Question")
     */
    private $nextQuestion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Groups({"QuestionList"})
     */
    private $additionalText;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Question|null
     */
    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    /**
     * @param Question|null $question
     * @return Answer
     */
    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Answer
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return Answer
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Question|null
     */
    public function getNextQuestion(): ?Question
    {
        return $this->nextQuestion;
    }

    /**
     * @param Question|null $nextQuestion
     * @return Answer
     */
    public function setNextQuestion(?Question $nextQuestion): self
    {
        $this->nextQuestion = $nextQuestion;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdditionalText(): ?string
    {
        return $this->additionalText;
    }

    /**
     * @param string|null $additionalText
     * @return Answer
     */
    public function setAdditionalText(?string $additionalText): self
    {
        $this->additionalText = $additionalText;

        return $this;
    }

    /**
     * @return int|null
     * @Serializer\VirtualProperty()
     * @Serializer\Groups({"QuestionList"})
     * @Serializer\SerializedName("next_question_id")
     * @Serializer\Type("integer")
     */
    public function virtualNextQuestionId()
    {
        return $this->nextQuestion ? $this->nextQuestion->getId() : null;
    }

}
