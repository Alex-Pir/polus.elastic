<?php

namespace Polus\Elastic\Search\Index;

use Polus\Elastic\Search\Aggregations\AggregationsQuery;
use Polus\Elastic\Search\ElasticClient;
use Polus\Elastic\Search\SearchQueryBuilder;

abstract class Index
{
    protected string $indexName;

    public function __construct(
        protected ?ElasticClient $client = null,
    ) {
        if (is_null($this->client)) {
            $this->client = new ElasticClient();
        }

        $this->indexName = $this->uniqueIndexName();
    }

    public function uniqueIndexName(): string
    {
        return $this->indexName() . '_' . substr(md5(json_encode(static::settings())), 0, 8);
    }

    public function query(): SearchQueryBuilder
    {
        return new SearchQueryBuilder($this->indexName, searchClient: $this->client);
    }

    public function aggregation(): AggregationsQuery
    {
        return new AggregationsQuery($this->indexName, searchClient: $this->client);
    }

    public function create(): void
    {
        $this->client->create($this->uniqueIndexName(), $this->settings());
    }

    public function delete(): void
    {
        $this->client->deleteIndex($this->uniqueIndexName());
    }

    public function getClient(): ?ElasticClient
    {
        return $this->client;
    }

    public function index(array $body): void
    {
        $this->client->index($this->uniqueIndexName(), $body);
    }

    public function bulk(array $body): void
    {
        $this->client->bulk($this->uniqueIndexName(), $body);
    }

    public function isExists(): bool
    {
        return $this->client->isExists($this->uniqueIndexName());
    }

    abstract public function indexName(): string;

    abstract public function settings(): array;

    abstract public function aggregationFields(): array;

    abstract public function indexing(): void;
}
