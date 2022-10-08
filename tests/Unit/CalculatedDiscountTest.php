<?php
declare(strict_types=1);


namespace Vgpastor\DiscountsCalculator\Model;

use PHPUnit\Framework\TestCase;
use Vgpastor\DiscountsCalculator\CalculatedDiscount;
use Vgpastor\DiscountsCalculator\DiscountTypeEnum;

class CalculatedDiscountTest extends TestCase
{
    private float $base = 456.78;
    private float $discount = 12.3;
    private float $tax = 21.0;

    final public function testCalculateWithoutDiscount():void
    {
        $response = new CalculatedDiscount(
            DiscountTypeEnum::BASE_FIXED,
            $this->base,
            0,
            $this->tax
        );
        $this->assertEquals($this->base, $response->getBase());
        $this->assertEquals(0.0, $response->getDiscount());
        $this->assertEquals($this->tax, $response->getTax());
        $this->assertEquals($this->base, $response->getBaseSubtotal());
        $this->assertEquals(0.0, $response->getDiscountSubtotal());
        $this->assertEquals(95.92, $response->getTaxSubtotal());
        $this->assertEquals(552.70, $response->getTotal());

    }

    final public function testCalculateDiscountBaseFixed(): void
    {
        $response = new CalculatedDiscount(
            DiscountTypeEnum::BASE_FIXED,
            $this->base,
            $this->discount,
            $this->tax
        );
        $this->assertEquals($this->base, $response->getBase());
        $this->assertEquals($this->discount, $response->getDiscount());
        $this->assertEquals($this->tax, $response->getTax());
        $this->assertEquals(444.48, $response->getBaseSubtotal());
        $this->assertEquals(12.3, $response->getDiscountSubtotal());
        $this->assertEquals(93.34, $response->getTaxSubtotal());
        $this->assertEquals(537.82, $response->getTotal());
    }

    final public function testCalculateDiscountBasePercentage(): void
    {
        $response = new CalculatedDiscount(
            DiscountTypeEnum::BASE_PERCENTAGE,
            $this->base,
            $this->discount,
            $this->tax
        );
        $this->assertEquals($this->base, $response->getBase());
        $this->assertEquals($this->discount, $response->getDiscount());
        $this->assertEquals($this->tax, $response->getTax());
        $this->assertEquals(400.60, $response->getBaseSubtotal());
        $this->assertEquals(56.18, $response->getDiscountSubtotal());
        $this->assertEquals(84.13, $response->getTaxSubtotal());
        $this->assertEquals(484.73, $response->getTotal());
    }

    final public function testCalculateDiscountTotalFixed(): void
    {
        $response = new CalculatedDiscount(
            DiscountTypeEnum::TOTAL_FIXED,
            $this->base,
            $this->discount,
            $this->tax
        );
        $this->assertEquals($this->base, $response->getBase());
        $this->assertEquals($this->discount, $response->getDiscount());
        $this->assertEquals($this->tax, $response->getTax());
        $this->assertEquals(540.40, $response->getTotal());
        $this->assertEquals(93.79, $response->getTaxSubtotal());
        $this->assertEquals(446.61, $response->getBaseSubtotal());
        $this->assertEquals(10.17, $response->getDiscountSubtotal());
    }

    final public function testCalculateDiscountTotalPercentage(): void
    {
        $response = new CalculatedDiscount(
            DiscountTypeEnum::TOTAL_PERCENTAGE,
            $this->base,
            $this->discount,
            $this->tax
        );
        $this->assertEquals($this->base, $response->getBase());
        $this->assertEquals($this->discount, $response->getDiscount());
        $this->assertEquals($this->tax, $response->getTax());
        $this->assertEquals(484.72, $response->getTotal());
        $this->assertEquals(84.12, $response->getTaxSubtotal());
        $this->assertEquals(400.60, $response->getBaseSubtotal());
        $this->assertEquals(56.18, $response->getDiscountSubtotal());
    }

}
