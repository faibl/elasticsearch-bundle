<?php

namespace Faibl\ElasticsearchBundle\Search\Query;

class MatchAllQuery implements QueryInterface
{
    public function getQuery(): array
    {
        return ['match_all' => new \ArrayObject()];
    }
}
