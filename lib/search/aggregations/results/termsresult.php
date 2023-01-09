<?php

namespace Polus\Elastic\Search\Aggregations\Results;

class TermsResult extends SearchResult
{
    protected ?string $type = self::TERMS_TYPE;

    public function getBuckets(): array
    {
        return $this->values['buckets'];
    }
}
