<?php

namespace Polus\Elastic\UnitTests;

abstract class ElasticAggregationFactory extends ElasticFabric
{
    protected string $method = '';

    public function createForRequest(): array
    {
        return [
            'hits' => [
                'hits' => [
                ]
            ],
            'aggregations' => $this->aggregations()
        ];
    }

    abstract protected function aggregations(): array;
}
