# DiscountsCalculator
Helper library for calculating discounts in a application.

## Installation
This library are developed to PHP 8.1, use Enum so it's not possible using in previously versions, if you like you can put a PR to make compatible with previous versions..
```composer install vgpastor/discounts-calculator```

## Usage
Yo can see how to use in example.php file.

Required parameter are
- $type: Type of discount. Can be 'percentage' or 'amount' and based in base amount or in total amount (see DiscountTypeEnum)
- $base (float) - Base price
- $discount (float) - Discount percentage or amount

Optional parameters are
- $tax (float) - Tax percentage By default is 0%
- $precision (int) - Precision of the result. By default is 2
- $roundingMode (int) - Rounding mode. By default is PHP_ROUND_HALF_UP
