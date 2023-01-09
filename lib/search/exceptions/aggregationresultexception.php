<?php

namespace Polus\Elastic\Search\Exceptions;

use Exception;

class AggregationResultException extends Exception
{
    public function __construct(string $field)
    {
        parent::__construct("Поле $field не найдено");
    }
}
