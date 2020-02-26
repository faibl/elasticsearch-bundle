<?php

namespace Faibl\ElasticsearchBundle\Search\Sort;

interface SortInterface
{
    public function hasSort(): bool;

    public function getSort(): array;
}
