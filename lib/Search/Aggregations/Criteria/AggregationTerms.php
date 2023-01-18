<?php

namespace Polus\Elastic\Search\Aggregations\Criteria;

use Polus\Elastic\Search\Aggregations\AggregationsCollection;
use Polus\Elastic\Search\Aggregations\Results\SearchResult;
use Polus\Elastic\Search\Aggregations\Results\TermsResult;

class AggregationTerms extends AggregationCriteria
{
    protected int $size = 1000;

    protected AggregationsCollection $collection;

    public function __construct(string $field, string $value)
    {
        parent::__construct($field, $value);
        $this->collection = new AggregationsCollection();
    }

    public function toDSL(): array
    {
        return [
            $this->field => [
                'terms' => [
                    'field' => $this->value,
                    'min_doc_count' => 0,
                    'size' => $this->size
                ]
            ]
        ];
    }

    public function setSize(int $size): AggregationTerms
    {
        $this->size = $size;
        return $this;
    }

    public function parseResult(array $fields): SearchResult
    {
        if (!isset($fields[$this->field])) {
            return new TermsResult($this->field, ['buckets' => []]);
        }

        return new TermsResult($this->field, $fields[$this->field]);
    }
}
