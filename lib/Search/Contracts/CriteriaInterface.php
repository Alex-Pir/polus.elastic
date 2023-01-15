<?php

namespace Polus\Elastic\Search\Contracts;

interface CriteriaInterface
{
    public function toDSL(): array;
}
