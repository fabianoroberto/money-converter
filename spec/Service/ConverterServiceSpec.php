<?php

declare(strict_types=1);

namespace spec\App\Service;

use App\Entity\GbpPrice;
use App\Service\ConverterService;
use PhpSpec\ObjectBehavior;
use Webmozart\Assert\InvalidArgumentException;

class ConverterServiceSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ConverterService::class);
    }

    public function it_sum_gbp_prices(): void
    {
        $price1 = new GbpPrice(1, 19, 11);
        $price2 = new GbpPrice(1, 0, 1);

        $result = $this->sumGbpPrices($price1, $price2);
        $result->shouldBeEqualTo('3p 0s 0d');
    }

    public function it_sub_gbp_prices(): void
    {
        $price1 = new GbpPrice(2, 1, 1);
        $price2 = new GbpPrice(1, 19, 11);

        $result = $this->subGbpPrices($price1, $price2);
        $result->shouldBeEqualTo('0p 1s 2d');
    }

    public function it_not_sub_gbp_price_due_subtrahend_less_than_minuend(): void
    {
        $price1 = new GbpPrice(2, 1, 1);
        $price2 = new GbpPrice(1, 19, 11);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('subGbpPrices', [$price2, $price1]);
    }

    public function it_mul_gbp_prices(): void
    {
        $price1 = new GbpPrice(2, 4, 2);
        $price2 = new GbpPrice(1, 5, 6);

        $result = $this->mulGbpPrices($price1, $price2);
        $result->shouldBeEqualTo('675p 15s 0d');
    }

    public function it_div_gbp_prices(): void
    {
        $price1 = new GbpPrice(675, 15, 0);
        $price2 = new GbpPrice(1, 5, 6);

        $result = $this->divGbpPrices($price1, $price2);
        $result->shouldBeEqualTo('2p 4s 2d');
    }

    public function it_not_div_gbp_price_due_dividend_less_than_divisor(): void
    {
        $price1 = new GbpPrice(2, 1, 1);
        $price2 = new GbpPrice(1, 19, 11);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('divGbpPrices', [$price2, $price1]);
    }
}
