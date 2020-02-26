<?php

namespace Faibl\ElasticsearchBundle\Search\Query;

use Faibl\ElasticsearchBundle\Search\Sort\SortInterface;
use App\Util\ArrayUtil;

class Query implements QueryInterface
{
    private $query = [];
    private $sort = [];
    private $from = 0;
    private $size = 20;

    public function getQuery(): array
    {
        $query = [
            'query' => $this->query,
            'sort' => $this->sort,
            'from' => $this->from,
            'size' => $this->size,
        ];

        return ArrayUtil::filterEmpty($query);
    }

    public function setQuery(QueryInterface $query): self
    {
        $this->query = $query->getQuery();

        return $this;
    }

    public function addSorts(array $sorts): self
    {
        foreach ($sorts as $sort) {
            $this->addSort($sort);
        }

        return $this;
    }

    public function addSort(SortInterface $sort): self
    {
        if ($sort->hasSort()) {
            $this->sort[] = $sort->getSort();
        }

        return $this;
    }

    public function setFrom(int $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }
}
