<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"QuestionList"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"QuestionList"})
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question")
     * @Serializer\Groups({"QuestionList"})
     */
    private $answers;

    /**
     * Question constructor.
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    /**
     * @param Answer $answer
     * @return Question
     */
    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }
        return $this;
    }

    /**
     * @param Answer $answer
     * @return Question
     */
    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Survey|null
     */
    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    /**
     * @param Survey|null $survey
     * @return Question
     */
    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Question
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
