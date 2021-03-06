<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class BoolTermFilter implements FilterInterface
{
    private $key;
    private $value;

    public function __construct(string $key, bool $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function hasFilter(): bool
    {
        return !is_null($this->value);
    }

    public function getFilter(): array
    {
        return ['term' => [$this->key => $this->value]];
    }
}
