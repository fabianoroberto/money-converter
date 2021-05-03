<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ConversionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ConversionRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Conversion
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Embedded(class="App\Entity\Price")
     */
    private ?Price $sourcePrice;

    /**
     * @ORM\Embedded(class="App\Entity\Price")
     */
    private ?Price $destinationPrice;

    public function __construct(Price $sourcePrice, Price $destinationPrice)
    {
        $this->sourcePrice = $sourcePrice;
        $this->destinationPrice = $destinationPrice;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourcePrice(): ?Price
    {
        return $this->sourcePrice;
    }

    public function getDestinationPrice(): ?Price
    {
        return $this->destinationPrice;
    }
}
