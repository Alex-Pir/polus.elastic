<?php

namespace Polus\Elastic\Search\Aggregations;

use Polus\Elastic\Search\Aggregations\Criteria\AggregationMinMax;
use Polus\Elastic\Search\Aggregations\Criteria\AggregationTerms;
use Polus\Elastic\Search\Contracts\AggregationQueryInterface;

class AggregationsBuilder implements AggregationQueryInterface
{
    protected AggregationsCollection $aggregations;

    public function __construct()
    {
        $this->aggregations = new AggregationsCollection();
    }

    public function terms(AggregationTerms $terms): AggregationQueryInterface
    {
        $this->aggregations->add($terms);
        return $this;
    }

    public function minMax(AggregationMinMax $minMax): AggregationQueryInterface
    {
        $this->aggregations->add($minMax);
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
