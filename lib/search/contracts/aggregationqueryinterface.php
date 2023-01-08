<?php

namespace Polus\Elastic\Search\Contracts;

interface AggregationQueryInterface extends CriteriaInterface
{
    public function terms(string $field, string $path): AggregationQueryInterface;
    public function minMax(string $field, string $path): AggregationQueryInterface;
}