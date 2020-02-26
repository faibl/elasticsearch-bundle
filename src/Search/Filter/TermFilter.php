<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class TermFilter implements FilterInterface
{
    private $key;
    private $value;

    public function __construct(string $key, ?string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function hasFilter(): bool
    {
        return !empty($this->value);
    }

    public function getFilter(): array
    {
        return ['term' => [$this->key => $this->value]];
    }
}
