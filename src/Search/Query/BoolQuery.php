<?php

namespace Faibl\ElasticsearchBundle\Search\Query;

use Faibl\ElasticsearchBundle\Search\Filter\FilterInterface;

class BoolQuery implements QueryInterface
{
    private $filter = [];
    private $must = [];
    private $should = [];
    private $mustNot = [];
    private $minimumShouldMatch = 0; // defines how many terms defined in should must be present. To create or query, set to 1

    public function getQuery(): array
    {
        return [
            'bool' => [
                'filter' => $this->filter, // real filter, no effect on score
                'must' => $this->must, // effect on score
                'must_not' => $this->mustNot,
                'should' => $this->should,
                'minimum_should_match' => $this->minimumShouldMatch,
                'boost' => 1,
            ],
        ];
    }

    public function addFilters(array $filters): self
    {
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }

        return $this;
    }

    public function addFilter(FilterInterface $filter): self
    {
        if ($filter->hasFilter()) {
            $this->filter[] = $filter->getFilter();
        }

        return $this;
    }

    public function addShould(FilterInterface $filter): self
    {
        if ($filter->hasFilter()) {
            $this->should[] = $filter->getFilter();
        }

        return $this;
    }

    public function addMustNot(FilterInterface $filter): self
    {
        if ($filter->hasFilter()) {
            $this->mustNot[] = $filter->getFilter();
        }

        return $this;
    }

    public function addMust(QueryInterface $query): self
    {
        $this->must = $query->getQuery();

        return $this;
    }

    public function setMinimumShouldMatch(int $minimumShouldMatch): self
    {
        $this->minimumShouldMatch = $minimumShouldMatch;

        return $this;
    }
}
