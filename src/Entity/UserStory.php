<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserStoryRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class UserStory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userStories")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Feed")
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    private $feed;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Story")
     * @ORM\OrderBy({"date" = "DESC"})
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     */
    private $story;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Serializer\Expose
     */
    private $starred;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Serializer\Expose
     */
    private $readStatus;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFeed(): ?Feed
    {
        return $this->feed;
    }

    public function setFeed(?Feed $feed): self
    {
        $this->feed = $feed;

        return $this;
    }

    public function getStory(): ?Story
    {
        return $this->story;
    }

    public function setStory(?Story $story): self
    {
        $this->story = $story;

        return $this;
    }

    public function getStarred(): ?bool
    {
        return $this->starred;
    }

    public function setStarred(bool $starred): self
    {
        $this->starred = $starred;

        return $this;
    }

    public function getReadStatus(): ?bool
    {
        return $this->readStatus;
    }

    public function setReadStatus(bool $readStatus): self
    {
        $this->readStatus = $readStatus;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
