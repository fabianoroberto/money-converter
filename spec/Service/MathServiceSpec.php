<?php

declare(strict_types=1);

namespace spec\App\Service;

use App\Entity\GbpPrice;
use App\Service\MathService;
use PhpSpec\ObjectBehavior;
use Webmozart\Assert\InvalidArgumentException;

class MathServiceSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(MathService::class);
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

    public function it_div_gbp_prices2(): void
    {
        $price1 = new GbpPrice(18, 16, 1);
        $price2 = new GbpPrice(0, 0, 15);

        $result = $this->divGbpPrices($price1, $price2);
        $result->shouldBeEqualTo('1p 5s 0d (1s 1d)');
    }

    public function it_litmus_test(): void
    {
        $price1 = new GbpPrice(18, 16, 3); // 4515
        $price2 = new GbpPrice(0, 1, 3);

        $result = $this->divGbpPrices($price1, $price2);
        $result->shouldBeEqualTo('1p 5s 1d');

        $price3 = new GbpPrice(1, 5, 1);

        $result2 = $this->mulGbpPrices($price2, $price3);
        $result2->shouldBeEqualTo('18p 16s 3d');

        $price4 = new GbpPrice(18, 16, 3);
        $price5 = new GbpPrice(0, 0, 9);

        $result3 = $this->sumGbpPrices($price4, $price5);
        $result3->shouldBeEqualTo('18p 17s 0d');
    }

    public function it_litmus_test2(): void
    {
        $price1 = new GbpPrice(1, 5, 0); // 300
        $price2 = new GbpPrice(0, 1, 3); // 15

        $result = $this->mulGbpPrices($price1, $price2);
        $result->shouldBeEqualTo('18p 15s 0d');

        $price3 = new GbpPrice(18, 15, 0); // 4500
        $price4 = new GbpPrice(0, 2, 0); // 24

        $result2 = $this->sumGbpPrices($price3, $price4);
        $result2->shouldBeEqualTo('18p 17s 0d');
    }
}
