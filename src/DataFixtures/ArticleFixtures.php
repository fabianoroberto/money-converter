<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Catalog;
use App\Entity\GbpPrice;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private FilesystemOperator $storage;

    public function __construct(FilesystemOperator $articlesStorage)
    {
        $this->storage = $articlesStorage;
    }

    public function loadData(ObjectManager $manager)
    {
        $name = $this->faker->realText(50);
        $price = new GbpPrice(
            $this->faker->randomNumber(),
            $this->faker->randomNumber(1, 19),
            $this->faker->randomNumber(1, 11),
        );

        $this->createMany(Article::class, 50, function (Article $article, $count) {
            $image = new UploadedFile($this->faker->image(), (string) $count);
            $imageName = \sprintf('%s.%s', $count, $image->getExtension());

            $this->storage->write(
                $imageName,
                $image->getContent()
            );

            /** @var Catalog $catalog1 */
            $catalog1 = $this->getReference(
                \sprintf('%s_%d', Catalog::class, $this->faker->numberBetween(0, 9))
            );

            /** @var Catalog $catalog2 */
            $catalog2 = $this->getReference(
                \sprintf('%s_%d', Catalog::class, $this->faker->numberBetween(0, 9))
            );

            $price = new GbpPrice(
                $this->faker->randomNumber(),
                $this->faker->randomNumber(1, 19),
                $this->faker->randomNumber(1, 11),
            );

            $article->setName($this->faker->realText(50))
                ->setDescription($this->faker->realText(1000))
                ->setPrice($price)
                ->addCatalog($catalog1)
                ->addCatalog($catalog2)
                ->setPhotoFilename($imageName);
        }, [$name, $price]);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CatalogFixtures::class,
        ];
    }
}
