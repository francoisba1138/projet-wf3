<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 */
class Ad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le prix est obligatoire.")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="L'état est obligatoire.")
     */
    private $cond;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Le titre est obligatoire.")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le contenu est obligatoire.")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sellerAds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $seller;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="buyerAds")
     * @ORM\JoinColumn(nullable=true)
     */
    private $buyer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game", inversedBy="ads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCond(): ?string
    {
        return $this->cond;
    }

    public function setCond(string $cond): self
    {
        $this->cond = $cond;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Ad
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return Ad
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }


    public function __toString()
    {
        return $this->title;
    }

    public function getSeller(): ?User
    {
        return $this->seller;
    }

    public function setSeller(?User $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
