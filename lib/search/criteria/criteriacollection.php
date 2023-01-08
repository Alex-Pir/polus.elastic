<?php

namespace Polus\Elastic\Search\Criteria;

use Polus\Elastic\Search\Contracts\CriteriaInterface;

class CriteriaCollection implements CriteriaInterface
{
    /** @var CriteriaInterface[] */
    protected array $criteria = [];

    public function add(CriteriaInterface $criteriaValue): void
    {
        $this->criteria[] = $criteriaValue;
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
