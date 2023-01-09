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
        $this->aggregations[$aggregation->getField()] = $aggregation;
    }

    public function toDSL(): array
    {
        $result = [];

        foreach ($this->aggregations as $aggregation) {
            $result = array_merge($result, $aggregation->toDSL());
        }

        return $result;
    }

    public function isEmpty(): bool
    {
        return empty($this->aggregations);
    }

    public function toArray(): array
    {
        return $this->aggregations;
    }
}
