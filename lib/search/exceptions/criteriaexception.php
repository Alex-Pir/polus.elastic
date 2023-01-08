<?php

namespace Polus\Elastic\Search\Exceptions;

use Exception;
use Throwable;

class CriteriaException extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct("Поле недоступно для $class", 0, null);
    }
}