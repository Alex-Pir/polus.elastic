<?php

namespace Polus\Elastic\UnitTests;

class TermsAggregationFactory extends ElasticAggregationFactory
{
    protected function aggregations(): array
    {
        return [
            'offers' => [
                'doc_count_error_upper_bound' => 0,
                'sum_other_doc_count' => 0,
                'buckets' => [
                    [
                        'key' => 'first',
                        'doc_count' => rand(1, 100),
                    ],
                    [
                        'key' => 'second',
                        'doc_count' => rand(1, 100),
                    ],
                ],
            ],
        ];
    }
}