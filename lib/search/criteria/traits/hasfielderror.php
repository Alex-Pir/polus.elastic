<?php

namespace Polus\Elastic\Search\Criteria\Traits;

use Polus\Elastic\Search\Exceptions\CriteriaException;

trait HasFieldError
{
    /**
     * @throws CriteriaException
     */
    public function checkEmpty(): bool
    {
        if (!trim($this->field) || is_string($this->value) ? !trim($this->value) : !$this->value) {
            throw new CriteriaException(static::class);
        }

        return true;
    }
}