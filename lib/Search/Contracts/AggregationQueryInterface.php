<?php

namespace Polus\Elastic\Search\Contracts;

use Polus\Elastic\Search\Aggregations\Criteria\AggregationMinMax;
use Polus\Elastic\Search\Aggregations\Criteria\AggregationTerms;

interface AggregationQueryInterface extends CriteriaInterface
{
    public function terms(AggregationTerms $terms): AggregationQueryInterface;
    public function minMax(AggregationMinMax $minMax): AggregationQueryInterface;
    public function getAggregationsQueryValues(): array;
}