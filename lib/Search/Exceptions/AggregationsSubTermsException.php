<?php

namespace Polus\Elastic\Search\Exceptions;

use Exception;

class AggregationsSubTermsException extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct("Ошибка при создании элемента $class");
    }
}
