<?php
declare(strict_types=1);


namespace Vgpastor\DiscountsCalculator\Exceptions;


final class DiscountBiggerThanBase extends \Exception
{
    public function __construct()
    {
        parent::__construct('Discount it\'s bigger than base');
    }
}
