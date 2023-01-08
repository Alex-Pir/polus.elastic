<?php

namespace Polus\Elastic\Search\Aggregations\Criteria;

class AggregationTerms extends AggregationCriteria
{
    public function toDSL(): array
    {
        return [
            $this->field => [
                'terms' => [
                    'field' => $this->value,
                    'min_doc_count' => 0
                ]
            ]
        ];
    }
}
