<?php

use Vgpastor\DiscountsCalculator\CalculatedDiscount;
use Vgpastor\DiscountsCalculator\DiscountTypeEnum;

require __DIR__ . '/../vendor/autoload.php';

$result = new CalculatedDiscount(
    DiscountTypeEnum::TOTAL_PERCENTAGE,
    456.78,
    12.3,
    32.1
);

echo "Base ".$result->getBase() . PHP_EOL;
echo "Discount ".$result->getDiscount() . PHP_EOL;
echo "Tax ".$result->getTax() . PHP_EOL;
echo "Subtotal Base ".$result->getBaseSubtotal() . PHP_EOL;
echo "Subtotal Discount ".$result->getDiscountSubtotal() . PHP_EOL;
echo "Subtotal Tax ".$result->getTaxSubtotal() . PHP_EOL;
echo "Total ".$result->getTotal() . PHP_EOL;

