<?php

namespace Polus\Elastic\Search\Aggregations\Criteria;

class AggregationTopHits implements AggregationSubTermsInterface
{
    protected array $sort = [];

    protected array $size;

    public function __construct(int $size = 1)
    {
        if ($size <= 0) {
            $size = 1;
        }

        $this->size = ['size' => $size];
    }

    public function sort(string $field, string $order = 'asc')
    {
        $this->sort['sort'][] = [$field => ['order' => $order]];
    }

    public function toDSL(): array
    {
        return [
            'aggs' => [
                'top_hits' => array_merge($this->size, $this->sort)
            ]
        ];
    }
}