<?php

namespace Polus\Elastic\Search\Aggregations\Criteria;

use Polus\Elastic\Search\Aggregations\AggregationsCollection;
use Polus\Elastic\Search\Aggregations\Results\SearchResult;
use Polus\Elastic\Search\Contracts\AggregationInterface;
use Polus\Elastic\Search\Contracts\QueryInterface;

class AggregationFilter implements AggregationInterface
{
    public function __construct(
        protected string $field,
        protected QueryInterface $value,
        protected AggregationsCollection $children
    ) {
    }

    public function parseResult(array $fields): SearchResult
    {
        // TODO: Implement parseResult() method.
    }

    public function toDSL(): array
    {
        return [
            $this->field => [
                'filter' => $this->value->toDSL(),
                'aggs' => $this->children->toDSL()
            ]
        ];
    }

    public function getField(): string
    {
        return $this->field;
    }
}