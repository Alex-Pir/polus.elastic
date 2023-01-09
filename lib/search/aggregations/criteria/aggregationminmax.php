<?php

namespace Polus\Elastic\Search\Aggregations\Criteria;

use Polus\Elastic\Search\Aggregations\Results\MinMaxResult;
use Polus\Elastic\Search\Aggregations\Results\SearchResult;

class AggregationMinMax extends AggregationCriteria
{
    public function toDSL(): array
    {
        return [
            "{$this->field}_min" => ['min' => ['field' => $this->value]],
            "{$this->field}_max" => ['max' => ['field' => $this->value]]
        ];
    }

    public function parseResult(array $fields): SearchResult
    {
        if (!isset($fields["{$this->field}_min"]) && !isset($fields["{$this->field}_max"])) {
            return new MinMaxResult(
                $this->field,
                [
                    "{$this->field}_min" => 0,
                    "{$this->field}_max" => 0
                ]
            );
        }

        return new MinMaxResult(
            $this->field,
            [
                "{$this->field}_min" => $fields["{$this->field}_min"],
                "{$this->field}_max" => $fields["{$this->field}_max"]
            ]
        );
    }
}
