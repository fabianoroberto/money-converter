<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Conversion;
use App\Entity\Currency;
use App\Entity\Price;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConversionFixtures extends Fixture implements DependentFixtureInterface
{
    public array $conversions = [
        [
            'source' => [1, 'GBP'],
            'destination' => [20, 'GBPs'],
        ],
        [
            'source' => [1, 'GBPs'],
            'destination' => [12, 'GBPd'],
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->conversions as $conversion) {
            [$sourceValue, $sourceCode] = $conversion['source'];
            [$destinationValue, $destinationCode] = $conversion['destination'];

            /** @var Currency $sourceCurrency */
            $sourceCurrency = $this->getReference($sourceCode);
            $sourcePrice = new Price((float) $sourceValue, $sourceCurrency);

            /** @var Currency $destinationCurrency */
            $destinationCurrency = $this->getReference($destinationCode);
            $destinationPrice = new Price((float) $destinationValue, $destinationCurrency);

            $conversion = new Conversion($sourcePrice, $destinationPrice);
            $manager->persist($conversion);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CurrencyFixtures::class,
        ];
    }
}
