<?php

namespace Polus\Elastic\Search\Criteria;

use Polus\Elastic\Search\Contracts\CriteriaInterface;
use Polus\Elastic\Search\Criteria\Traits\HasFieldError;
use Polus\Elastic\Search\Exceptions\CriteriaException;

class Term implements CriteriaInterface
{
    use HasFieldError;

    /**
     * @throws CriteriaException
     */
    public function __construct(protected string $field, protected string $value)
    {
        $this->checkEmpty();
    }

    public function toDSL(): array
    {
        return ['term' => [$this->field => $this->value]];
    }
}