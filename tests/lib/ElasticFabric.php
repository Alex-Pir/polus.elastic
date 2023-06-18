<?php

namespace Polus\Elastic\UnitTests;

class ElasticFabric implements FabricInterface
{
    public static string $indexName = 'index_test';

    public function createForRequest(): array
    {
        return [
            'hits' => [
                'hits' => [
                    [
                        '_index' => 'test_index',
                        '_source' => [
                            'product' => [
                                'id' => 1,
                                'price' => 100
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}

