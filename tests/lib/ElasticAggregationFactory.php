<?php

namespace Polus\Elastic\UnitTests;

class ElasticAggregationFactory extends ElasticFabric
{
    //protected

    public function createForRequest(): array
    {
        return [
            'hits' => [
                'hits' => [
                ]
            ],
            'aggregations' => [

            ]
        ];
    }

    public function setTerms()
    {

    }

    protected function terms(): array
    {
        return [
            'offers' => [
                'doc_count' => rand(1, 10),
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
            ]
        ];
    }
}
