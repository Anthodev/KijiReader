<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FolderRepository")
 */
class Folder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Feed")
     */
    private $feeds;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="folders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->feeds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Feed[]
     */
    public function getFeeds(): Collection
    {
        return $this->feeds;
    }

    public function addFeed(Feed $feed): self
    {
        if (!$this->feeds->contains($feed)) {
            $this->feeds[] = $feed;
        }

        return $this;
    }

    public function removeFeed(Feed $feed): self
    {
        if ($this->feeds->contains($feed)) {
            $this->feeds->removeElement($feed);
        }

        return $this;
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
}
