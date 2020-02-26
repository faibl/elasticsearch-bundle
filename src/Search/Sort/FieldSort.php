<?php

namespace Faibl\ElasticsearchBundle\Search\Sort;

class FieldSort implements SortInterface
{
    private $field;
    private $direction;

    public function __construct(string $field, string $direction = 'asc')
    {
        $this->field = $field;
        $this->direction = $direction;
    }

    public function hasSort(): bool
    {
        return true;
    }

    public function getSort(): array
    {
        return [$this->field => ['order' => $this->direction]];
    }
}
