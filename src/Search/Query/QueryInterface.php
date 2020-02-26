<?php

namespace Faibl\ElasticsearchBundle\Search\Query;

interface QueryInterface
{
    public function getQuery(): array;
}
