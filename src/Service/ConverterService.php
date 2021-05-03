<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\GbpPrice;

class ConverterService
{
    public function sumGbpPrices(GbpPrice $price1, GbpPrice $price2): string
    {
        $sum = clone $price1;
        $sum->add($price2->getPoundValue(), $price2->getShillingValue(), $price2->getPenceValue());

        return $sum;
    }

    public function subGbpPrices(GbpPrice $price1, GbpPrice $price2): string
    {
        $sub = clone $price1;
        $sub->sub($price2->getPoundValue(), $price2->getShillingValue(), $price2->getPenceValue());

        return $sub;
    }

    public function mulGbpPrices(GbpPrice $price1, GbpPrice $price2): string
    {
        $mul = clone $price1;
        $mul->mul($price2->getPoundValue(), $price2->getShillingValue(), $price2->getPenceValue());

        return $mul;
    }

    public function divGbpPrices(GbpPrice $price1, GbpPrice $price2): string
    {
        $div = clone $price1;
        $div->div($price2->getPoundValue(), $price2->getShillingValue(), $price2->getPenceValue());

        return $div;
    }
}
