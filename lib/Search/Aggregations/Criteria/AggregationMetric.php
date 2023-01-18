<?php

namespace Polus\Elastic\Search\Aggregations\Criteria;

use Polus\Elastic\Search\Criteria\Traits\HasFieldError;
use Polus\Elastic\Search\Exceptions\AggregationsSubTermsException;
use Polus\Elastic\Search\Exceptions\CriteriaException;

class AggregationMetric implements AggregationSubTermsInterface
{
    use HasFieldError;

    protected array $operations = [
        'min',
        'max',
        'count',
        'avg'
    ];

    /**
     * @throws CriteriaException
     * @throws AggregationsSubTermsException
     */
    public function __construct(protected string $field, protected string $value, protected string $operation)
    {
        $this->checkEmpty();

        if (!in_array($this->operation, $this->operations)) {
            throw new AggregationsSubTermsException(static::class);
        }
    }

    public function toDSL(): array
    {
        return [$this->field => [$this->operation => ['field' => $this->value]]];
    }
}
