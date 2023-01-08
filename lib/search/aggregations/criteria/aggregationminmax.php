<?php

namespace Polus\Elastic\Search\Aggregations\Criteria;

class AggregationMinMax extends AggregationCriteria
{
    public function toDSL(): array
    {
        return [
            "{$this->field}_min" => ['min' => ['field' => $this->value]],
            "{$this->field}_max" => ['max' => ['field' => $this->value]]
        ];
    }
}
