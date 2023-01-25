<?php

namespace Polus\Elastic\Search\Aggregations;

use Polus\Elastic\Search\Contracts\AggregationInterface;
use Polus\Elastic\Search\Contracts\AggregationQueryInterface;
use Polus\Elastic\Search\Contracts\QueryInterface;
use Polus\Elastic\Search\ElasticClient;
use Polus\Elastic\Search\Exceptions\AggregationSearchException;
use Polus\Elastic\Search\QueryBuilder;

/**
 * @method AggregationsQuery where(string $field, string $operator, string|int|null $value = null)
 * @method AggregationsQuery whereNot(string $field, string|int $value)
 * @method AggregationsQuery whereIn(string $field, array $value)
 * @method AggregationsQuery whereNotIn(string $field, array $value)
 * @method AggregationsQuery terms(string $field, string $path)
 * @method AggregationsQuery minMax(string $field, string $path)
 */
class AggregationsQuery extends QueryBuilder
{
    public function __construct(
        string $index,
        ?QueryInterface $query = null,
        protected ?AggregationQueryInterface $aggregationQuery = null,
        protected ?ElasticClient $searchClient = null
    ) {
        parent::__construct($index, $query);

        if (is_null($aggregationQuery)) {
            $this->aggregationQuery = new AggregationsBuilder();
        }
    }

    /**
     * @throws AggregationSearchException
     */
    public function search(): array
    {
        $aggregations = $this->aggregationQuery->toDSL();

        if (!$aggregations) {
            throw new AggregationSearchException();
        }

        $body = [
            'query' => $this->getQuery()->toDSL(),
            'aggs' => $aggregations,
            'track_total_hits' => false,
            'size' => 0
        ];

        return $this->parseResult($this->searchClient->search($this->index, $body));
    }

    public function __call($name, $arguments): AggregationsQuery
    {
        if (method_exists($this->aggregationQuery, $name)) {
            $this->aggregationQuery->$name(...$arguments);
            return $this;
        }

        return parent::__call($name, $arguments);
    }

    protected function parseResult(array $searchResult)
    {
        $aggregationsResult = $searchResult['aggregations'] ?? [];
        $aggregationQueryValues = $this->aggregationQuery->getAggregationsQueryValues();

        return array_map(
            fn(AggregationInterface $aggregationQueryValue) => $aggregationQueryValue->parseResult($aggregationsResult),
            $aggregationQueryValues
        );
    }
}
