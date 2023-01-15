<?php

namespace Polus\Elastic\UnitTests;

use PHPUnit\Framework\TestCase;
use Polus\Elastic\Search\BoolBuilder;
use Polus\Elastic\Search\Contracts\QueryInterface;

class BoolBuilderTest extends TestCase
{
    protected QueryInterface $boolBuilder;

    public function setUp(): void
    {
        $this->boolBuilder = new BoolBuilder();
    }

    public function testMethodWhereEqualsSuccess()
    {
        $this->boolBuilder->where('test_field', 'test_value');

        $dsl = $this->boolBuilder->toDSL();

        $this->assertArrayHasKey('filter', $dsl['bool']);
        $this->assertArrayHasKey('test_field', $dsl['bool']['filter'][0]['term']);
        $this->assertEquals('test_value', $dsl['bool']['filter'][0]['term']['test_field']);
    }

    public function testMethodWhereGreatSuccess()
    {
        $this->boolBuilder->where('test_field', '>', 'test_value');

        $dsl = $this->boolBuilder->toDSL();

        $this->assertArrayHasKey('filter', $dsl['bool']);
        $this->assertArrayHasKey('test_field', $dsl['bool']['filter'][0]['range']);
        $this->assertEquals('test_value', $dsl['bool']['filter'][0]['range']['test_field']['gt']);

        $this->boolBuilder->clear();
        $this->boolBuilder->where('test_field', '>=', 'test_value');

        $dsl = $this->boolBuilder->toDSL();

        $this->assertEquals('test_value', $dsl['bool']['filter'][0]['range']['test_field']['gte']);
    }

    public function testMethodWhereLessSuccess()
    {
        $this->boolBuilder->where('test_field', '<', 'test_value');

        $dsl = $this->boolBuilder->toDSL();

        $this->assertArrayHasKey('filter', $dsl['bool']);
        $this->assertArrayHasKey('test_field', $dsl['bool']['filter'][0]['range']);
        $this->assertEquals('test_value', $dsl['bool']['filter'][0]['range']['test_field']['lt']);

        $this->boolBuilder->clear();
        $this->boolBuilder->where('test_field', '<=', 'test_value');

        $dsl = $this->boolBuilder->toDSL();

        $this->assertEquals('test_value', $dsl['bool']['filter'][0]['range']['test_field']['lte']);
    }

    public function testMethodWhereNotEquals()
    {
        $this->boolBuilder->where('test_field', '!=', 'test_value');

        $dsl = $this->boolBuilder->toDSL();

        $this->assertArrayHasKey('mustNot', $dsl['bool']);
        $this->assertArrayHasKey('test_field', $dsl['bool']['mustNot'][0]['term']);
        $this->assertEquals('test_value', $dsl['bool']['mustNot'][0]['term']['test_field']);

        $this->boolBuilder->clear();

        $this->boolBuilder->whereNot('test_field', 'test_value');
        $this->assertEquals('test_value', $dsl['bool']['mustNot'][0]['term']['test_field']);
    }

    public function testMethodWhereIn()
    {
        $assertValue = [1, 2, 3];
        $this->boolBuilder->whereIn('test_field', $assertValue);

        $dsl = $this->boolBuilder->toDSL();

        $this->assertArrayHasKey('filter', $dsl['bool']);
        $this->assertArrayHasKey('test_field', $dsl['bool']['filter'][0]['terms']);
        $this->assertEquals($assertValue, $dsl['bool']['filter'][0]['terms']['test_field']);
    }

    public function testMethodWhereNotIn()
    {
        $assertValue = [1, 2, 3];
        $this->boolBuilder->whereNotIn('test_field', $assertValue);

        $dsl = $this->boolBuilder->toDSL();

        $this->assertArrayHasKey('mustNot', $dsl['bool']);
        $this->assertArrayHasKey('test_field', $dsl['bool']['mustNot'][0]['terms']);
        $this->assertEquals($assertValue, $dsl['bool']['mustNot'][0]['terms']['test_field']);
    }
}
