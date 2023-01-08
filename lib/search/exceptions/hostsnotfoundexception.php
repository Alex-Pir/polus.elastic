<?php

namespace Polus\Elastic\Search\Exceptions;

use Exception;

class HostsNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('В настройках модуля не заданы хосты');
    }
}
