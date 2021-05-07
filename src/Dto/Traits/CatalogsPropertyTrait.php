<?php

declare(strict_types=1);

namespace App\Dto\Traits;

use App\Dto\Item\CatalogItem;
use JMS\Serializer\Annotation as JMS;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

trait CatalogsPropertyTrait
{
    /**
     * @JMS\Type("array")
     * @OA\Property(
     *     type="array",
     *     @OA\Items(
     *         ref=@Model(type=CatalogItem::class)
     *     )
     * )
     */
    private array $catalogs;

    public function getCatalogs(): array
    {
        return $this->catalogs;
    }
}
