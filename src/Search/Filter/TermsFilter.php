<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class TermsFilter implements FilterInterface
{
    private $key;
    private $values;

    public function __construct(string $key, array $values)
    {
        $this->key = $key;
        $this->values = $values;
    }

    public function hasFilter(): bool
    {
        return !empty($this->values);
    }

    public function getFilter(): array
    {
        return ['terms' => [$this->key => $this->values]];
    }
}
