<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserTokenRepository")
 */
class UserToken implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=10, name="token")
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey")
     */
    private $survey;

    /**
     * @ORM\Column(type="text")
     */
    private $firstName;

    /**
     * @ORM\Column(type="text")
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startedSurveyAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finishedSurveyAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $review;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $skippedReview;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return UserToken
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return UserToken
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
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
     * @return UserToken
     */
    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->getFinishedSurveyAt() ? array('ROLE_EXPIRED') : array('ROLE_OPEN');
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStartedSurveyAt(): ?DateTimeInterface
    {
        return $this->startedSurveyAt;
    }

    /**
     * @param DateTimeInterface $startedSurveyAt
     */
    public function setStartedSurveyAt(DateTimeInterface $startedSurveyAt)
    {
        $this->startedSurveyAt = $startedSurveyAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getFinishedSurveyAt(): ?DateTimeInterface
    {
        return $this->finishedSurveyAt;
    }


    /**
     * @param DateTimeInterface $finishedSurveyAt
     * @return $this
     */
    public function setFinishedSurveyAt(DateTimeInterface $finishedSurveyAt): self
    {
        $this->finishedSurveyAt = $finishedSurveyAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReview(): ?string
    {
        return $this->review;
    }

    /**
     * @param string $review
     * @return $this
     */
    public function setReview(string $review): self
    {
        $this->review = $review;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->getToken();
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return UserToken
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getSkippedReview(): ?bool
    {
        return $this->skippedReview;
    }

    public function setSkippedReview(?bool $skippedReview): self
    {
        $this->skippedReview = $skippedReview;

        return $this;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

}
