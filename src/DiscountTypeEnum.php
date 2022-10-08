<?php
declare(strict_types=1);


namespace Vgpastor\DiscountsCalculator;


enum DiscountTypeEnum
{
    case BASE_PERCENTAGE;
    case BASE_FIXED;
    case TOTAL_PERCENTAGE;
    case TOTAL_FIXED;
}
