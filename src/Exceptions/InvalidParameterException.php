<?php
declare(strict_types=1);


namespace Vgpastor\DiscountsCalculator\Exceptions;


final class InvalidParameterException extends \Exception
{
    private string $parameter;

    public function __construct(string $parameter)
    {
        $this->parameter = $parameter;
        parent::__construct('Invalid parameter: ' . $this->parameter);
    }

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return $this->parameter;
    }

}
