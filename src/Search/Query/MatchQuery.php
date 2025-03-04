<?php

namespace Faibl\ElasticsearchBundle\Search\Query;

class MatchQuery implements QueryInterface
{
    public function __construct(
        private readonly string $field,
        private readonly string $value,
        private readonly bool $zeroTermsQuery = true
    ) {
    }

    public function getQuery(): array
    {
        return [
            'match' => [
                $this->field => [
                    'query' => $this->value,
                    'zero_terms_query' => $this->getZeroTermsQuery(),
                ],
            ],
        ];
    }

    private function getZeroTermsQuery(): string
    {
        return $this->zeroTermsQuery ? 'all' : 'none';
    }
}
