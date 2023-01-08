<?php

namespace Polus\Elastic\Search\Criteria;

use Polus\Elastic\Search\Contracts\CriteriaInterface;
use Polus\Elastic\Search\Criteria\Traits\HasFieldError;
use Polus\Elastic\Search\Exceptions\CriteriaException;

class Range implements CriteriaInterface
{
    use HasFieldError;

    protected array $operators = [
        '<' => 'lt',
        '<=' => 'lte',
        '>' => 'gt',
        '>=' => 'gte'
    ];

    /**
     * @throws CriteriaException
     */
    public function __construct(
        protected string $field,
        protected string $operator,
        protected string $value
    ) {
        if (!in_array($operator, array_keys($this->operators))) {
            throw new CriteriaException(static::class);
        }

        $this->checkEmpty();
    }

    public function toDSL(): array
    {
        return ['range' => [$this->field => [$this->operators[$this->operator] => $this->value]]];
    }
}
