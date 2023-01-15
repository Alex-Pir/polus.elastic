<?php

namespace Polus\Elastic\Search;

use Polus\Elastic\Search\Contracts\QueryInterface;
use Polus\Elastic\Search\Contracts\SearchInterface;

/**
 * @method QueryBuilder where(string $field, string $operator, string|int|null $value = null)
 * @method QueryBuilder whereNot(string $field, string|int $value)
 * @method QueryBuilder whereIn(string $field, array $value)
 * @method QueryBuilder whereNotIn(string $field, array $value)
 */
abstract class QueryBuilder implements SearchInterface
{
    public function __construct(
        protected string $index,
        protected ?QueryInterface $query = null,
        protected ?Search $searchClient = null
    ) {
        if (is_null($this->query)) {
            $this->query = new BoolBuilder();
        }

        if (is_null($this->searchClient)) {
            $this->searchClient = new Search();
        }
    }

    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    public function __call($name, $arguments): QueryBuilder {
        if (method_exists($this->query, $name)) {
            $this->getQuery()->$name(...$arguments);
        }

        return $this;
    }
}
