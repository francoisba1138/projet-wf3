<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Le prénom est obligatoire.")
     * @Assert\Length(max="50",maxMessage="Le prénom ne doit pas faire plus de {{ limit }} caractères.")
     * @ORM\Column(type="string", length=50)
     */
    private $firstname;

    /**
     * @Assert\NotBlank(message="Le nom est obligatoire.")
     * @Assert\Length(max="50",maxMessage="Le nom ne doit pas faire plus de {{ limit }} caractères.")
     * @ORM\Column(type="string", length=50)
     */
    private $lastname;

    /**
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $shopName;


    /**
     *
     * @ORM\Column(type="text", length=500, nullable=true)
     */
    private $presentation;


    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;



    /**
     * @ORM\Column(type="string", length=100)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="L'e-mail est obligatoire.")
     * @Assert\Email(message="L'e-mail n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $role = "ROLE_USER";

    /**
     * Mot de passe en clair pour interagir avec le formulaire d'inscription
     *
     * @var string
     *
     * @Assert\NotBlank(message="Le mot de passe est obligatoire")
     */
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="seller")
     */
    private $sellerAds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="buyer")
     */
    private $buyerAds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     */
    private $comments;






    public function __construct()
    {
        $this->sellerAds = new ArrayCollection();
        $this->buyerAds = new ArrayCollection();
        $this->title = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }




    /**
     * @return mixed
     */
    public function getShopName()
    {
        return $this->shopName;
    }

    /**
     * @param mixed $shopName
     * @return User
     */
    public function setShopName($shopName)
    {
        $this->shopName = $shopName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @param mixed $presentation
     * @return User
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return User
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return User
     */

    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function __toString()
    {
        return $this->firstname . ' ' .$this->lastname;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getSellerAds(): Collection
    {
        return $this->sellerAds;
    }

    public function addSellerAd(Ad $sellerAd): self
    {
        if (!$this->sellerAds->contains($sellerAd)) {
            $this->sellerAds[] = $sellerAd;
            $sellerAd->setSeller($this);
        }

        return $this;
    }

    public function removeSellerAd(Ad $sellerAd): self
    {
        if ($this->sellerAds->contains($sellerAd)) {
            $this->sellerAds->removeElement($sellerAd);
            // set the owning side to null (unless already changed)
            if ($sellerAd->getSeller() === $this) {
                $sellerAd->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getBuyerAds(): Collection
    {
        return $this->buyerAds;
    }

    public function addBuyerAd(Ad $buyerAd): self
    {
        if (!$this->buyerAds->contains($buyerAd)) {
            $this->buyerAds[] = $buyerAd;
            $buyerAd->setBuyer($this);
        }

        return $this;
    }

    public function removeBuyerAd(Ad $buyerAd): self
    {
        if ($this->buyerAds->contains($buyerAd)) {
            $this->buyerAds->removeElement($buyerAd);
            // set the owning side to null (unless already changed)
            if ($buyerAd->getBuyer() === $this) {
                $buyerAd->setBuyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getTitle(): Collection
    {
        return $this->title;
    }

    public function addTitle(Comment $title): self
    {
        if (!$this->title->contains($title)) {
            $this->title[] = $title;
            $title->setAuthor($this);
        }

        return $this;
    }

    public function removeTitle(Comment $title): self
    {
        if ($this->title->contains($title)) {
            $this->title->removeElement($title);
            // set the owning side to null (unless already changed)
            if ($title->getAuthor() === $this) {
                $title->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }
}
