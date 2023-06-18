<?php

namespace Polus\Elastic\UnitTests;

use PHPUnit\Framework\MockObject\MockObject;
use Polus\Elastic\Search\Aggregations\AggregationsQuery;
use Polus\Elastic\Search\ElasticClient;
use Polus\Elastic\Search\QueryBuilder;

class AggregationTest extends BaseTestCase
{
    protected QueryBuilder $query;
    protected MockObject $searchMock;

    protected function setUp(): void
    {
        $this->searchMock = $this->getMockBuilder(ElasticClient::class)
            ->onlyMethods(['search'])
            ->getMock();

        $this->query = new AggregationsQuery(static::$testIndex->uniqueIndexName(), searchClient: $this->searchMock);
    }

    public function testAggregationFilterSuccess()
    {
        $this->searchMock
            ->method('search')
            ->willReturn((new ElasticAggregationFactory())->createForRequest());

        $searchResult = $this->query->terms('offers', 'offers')->search();
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logTestSearch.log', print_r($searchResult, true) . "\n", FILE_APPEND);
    }
}