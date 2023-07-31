<?php

namespace Polus\Elastic\UnitTests;

class ElasticAggregationFactory extends ElasticFabric
{
    public const TERMS = 'terms';

    protected string $method = '';

    public function createForRequest(): array
    {
        return [
            'hits' => [
                'hits' => [
                ]
            ],
            'aggregations' => $this->method === static::TERMS ? $this->terms() : ''
        ];
    }

    public function setMethodAsTerms(): static
    {
        $this->method = static::TERMS;
        return $this;
    }

    protected function terms(): array
    {
        return [
            'offers' => [
                'doc_count_error_upper_bound' => 0,
                'sum_other_doc_count' => 0,
                'buckets' => [
                    [
                        'key' => 'first',
                        'doc_count' => rand(1, 100)
                    ],
                    [
                        'key' => 'second',
                        'doc_count' => rand(1, 100)
                    ]
                ]
            ]
        ];
    }
}
