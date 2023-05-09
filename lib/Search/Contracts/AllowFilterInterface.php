<?php

namespace Polus\Elastic\Search\Contracts;

use Polus\Elastic\Search\QueryBuilder;

interface AllowFilterInterface
{
    public static function query(QueryBuilder $query, string $property, mixed $value): void;
}