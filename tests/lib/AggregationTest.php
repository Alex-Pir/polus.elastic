<?php

namespace Polus\Elastic\UnitTests;

use PHPUnit\Framework\MockObject\MockObject;
use Polus\Elastic\Search\Aggregations\AggregationsQuery;
use Polus\Elastic\Search\Aggregations\Results\TermsResult;
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
        $termsKey = 'offers';

        $this->searchMock
            ->method('search')
            ->willReturn((new ElasticAggregationFactory())
                ->setMethodAsTerms()
                ->createForRequest()
            );

        $searchResult = $this->query->terms($termsKey, $termsKey)->search();

        $this->assertArrayHasKey($termsKey, $searchResult);
        $this->assertInstanceOf(TermsResult::class, $searchResult['offers']);

        /** @var TermsResult $termsResult */
        $termsResult = $searchResult['offers'];

        $buckets = $termsResult->getBuckets();

        $this->assertCount(2, $buckets);
        $this->assertEquals('first', $buckets[0]['key']);
        $this->assertEquals('second', $buckets[1]['key']);
    }
}
