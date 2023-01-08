<?php

namespace Polus\Elastic\Search\Aggregations;

use Polus\Elastic\Search\Contracts\AggregationInterface;
use Polus\Elastic\Search\Contracts\CriteriaInterface;

class AggregationsCollection implements CriteriaInterface
{
    /** @var AggregationInterface[] */
    protected array $aggregations = [];

    public function add(AggregationInterface $aggregation): void
    {
        $this->aggregations[] = $aggregation;
    }

    public function toDSL(): array
    {
        return array_map(fn(AggregationInterface $aggregation) => $aggregation->toDSL(), $this->aggregations);
    }

    public function isEmpty(): bool
    {
        return empty($this->aggregations);
    }
}
