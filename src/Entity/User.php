<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("username")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Settings", mappedBy="user", cascade={"persist", "remove"})
     */
    private $settings;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Feed", mappedBy="users")
     */
    private $feeds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Folder", mappedBy="user", orphanRemoval=true)
     */
    private $folders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserStory", mappedBy="user")
     * @ORM\OrderBy({"story" = "DESC"})
     */
    private $userStories;

    public function __construct()
    {
        $this->feeds = new ArrayCollection();
        $this->folders = new ArrayCollection();
        $this->userStories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        $this->password = null;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles()
    {
        return [$this->getRole()->getCode()];
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

    public function getSettings(): ?Settings
    {
        return $this->settings;
    }

    public function setSettings(Settings $settings): self
    {
        $this->settings = $settings;

        // set the owning side of the relation if necessary
        if ($settings->getUser() !== $this) {
            $settings->setUser($this);
        }

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
            $feed->addUser($this);
        }

        return $this;
    }

    public function removeFeed(Feed $feed): self
    {
        if ($this->feeds->contains($feed)) {
            $this->feeds->removeElement($feed);
            $feed->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Folder[]
     */
    public function getFolders(): Collection
    {
        return $this->folders;
    }

    public function addFolder(Folder $folder): self
    {
        if (!$this->folders->contains($folder)) {
            $this->folders[] = $folder;
            $folder->setUser($this);
        }

        return $this;
    }

    public function removeFolder(Folder $folder): self
    {
        if ($this->folders->contains($folder)) {
            $this->folders->removeElement($folder);
            // set the owning side to null (unless already changed)
            if ($folder->getUser() === $this) {
                $folder->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ]);
    }
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|UserStory[]
     */
    public function getUserStories(): Collection
    {
        return $this->userStories;
    }

    public function addUserStory(UserStory $userStory): self
    {
        if (!$this->userStories->contains($userStory)) {
            $this->userStories[] = $userStory;
            $userStory->setUser($this);
        }

        return $this;
    }

    public function removeUserStory(UserStory $userStory): self
    {
        if ($this->userStories->contains($userStory)) {
            $this->userStories->removeElement($userStory);
            // set the owning side to null (unless already changed)
            if ($userStory->getUser() === $this) {
                $userStory->setUser(null);
            }
        }

        return $this;
    }
}
