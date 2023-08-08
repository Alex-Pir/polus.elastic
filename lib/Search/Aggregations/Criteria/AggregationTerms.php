<?php

namespace Polus\Elastic\Search\Aggregations\Criteria;

use Polus\Elastic\Search\Aggregations\Results\SearchResult;
use Polus\Elastic\Search\Aggregations\Results\TermsResult;

class AggregationTerms extends AggregationCriteria
{
    public function __construct(
        string $field,
        string $value,
        protected int $size = 10,
        protected int $minDocCount = 1
    ) {
        parent::__construct($field, $value);
    }

    public function toDSL(): array
    {
        return [
            $this->field => [
                'terms' => [
                    'field' => $this->value,
                    'size' => $this->size,
                    'min_doc_count' => $this->minDocCount
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
