<?php

namespace Polus\Elastic\UnitTests;

use PHPUnit\Framework\TestCase;
use Polus\Elastic\Search\Index\Index;

abstract class BaseTestCase extends TestCase
{
    protected static Index $testIndex;

    public static function setUpBeforeClass(): void
    {
        static::$testIndex = new TestIndex();

        if (!static::$testIndex->isExists()) {
            static::$testIndex->create();
        }
    }
}