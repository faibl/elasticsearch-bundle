<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class BoolFilter implements FilterInterface
{
    private $filter = [];
    private $must = [];
    private $mustNot = [];
    private $should = [];
    private $minimumShouldMatch = 0;

    public function hasFilter(): bool
    {
        return !empty($this->filter) || !empty($this->must) || !empty($this->mustNot) || !empty($this->should);
    }

    public function getFilter(): array
    {
        return [
            'bool' => [
                'filter' => $this->filter, // real filter, no effect on score
                'should' => $this->should, // used for or clauses
                'minimum_should_match' => $this->minimumShouldMatch,
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

    public function addMusts(array $filters): self
    {
        foreach ($filters as $filter) {
            $this->addMust($filter);
        }

        return $this;
    }


    public function addMust(FilterInterface $filter): self
    {
        if ($filter->hasFilter()) {
            $this->must[] = $filter->getFilter();
        }

        return $this;
    }

    public function addMustNots(array $filters): self
    {
        foreach ($filters as $filter) {
            $this->addMustNot($filter);
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

    public function addShoulds(array $filters): self
    {
        foreach ($filters as $filter) {
            $this->addShould($filter);
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

    public function setMinimumShouldMatch(int $minimumShouldMatch): self
    {
        $this->minimumShouldMatch = $minimumShouldMatch;

        return $this;
    }
}
