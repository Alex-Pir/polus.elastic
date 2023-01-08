<?php

namespace Polus\Elastic\Search;

class Index
{
    public function query(): SearchInterface
    {
        return new Query();
    }

    //public function search
}