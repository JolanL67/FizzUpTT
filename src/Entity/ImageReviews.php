<?php

namespace App\Entity;

use App\Repository\ImageReviewsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ImageReviewsRepository::class)
 */
class ImageReviews
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"filter_rating"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"filter_rating"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Review::class, inversedBy="imageReviews", cascade={"persist"})
     */
    private $images;

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

    public function getImages(): ?Review
    {
        return $this->images;
    }

    public function setImages(?Review $images): self
    {
        $this->images = $images;

        return $this;
    }
}
