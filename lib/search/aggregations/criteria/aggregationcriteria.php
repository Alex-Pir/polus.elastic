<?php

namespace Polus\Elastic\Search\Aggregations\Criteria;

use Polus\Elastic\Search\Contracts\AggregationInterface;
use Polus\Elastic\Search\Criteria\Traits\HasFieldError;
use Polus\Elastic\Search\Exceptions\CriteriaException;

abstract class AggregationCriteria implements AggregationInterface
{
    use HasFieldError;

    /**
     * @throws CriteriaException
     */
    public function __construct(protected string $field, protected string $value)
    {
        $this->checkEmpty();
    }

    public function getField(): string
    {
        return $this->field;
    }
}
