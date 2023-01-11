<?php

namespace Polus\Elastic\Search;

class SearchQueryBuilder extends QueryBuilder
{
    public function search(array $select = [], int $limit = 20, int $offset = 0): array
    {
        $body = [
            'query' => $this->getQuery()->toDSL(),
            'track_total_hits' => true,
            '_source' => $select,
            'size' => $limit,
            'from' => $offset
        ];

        return $this->searchClient->search($this->index, $body);
    }
}