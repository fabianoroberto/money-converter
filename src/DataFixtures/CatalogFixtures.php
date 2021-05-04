<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Catalog;
use Doctrine\Persistence\ObjectManager;

class CatalogFixtures extends BaseFixtures
{
    protected function loadData(ObjectManager $manager)
    {
        $name = $this->faker->realText(20);

        $this->createMany(Catalog::class, 10, function (Catalog $catalog) {
            $catalog->setName($this->faker->realText(20))
                ->setDescription($this->faker->realText());
        }, [$name]);

        $manager->flush();
    }
}
