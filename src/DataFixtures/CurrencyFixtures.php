<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CurrencyFixtures extends Fixture
{
    public array $currencies = [
        'GBP' => 'British Pound',
        'GBPs' => 'British Shilling',
        'GBPd' => 'British Pence',
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->currencies as $code => $description) {
            $currency = new Currency($code, $description);
            $manager->persist($currency);
            $this->addReference($code, $currency);
        }

        $manager->flush();
    }
}
