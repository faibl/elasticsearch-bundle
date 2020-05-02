<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class IdFilter implements FilterInterface
{
    private $id;

    public function __construct(?int $id)
    {
        $this->id = $id;
    }

    public function hasFilter(): bool
    {
        return !empty($this->id);
    }

    public function getFilter(): array
    {
        return (new TermFilter('_id', $this->id))->getFilter();
    }
}
