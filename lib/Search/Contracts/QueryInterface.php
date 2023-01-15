<?php

namespace Polus\Elastic\Search\Contracts;

interface QueryInterface extends CriteriaInterface
{
    public function where(string $field, string $operator, string|int|null $value = null): QueryInterface;
    public function whereIn(string $field, array $value): QueryInterface;
    public function whereNot(string $field, string|int $value): QueryInterface;
    public function whereNotIn(string $field, array $value): QueryInterface;
    public function clear(): void;
}
