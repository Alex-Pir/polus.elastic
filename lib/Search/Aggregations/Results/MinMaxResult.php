<?php

namespace Polus\Elastic\Search\Aggregations\Results;

use Polus\Elastic\Search\Exceptions\AggregationResultException;

class MinMaxResult extends SearchResult
{
    protected ?string $type = self::MIN_MAX_TYPE;

    /**
     * @throws AggregationResultException
     */
    public function getMin(): int|float
    {
        if (!isset($this->values["{$this->field}_min"])) {
            throw new AggregationResultException("{$this->field}_min");
        }

        return $this->values["{$this->field}_min"]['value'] ?? $this->values["{$this->field}_min"];
    }

    /**
     * @throws AggregationResultException
     */
    public function getMax(): int|float
    {
        if (!isset($this->values["{$this->field}_max"])) {
            throw new AggregationResultException("{$this->field}_max");
        }

        return $this->values["{$this->field}_max"]['value'] ?? $this->values["{$this->field}_max"];
    }
}
