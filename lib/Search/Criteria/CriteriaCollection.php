<?php

namespace Polus\Elastic\Search\Criteria;

use Polus\Elastic\Search\Contracts\CriteriaInterface;

class CriteriaCollection implements CriteriaInterface
{
    /** @var CriteriaInterface[] */
    protected array $criteria = [];

    public function add(string $fieldName, CriteriaInterface $criteriaValue): void
    {
        $this->criteria[$fieldName] = $criteriaValue;
    }

    public function remove(string $fieldName): void
    {
        if (isset($this->criteria[$fieldName])) {
            $this->criteria = array_filter(
                $this->criteria,
                fn (string $key) => $key !== $fieldName,
                ARRAY_FILTER_USE_KEY
            );
        }
    }

    public function toDSL(): array
    {
        return array_map(fn(CriteriaInterface $criteria) => $criteria->toDSL(), $this->criteria);
    }

    public function isEmpty(): bool
    {
        return empty($this->criteria);
    }
}
