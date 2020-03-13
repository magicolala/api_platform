<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={
 *          "get"={},
 *          "put"
 *     },
 *     attributes={
 *         "pagination_items_per_page"=10,
 *         "formats"={"jsonld", "json", "html", "jsonhal", "csv"={"text/csv"}}
 *     },
 *     shortName="cheeses",
 * )
 * @ApiFilter(SearchFilter::class, properties={"title": "partial"})
 * @ApiFilter(RangeFilter::class, properties={"price"})
 * @ORM\Entity(repositoryClass="App\Repository\CheeseListingRepository")
 */
class CheeseListing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     max=50,
     *     maxMessage="Describe your cheese in 50 chars or less"
     * )
     */
    private $title;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $price;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = false;
    
    public function __construct(string $title)
    {
        $this->title = $title;
        $this->createdAt = new \DateTimeImmutable();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }

//    public function setTitle(string $title): self
//    {
//        $this->title = $title;
//
//        return $this;
//    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * @return string
     * @Groups("cheese_listing:read")
     */
    public function getShortDescription(): string
    {
        if (strlen($this->description) < 40) {
            return $this->description;
        }
        return substr($this->description, 0, 40) . '...';
    }
    
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * @param string $description
     *
     * @return $this
     * @SerializedName("description")
     */
    public function setTextDescription(string $description): self
    {
        $this->description = nl2br($description);
        
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
    
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    
    /**
     * @return string
     * @Groups("cheese_listing:read")
     */
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }
    
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }
    
    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;
        
        return $this;
    }
}
