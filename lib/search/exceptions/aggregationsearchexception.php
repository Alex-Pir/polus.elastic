<?php

namespace Polus\Elastic\Search\Exceptions;

use Exception;

class AggregationSearchException extends Exception
{
    public function __construct()
    {
        parent::__construct('Невозможно отправить пустой запрос');
    }
}
