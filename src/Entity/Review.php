<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="smallint")
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=ImageReviews::class, mappedBy="images", cascade={"persist"})
     */
    private $imageReviews;

    public function __construct()
    {
        $this->imageReviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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

    /**
     * @return Collection|ImageReviews[]
     */
    public function getImageReviews(): Collection
    {
        return $this->imageReviews;
    }

    public function addImageReview(ImageReviews $imageReview): self
    {
        if (!$this->imageReviews->contains($imageReview)) {
            $this->imageReviews[] = $imageReview;
            $imageReview->setImages($this);
        }

        return $this;
    }

    public function removeImageReview(ImageReviews $imageReview): self
    {
        if ($this->imageReviews->contains($imageReview)) {
            $this->imageReviews->removeElement($imageReview);
            // set the owning side to null (unless already changed)
            if ($imageReview->getImages() === $this) {
                $imageReview->setImages(null);
            }
        }

        return $this;
    }
}
