<?php

namespace Polus\Elastic\Search\Aggregations;

use Polus\Elastic\Search\Contracts\AggregationQueryInterface;
use Polus\Elastic\Search\Contracts\QueryInterface;
use Polus\Elastic\Search\QueryBuilder;

/**
 * @method QueryBuilder terms(string $field, string $path)
 * @method QueryBuilder minMax(string $field, string $path)
 */
class AggregationsQuery extends QueryBuilder
{
    public function __construct(
        string $index,
        ?QueryInterface $query,
        protected ?AggregationQueryInterface $aggregationQuery
    ) {
        parent::__construct($index, $query);

        if (is_null($aggregationQuery)) {
            $this->aggregationQuery = new AggregationsBuilder();
        }
    }

    public function search(): array
    {
        $body = [
            'query' => $this->getQuery()->toDSL(),
            'track_total_hits' => false,
            'size' => 0
        ];

        return $this->searchClient->search($this->index, $body);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->aggregationQuery, $name)) {
            $this->aggregationQuery->$name(...$arguments);
            return $this;
        }

        return parent::__call($name, $arguments);
    }
}
