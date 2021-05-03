<?php

declare(strict_types=1);

namespace App\Dto\Traits;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

trait NamePropertyTrait
{
    /**
     * @JMS\Type("string")
     * @Assert\NotNull(message="Name is required.")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=4,
     *     max=255,
     *     minMessage="Name is too short. It should have 4 characters or more.",
     *     maxMessage="Name is too long. It should have 255 characters or less."
     * )
     */
    private string $name;

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
