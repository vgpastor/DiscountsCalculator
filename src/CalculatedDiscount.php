<?php
declare(strict_types=1);


namespace Vgpastor\DiscountsCalculator;

use Vgpastor\DiscountsCalculator\Exceptions\DiscountBiggerThanBase;
use Vgpastor\DiscountsCalculator\Exceptions\InvalidParameterException;

final class CalculatedDiscount
{
    private int $precision;
    private int $roundingMode;

    private float $base;
    private float $discount;
    private float $tax;
    private DiscountTypeEnum $type;

    private float $baseSubtotal;
    private float $discountSubtotal;
    private float $taxSubtotal;

    private float $total;

    /**
     * @param DiscountTypeEnum $type Discount type to apply
     * @param float $base The base amount used to calculate the discount.
     * @param float $discount The discount amount or percentage.
     * @param float $tax Optional tax percentage to apply.
     * @param int $precision Optional precision to use in calculations.
     * @param int $roundingMode Optional rounding mode to use in calculations.
     * @throws InvalidParameterException
     * @throws DiscountBiggerThanBase
     */
    public function __construct(
        DiscountTypeEnum $type,
        float            $base,
        float            $discount,
        float            $tax = 0.0,
        int              $precision = 2,
        int              $roundingMode = PHP_ROUND_HALF_UP
    ) {
        if ($base < 0) {
            throw new Exceptions\InvalidParameterException('base');
        }
        if ($discount < 0) {
            throw new Exceptions\InvalidParameterException('discount');
        }
        if ($tax < 0) {
            throw new Exceptions\InvalidParameterException('tax');
        }
        if ($discount > $base && $type === DiscountTypeEnum::BASE_FIXED) {
            throw new Exceptions\DiscountBiggerThanBase();
        }

        $this->type = $type;
        $this->base = $base;
        $this->discount = $discount;
        $this->tax = $tax;

        $this->precision = $precision;
        $this->roundingMode = $roundingMode;

        $this->calculate();

        if ($this->discountSubtotal > $this->base) {
            throw new Exceptions\DiscountBiggerThanBase();
        }
    }

    private function calculate(): void
    {
        switch ($this->type) {
            case DiscountTypeEnum::BASE_PERCENTAGE:
            case DiscountTypeEnum::BASE_FIXED:
                $this->calculateDiscountInBase();
                break;
            case DiscountTypeEnum::TOTAL_FIXED:
            case DiscountTypeEnum::TOTAL_PERCENTAGE:
                $this->calculateDiscountInTotal();
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

    private function calculateDiscountInTotal(): void
    {
        if ($this->type === DiscountTypeEnum::TOTAL_PERCENTAGE) {
            $pretotal = $this->base * (1 + $this->tax / 100);
            $this->total = round($pretotal - $this->percentageCalculation($pretotal, $this->discount), $this->precision, $this->roundingMode);
        } else {
            $this->total = round($this->base + $this->percentageCalculation($this->base, $this->tax) - $this->discount, $this->precision, $this->roundingMode);
        }
        $this->taxSubtotal = round($this->total * $this->tax / (100 + $this->tax), $this->precision, $this->roundingMode);
        $this->baseSubtotal = round($this->total - $this->taxSubtotal, $this->precision, $this->roundingMode);
        $this->discountSubtotal = round($this->base - $this->baseSubtotal, $this->precision, $this->roundingMode);
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
