<?php

namespace Faibl\ElasticsearchBundle\Search\Query;

class MultiMatchQuery implements QueryInterface
{
    private string $operator = 'and';
    private string $type = 'cross_fields';
    private string $analyzer = 'standard';

    public function __construct(
        private array $fields,
        private string $phrase
    ) {
    }

    public function getQuery(): array
    {
        $query = [
            'multi_match' => [
                'query' => $this->phrase,
                'fields' => $this->fields,
                'operator' => $this->operator,
                'type' => $this->type,
                'analyzer' => $this->analyzer,
                'zero_terms_query' => 'all',
            ],
        ];

        return $query;
    }

    public function setPhrase(string $phrase)
    {
        $this->phrase = $phrase;

        return $this;
    }

    public function setFields(array $fields): MultiMatchQuery
    {
        $this->fields = $fields;

        return $this;
    }

    public function setOperator(string $operator): MultiMatchQuery
    {
        $this->operator = $operator;

        return $this;
    }

    public function setType(string $type): MultiMatchQuery
    {
        $this->type = $type;

        return $this;
    }

    public function setAnalyzer(string $analyzer): MultiMatchQuery
    {
        $this->analyzer = $analyzer;

        return $this;
    }
}
