<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\SoftDeleteable;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Timestampable;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Article implements SoftDeleteable, Timestampable
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private ?Uuid $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Embedded(class="App\Entity\GbpPrice")
     */
    private ?GbpPrice $price;

    /**
     * @ORM\Column(length=10, unique=true)
     */
    private string $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $photoFilename = null;

    /**
     * @ORM\ManyToMany(targetEntity=Catalog::class, mappedBy="articles")
     */
    private Collection $catalogs;

    public function __construct(string $name, GbpPrice $price)
    {
        $this->name = $name;
        $this->price = $price;
        $this->generateCode();
        $this->catalogs = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->id?->__toString();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhotoFilename(): ?string
    {
        return $this->photoFilename;
    }

    public function setPhotoFilename(?string $photoFilename): self
    {
        $this->photoFilename = $photoFilename;

        return $this;
    }

    /**
     * @return Catalog[]|Collection
     */
    public function getCatalogs(): array | Collection
    {
        return $this->catalogs;
    }

    public function addCatalog(Catalog $catalog): self
    {
        if (!$this->catalogs->contains($catalog)) {
            $this->catalogs[] = $catalog;
            $catalog->addArticle($this);
        }

        return $this;
    }

    public function removeCatalog(Catalog $catalog): self
    {
        if ($this->catalogs->removeElement($catalog)) {
            $catalog->removeArticle($this);
        }

        return $this;
    }

    public function getPrice(): ?GbpPrice
    {
        return $this->price;
    }

    public function setPrice(?GbpPrice $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    private function generateCode(): void
    {
        $this->code = \sprintf(
            'A%d%s',
            \date('y'),
            \mb_substr(\str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 7)
        );
    }
}
