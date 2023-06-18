<?php

namespace Polus\Elastic\Search;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Polus\Elastic\Constants;
use Polus\Elastic\Search\Exceptions\HostsNotFoundException;
use Polus\Elastic\Traits\HasModuleOption;

class ElasticClient
{
    use HasModuleOption;

    protected Client $client;

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     * @throws HostsNotFoundException
     */
    public function __construct()
    {
        $options = static::getAllModuleOptions();
        $host = trim($options[Constants::ELASTIC_HOST]);

        if (!$host) {
            throw new HostsNotFoundException();
        }

        $this->client = ClientBuilder::create()
            ->setHosts(
                array_filter(explode(PHP_EOL, $host))
            )
            ->build();
    }

    public function search(string $index, array $body): array
    {
        return $this->client->search([
            'index' => $index,
            'body' => $body
        ]);
    }

    public function create(string $index, array $body): void
    {
        $this->client->indices()->create([
            'index' => $index,
            'body' => $body
        ]);
    }

    public function deleteIndex(string $index): void
    {
        $this->client->indices()->delete([
            'index' => $index
        ]);
    }

    public function deleteById(string $id, string $index): void
    {
        $this->client->delete([
            'id' => $id,
            'index' => $index
        ]);
    }

    public function bulk(string $index, array $body): array
    {
        return $this->client->bulk([
            'index' => $index,
            'body' => $body
        ]);
    }

    public function index(string $index, array $body): array
    {
        return $this->client->index([
            'index' => $index,
            'body' => $body
        ]);
    }

    public function isExists(string $index): bool
    {
        return $this->client->indices()->exists([
            'index' => $index
        ]);
    }
}
