<?php

namespace Polus\Elastic\Search\Aggregations;

use Polus\Elastic\Search\Aggregations\Criteria\AggregationFilter;
use Polus\Elastic\Search\Aggregations\Criteria\AggregationMinMax;
use Polus\Elastic\Search\Aggregations\Criteria\AggregationTerms;
use Polus\Elastic\Search\Contracts\AggregationInterface;
use Polus\Elastic\Search\Contracts\AggregationQueryInterface;
use Polus\Elastic\Search\Contracts\QueryInterface;
use Polus\Elastic\Search\Exceptions\CriteriaException;

class AggregationsBuilder implements AggregationQueryInterface
{
    protected AggregationsCollection $aggregations;

    public function __construct()
    {
        $this->aggregations = new AggregationsCollection();
    }

    /**
     * @throws CriteriaException
     */
    public function terms(string $field, string $path): AggregationQueryInterface
    {
        $this->aggregations->add(new AggregationTerms($field, $path));
        return $this;
    }

    /**
     * @throws CriteriaException
     */
    public function minMax(string $field, string $path): AggregationQueryInterface
    {
        $this->aggregations->add(new AggregationMinMax($field, $path));
        return $this;
    }

    public function filter(string $field, QueryInterface $queryBuilder, AggregationInterface $aggregation): AggregationQueryInterface
    {
        $this->aggregations->add(new AggregationFilter($field, $queryBuilder, $aggregation));
        return $this;
    }

    public function toDSL(): array
    {
        return $this->aggregations->toDSL();
    }

    public function getAggregationsQueryValues(): array
    {
        return $this->aggregations->toArray();
    }
}
