<?php

namespace Polus\Elastic\Search\Aggregations\Results;

class MinMaxResult extends SearchResult
{
    protected ?string $type = self::MIN_MAX_TYPE;

    public function getMin(): int|float
    {
        return $this->values["{$this->field}_min"];
    }

    public function getMax(): int|float
    {
        return $this->values["{$this->field}_max"];
    }
}
