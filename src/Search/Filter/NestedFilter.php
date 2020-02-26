<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class NestedFilter implements FilterInterface
{
    private $path;
    private $filter;

    public function __construct(string $path, ?FilterInterface $filter = null)
    {
        $this->path = $path;
        $this->filter = $filter;
    }

    public function hasFilter(): bool
    {
        return !empty($this->filter);
    }

    public function getFilter(): array
    {
        return [
            'nested' => [
                'path' => $this->path,
                'query' => $this->filter->getFilter(),
            ],
        ];
    }

    public function setFilter(FilterInterface $filter): self
    {
        $this->filter = $filter;

        return $this;
    }
}
