<?php

namespace Polus\Elastic\Search\Contracts;

interface SearchInterface
{
    public function search(): array;
    public function getQuery(): QueryInterface;
}