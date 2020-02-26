<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

interface FilterInterface
{
    public function hasFilter(): bool;

    public function getFilter(): array;
}
