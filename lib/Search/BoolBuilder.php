<?php

namespace Polus\Elastic\Search;

use Polus\Elastic\Search\Contracts\CriteriaInterface;
use Polus\Elastic\Search\Contracts\QueryInterface;
use Polus\Elastic\Search\Criteria\CriteriaCollection;
use Polus\Elastic\Search\Criteria\Range;
use Polus\Elastic\Search\Criteria\Term;
use Polus\Elastic\Search\Criteria\Terms;
use Polus\Elastic\Search\Exceptions\CriteriaException;
use stdClass;

class BoolBuilder implements QueryInterface
{
    protected const EQUALS = '=';
    protected const NOT_EQUALS = '!=';

    protected CriteriaCollection $must;
    protected CriteriaCollection $mustNot;
    protected CriteriaCollection $filter;

    public function __construct()
    {
        $this->clear();
    }

    /**
     * @throws CriteriaException
     */
    public function where(string $field, string $operator, string|int|null $value = null): static
    {
        if ($operator === static::NOT_EQUALS) {
            return $this->whereNot($field, $value);
        }

        if (func_num_args() == 2) {
            [$operator, $value] = [static::EQUALS, $operator];
        }

        $this->filter->add($this->createCriteria($field, $operator, $value));

        return $this;
    }

    /**
     * @throws CriteriaException
     */
    public function whereNot(string $field, string|int $value): QueryInterface
    {
        $this->mustNot->add($this->createCriteria($field, static::NOT_EQUALS, $value));
        return $this;
    }

    /**
     * @throws CriteriaException
     */
    public function whereIn(string $field, array $value): QueryInterface
    {
        $terms = new Terms($field, $value);
        $this->filter->add($terms);

        return $this;
    }

    /**
     * @throws CriteriaException
     */
    public function whereNotIn(string $field, array $value): QueryInterface
    {
        $terms = new Terms($field, $value);
        $this->mustNot->add($terms);

        return $this;
    }

    public function toDSL(): array
    {
        if ($this->isSearchEmpty()) {
            return ['match_all' => new stdClass()];
        }

        return [
            'bool' => array_merge(
                $this->searchWay('must', $this->must),
                $this->searchWay('mustNot', $this->mustNot),
                $this->searchWay('filter', $this->filter)
            )
        ];
    }

    /**
     * @throws CriteriaException
     */
    protected function createCriteria(string $field, string $operator, string $value): CriteriaInterface
    {
        return in_array($operator, [static::EQUALS, static::NOT_EQUALS])
            ? new Term($field, $value)
            : new Range($field, $operator, $value);
    }

    protected function searchWay(string $wayName, CriteriaCollection $collection): array
    {
        return $collection->isEmpty() ? [] : [$wayName => $collection->toDSL()];
    }

    protected function isSearchEmpty(): bool
    {
        return $this->must->isEmpty() && $this->mustNot->isEmpty() && $this->filter->isEmpty();
    }

    public function clear(): void
    {
        $this->must = new CriteriaCollection();
        $this->mustNot = new CriteriaCollection();
        $this->filter = new CriteriaCollection();
    }
}
