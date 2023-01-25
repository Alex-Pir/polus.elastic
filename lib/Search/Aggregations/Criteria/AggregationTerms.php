<?php

namespace Polus\Elastic\Search\Aggregations\Criteria;

use Polus\Elastic\Search\Aggregations\Results\SearchResult;
use Polus\Elastic\Search\Aggregations\Results\TermsResult;

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

    public function parseResult(array $fields): SearchResult
    {
        if (!isset($fields[$this->field])) {
            return new TermsResult($this->field, ['buckets' => []]);
        }

        return new TermsResult($this->field, $fields[$this->field]);
    }
}
