<?php

namespace Faibl\ElasticsearchBundle\Search\Query;

class MatchQuery implements QueryInterface
{
    private $field;
    private $value;
    private $zeroTermsQuery;

    public function __construct(string $field, string $value, bool $zeroTermsQuery = true)
    {
        $this->field = $field;
        $this->value = $value;
        $this->zeroTermsQuery = $zeroTermsQuery ? 'all' : 'none';
    }

    public function getQuery(): array
    {
        return [
            'match' => [
                $this->field => [
                    'query' => $this->value,
                    'zero_terms_query' => $this->zeroTermsQuery,
                ],
            ],
        ];
    }
}
