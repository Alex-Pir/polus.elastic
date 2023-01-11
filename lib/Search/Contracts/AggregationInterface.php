<?php

namespace Polus\Elastic\Search\Contracts;

use Polus\Elastic\Search\Aggregations\Results\SearchResult;

interface AggregationInterface extends CriteriaInterface
{
    public function parseResult(array $fields): SearchResult;
    public function getField(): string;
}