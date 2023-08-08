<?php

namespace Polus\Elastic\UnitTests;

use Polus\Elastic\Search\Index\Index;

class TestIndex extends Index
{
    public function indexName(): string
    {
        return 'test';
    }

    public function settings(): array
    {
        return [
            'settings' => [
                'index.max_result_window' => 500000,
                'index.max_inner_result_window' => 100000,
                'index.number_of_shards' => 1,
                'index.number_of_replicas' => 1,
            ],
            'mappings' => [
                'properties' => [
                    'offers' => ['type' => 'keyword']
                ]
            ]
        ];
    }

    public function aggregationFields(): array
    {
        return [];
    }

    public function indexing(): void
    {
        return;
    }
}