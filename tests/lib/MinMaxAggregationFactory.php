<?php

namespace Polus\Elastic\UnitTests;

class MinMaxAggregationFactory extends ElasticAggregationFactory
{
    protected function aggregations(): array
    {
        return [
            'price_min' => [
                'value' => rand(1, 100),
            ],
            'price_max' => [
                'value' => rand(101, 1000),
            ],
        ];
    }
}
