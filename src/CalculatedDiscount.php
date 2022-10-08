<?php
declare(strict_types=1);


namespace Vgpastor\DiscountsCalculator;

final class CalculatedDiscount
{
    private int $precision = 2;
    private int $roundingMode = PHP_ROUND_HALF_UP;

    private float $base;
    private float $discount;
    private float $tax;
    private DiscountTypeEnum $type;

    private float $baseSubtotal;
    private float $discountSubtotal;
    private float $taxSubtotal;

    private float $total;

    public function __construct(
        DiscountTypeEnum $type,
        float $base,
        float $discount,
        float $tax = 0.0,
        int $precision = 2,
        int $roundingMode = PHP_ROUND_HALF_UP
    ) {
        $this->type = $type;
        $this->base = $base;
        $this->discount = $discount;
        $this->tax = $tax;

        $this->precision = $precision;
        $this->roundingMode = $roundingMode;

        $this->calculate();
    }

    private function calculate(): void
    {
        switch ($this->type) {
            case DiscountTypeEnum::BASE_PERCENTAGE:
            case DiscountTypeEnum::BASE_FIXED:
                $this->calculateDiscountInBase();
                break;
            case DiscountTypeEnum::TOTAL_FIXED:
                $this->total = round($this->base+$this->percentageCalculation($this->base, $this->tax) - $this->discount, 2);
                $this->taxSubtotal = round($this->total * $this->tax / (100 + $this->tax), 2);
                $this->baseSubtotal = round($this->total - $this->taxSubtotal, 2);
                $this->discountSubtotal = round($this->base - $this->baseSubtotal, 2);
                break;
            case DiscountTypeEnum::TOTAL_PERCENTAGE:
                $pretotal = $this->base*(1+$this->tax/100);
                $this->total = round($pretotal - $this->percentageCalculation($pretotal, $this->discount), 2);
                $this->taxSubtotal = round($this->total * $this->tax / (100 + $this->tax), 2);
                $this->baseSubtotal = round($this->total - $this->taxSubtotal, 2);
                $this->discountSubtotal = round($this->base - $this->baseSubtotal, 2);
                break;
        }
    }

    private function calculateDiscountInBase():void
    {
        if ($this->type === DiscountTypeEnum::BASE_PERCENTAGE) {
            $this->baseSubtotal = round($this->base - ($this->base * $this->discount / 100), $this->precision, $this->roundingMode);
        } else {
            $this->baseSubtotal = round($this->base - $this->discount, $this->precision, $this->roundingMode);
        }
        $this->discountSubtotal = round($this->base - $this->baseSubtotal, $this->precision, $this->roundingMode);
        $this->taxSubtotal = round($this->percentageCalculation($this->baseSubtotal, $this->tax), $this->precision, $this->roundingMode);
        $this->total = round($this->baseSubtotal + $this->taxSubtotal, $this->precision, $this->roundingMode);
    }

    private function percentageCalculation(float $value, float $percentage): float
    {
        return $value * $percentage / 100;
    }

    /**
     * @return float
     */
    public function getBase(): float
    {
        return $this->base;
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * @return float
     */
    public function getTax(): float
    {
        return $this->tax;
    }

    /**
     * @return DiscountTypeEnum
     */
    public function getType(): DiscountTypeEnum
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getBaseSubtotal(): float
    {
        return $this->baseSubtotal;
    }

    /**
     * @return float
     */
    public function getDiscountSubtotal(): float
    {
        return $this->discountSubtotal;
    }

    /**
     * @return float
     */
    public function getTaxSubtotal(): float
    {
        return $this->taxSubtotal;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }
}
