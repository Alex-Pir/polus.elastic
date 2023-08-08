<?php

namespace Polus\Elastic\Search;

class SearchQueryBuilder extends QueryBuilder
{
    protected ?string $collapseField = null;

    public function search(array $select = [], int $limit = 20, int $offset = 0): array
    {
        $body = [
            'query' => $this->getQuery()->toDSL(),
            'track_total_hits' => true,
            '_source' => $select,
            'size' => $limit,
            'from' => $offset
        ];

        if ($this->collapseField) {
            $body['collapse'] = [
                'field' => $this->collapseField
            ];
        }

        return $this->searchClient->search($this->index, $body);
    }

    public function groupBy(string $field): static
    {
        $this->collapseField = $field;

        return $this;
    }
}
