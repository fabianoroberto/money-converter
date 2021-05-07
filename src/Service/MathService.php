<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\GbpPrice;

class MathService
{
    public function sumGbpPrices(GbpPrice $price1, GbpPrice $price2): string
    {
        $sum = clone $price1;
        $sum->add($price2->getPoundValue(), $price2->getShillingValue(), $price2->getPenceValue());

        return $sum->__toString();
    }

    /**
     * @param GbpPrice $price1
     * @param GbpPrice $price2
     * @return string
     */
    public function subGbpPrices(GbpPrice $price1, GbpPrice $price2): string
    {
        $sub = clone $price1;
        $sub->sub($price2->getPoundValue(), $price2->getShillingValue(), $price2->getPenceValue());

        return $sub->__toString();
    }

    public function mulGbpPrices(GbpPrice $price1, GbpPrice $price2): string
    {
        $mul = clone $price1;
        $mul->mul($price2->getPoundValue(), $price2->getShillingValue(), $price2->getPenceValue());

        return $mul->__toString();
    }

    public function divGbpPrices(GbpPrice $price1, GbpPrice $price2): string
    {
        $div = clone $price1;
        $div->div($price2->getPoundValue(), $price2->getShillingValue(), $price2->getPenceValue());

        return $div->__toString();
    }
}
