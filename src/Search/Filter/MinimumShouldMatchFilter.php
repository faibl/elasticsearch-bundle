<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class MinimumShouldMatchFilter implements FilterInterface
{
    private $filters;

    public function __construct(array $filters)
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    public function hasFilter(): bool
    {
        return !empty($this->filters);
    }

    public function getFilter(): array
    {
        return [
            'bool' => [
                'should' => array_values($this->filters),
                'minimum_should_match' => 1
            ],
        ];
    }

    public function addFilter(FilterInterface $filter): void
    {
        if ($filter->hasFilter()) {
            $this->filters[] = $filter->getFilter();
        }
    }
}
