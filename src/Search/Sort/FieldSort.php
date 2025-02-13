<?php

namespace Faibl\ElasticsearchBundle\Search\Sort;

class FieldSort implements SortInterface
{
    public function __construct(
        private readonly string $field,
        private readonly string $direction = 'asc'
    ) {
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
