<?php

namespace Polus\Elastic\UnitTests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Polus\Elastic\Search\Search;
use Polus\Elastic\Search\SearchQueryBuilder;

class SearchTest extends TestCase
{
    protected MockObject $searchMock;
    protected FabricInterface $elastic;

    public function setUp(): void
    {
        $this->searchMock = $this->getMockBuilder(Search::class)
            ->onlyMethods(['search'])
            ->getMock();
    }

    public function testQueryResult(): void
    {
        $this->elastic = new ElasticFabric();
        $this->searchMock
            ->expects($this->once())
            ->method('search')
            ->willReturn($this->elastic->createForRequest());

        $query = new SearchQueryBuilder($_SERVER['ELASTIC_TEST_INDEX'], searchClient: $this->searchMock);
        $searchResult = $query->search();

        $this->assertArrayHasKey('hits', $searchResult);
    }
}
