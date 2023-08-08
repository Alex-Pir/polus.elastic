<?php

namespace Polus\Elastic\UnitTests;

use PHPUnit\Framework\TestCase;
use Polus\Elastic\Search\QueryBuilder;
use Polus\Elastic\Search\ElasticClient;
use Polus\Elastic\Search\SearchQueryBuilder;

class QueryBuilderTest extends BaseTestCase
{
    protected QueryBuilder $query;

    public function setUp(): void
    {
        $searchMock = $this->getMockBuilder(ElasticClient::class)
            ->onlyMethods(['search'])
            ->getMock();

        $searchMock
            ->method('search')
            ->willReturn((new ElasticFabric())->createForRequest());

        $this->query = new SearchQueryBuilder((new TestIndex())->uniqueIndexName(), searchClient: $searchMock);
    }

    public function testSearchSuccess(): void
    {
        $searchResult = $this->query->search();

        $this->assertArrayHasKey('hits', $searchResult);
    }

    public function testBoolMethodIntegrationSuccess(): void
    {
        $this->query->where('test_field', 'test_value');

        $dsl = $this->query->getQuery()->toDSL();

        $this->assertArrayHasKey('filter', $dsl['bool']);
        $this->assertArrayHasKey('test_field', $dsl['bool']['filter'][0]['term']);
        $this->assertEquals('test_value', $dsl['bool']['filter'][0]['term']['test_field']);
    }
}
