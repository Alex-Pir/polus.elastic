<?php

namespace Polus\Elastic\Search;

use Polus\Elastic\Search\Contracts\QueryInterface;
use Polus\Elastic\Search\Contracts\SearchInterface;

/**
 * @method SearchInterface where(string $field, string $operator, string|int|null $value = null)
 * @method SearchInterface whereNot(string $field, string|int $value)
 * @method SearchInterface whereIn(string $field, array $value)
 * @method SearchInterface whereNotIn(string $field, array $value)
 */
abstract class QueryBuilder implements SearchInterface
{
    protected Search $searchClient;

    public function __construct(
        protected string $index,
        protected ?QueryInterface $query
    ) {
        if (is_null($this->query)) {
            $this->query = new BoolBuilder();
        }

        $this->searchClient = new Search();
    }

    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    public function __call($name, $arguments) {
        if (!method_exists($this->query, $name)) {
            $this->getQuery()->$name(...$arguments);
        }

        return $this;
    }
}
