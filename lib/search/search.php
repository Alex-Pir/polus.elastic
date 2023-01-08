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

class Search
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
}
