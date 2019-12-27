<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingsRepository")
 */
class Settings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $viewFlux;

    /**
     * @ORM\Column(type="integer")
     */
    private $viewArticle;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderArticle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $displayUnread;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="settings", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getViewFlux(): ?int
    {
        return $this->viewFlux;
    }

    public function setViewFlux(int $viewFlux): self
    {
        $this->viewFlux = $viewFlux;

        return $this;
    }

    public function getViewArticle(): ?int
    {
        return $this->viewArticle;
    }

    public function setViewArticle(int $viewArticle): self
    {
        $this->viewArticle = $viewArticle;

        return $this;
    }

    public function getOrderArticle(): ?int
    {
        return $this->orderArticle;
    }

    public function setOrderArticle(int $orderArticle): self
    {
        $this->orderArticle = $orderArticle;

        return $this;
    }

    public function getDisplayUnread(): ?bool
    {
        return $this->displayUnread;
    }

    public function setDisplayUnread(bool $displayUnread): self
    {
        $this->displayUnread = $displayUnread;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
