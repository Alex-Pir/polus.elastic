<?php

namespace Polus\Elastic\Search\Aggregations\Results;

abstract class SearchResult
{
    public const TERMS_TYPE = 'terms';
    public const MIN_MAX_TYPE = 'min_max';

    protected ?string $type = null;

    public function __construct(
        protected string $field,
        protected array $values
    ) {
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}
