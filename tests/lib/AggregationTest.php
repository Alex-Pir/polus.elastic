<?php

namespace Polus\Elastic\UnitTests;

use PHPUnit\Framework\MockObject\MockObject;
use Polus\Elastic\Search\Aggregations\AggregationsQuery;
use Polus\Elastic\Search\Aggregations\Criteria\AggregationMinMax;
use Polus\Elastic\Search\Aggregations\Criteria\AggregationTerms;
use Polus\Elastic\Search\Aggregations\Results\MinMaxResult;
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

    public function testAggregationTermsSuccess()
    {
        $termsKey = 'offers';

        $this->searchMock
            ->method('search')
            ->willReturn((new TermsAggregationFactory())
                ->createForRequest()
            );

        $searchResult = $this->query->terms($termsKey, $termsKey)->search();

        $this->assertArrayHasKey($termsKey, $searchResult);
        $this->assertInstanceOf(TermsResult::class, $searchResult[$termsKey]);

        /** @var TermsResult $termsResult */
        $termsResult = $searchResult[$termsKey];

        $buckets = $termsResult->getBuckets();

        $this->assertCount(2, $buckets);
        $this->assertEquals('first', $buckets[0]['key']);
        $this->assertEquals('second', $buckets[1]['key']);
    }

    public function testAggregationTermsBucketEmptySuccess()
    {
        $termsKey = 'offers1';

        $this->searchMock
            ->method('search')
            ->willReturn((new TermsAggregationFactory())
                ->createForRequest()
            );

        $searchResult = $this->query->terms($termsKey, $termsKey)->search();

        $this->assertArrayHasKey($termsKey, $searchResult);

        /** @var TermsResult $termsResult */
        $termsResult = $searchResult[$termsKey];

        $buckets = $termsResult->getBuckets();

        $this->assertCount(0, $buckets);
    }

    public function testAggregationMinMaxSuccess()
    {
        $minMaxKey = 'price';

        $this->searchMock
            ->method('search')
            ->willReturn((new MinMaxAggregationFactory())
                ->createForRequest()
            );

        $searchResult = $this->query->minMax($minMaxKey, $minMaxKey)->search();

        $this->assertArrayHasKey($minMaxKey, $searchResult);
        $this->assertInstanceOf(MinMaxResult::class, $searchResult[$minMaxKey]);

        /** @var MinMaxResult $minMaxResult */
        $minMaxResult = $searchResult[$minMaxKey];

        $min = $minMaxResult->getMin();
        $max = $minMaxResult->getMax();

        $this->assertLessThan($max, $min);
    }

    public function testAggregationMinMaxEmptySuccess()
    {
        $minMaxKey = 'price1';

        $this->searchMock
            ->method('search')
            ->willReturn((new MinMaxAggregationFactory())
                ->createForRequest()
            );

        $searchResult = $this->query->minMax($minMaxKey, $minMaxKey)->search();

        $this->assertArrayHasKey($minMaxKey, $searchResult);

        /** @var MinMaxResult $minMaxResult */
        $minMaxResult = $searchResult[$minMaxKey];

        $min = $minMaxResult->getMin();
        $max = $minMaxResult->getMax();

        $this->assertEquals(0, $min);
        $this->assertEquals(0, $max);
    }

    public function testAggregationFilterWithMinMaxSuccess()
    {
        $minMaxKey = 'price';

        $this->searchMock
            ->method('search')
            ->willReturn((new MinMaxAggregationFactory())
                ->createForRequest()
            );

        $searchResult = $this->query->filter(
            $minMaxKey,
            $this->query->getQuery()->copyWithoutField($minMaxKey), new AggregationMinMax($minMaxKey, $minMaxKey)
        )->search();

        $this->assertArrayHasKey($minMaxKey, $searchResult);
        $this->assertInstanceOf(MinMaxResult::class, $searchResult[$minMaxKey]);

        /** @var MinMaxResult $minMaxResult */
        $minMaxResult = $searchResult[$minMaxKey];

        $min = $minMaxResult->getMin();
        $max = $minMaxResult->getMax();

        $this->assertLessThan($max, $min);
    }

    public function testAggregationWithTermsSuccess()
    {
        $termsKey = 'offers';

        $this->searchMock
            ->method('search')
            ->willReturn((new TermsAggregationFactory())
                ->createForRequest()
            );

        $searchResult = $this->query->filter(
            $termsKey,
            $this->query->getQuery()->copyWithoutField($termsKey), new AggregationTerms($termsKey, $termsKey)
        )->search();

        $this->assertArrayHasKey($termsKey, $searchResult);
        $this->assertInstanceOf(TermsResult::class, $searchResult[$termsKey]);

        /** @var TermsResult $termsResult */
        $termsResult = $searchResult[$termsKey];

        $buckets = $termsResult->getBuckets();

        $this->assertCount(2, $buckets);
        $this->assertEquals('first', $buckets[0]['key']);
        $this->assertEquals('second', $buckets[1]['key']);
    }
}
