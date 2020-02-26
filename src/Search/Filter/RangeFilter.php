<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class RangeFilter implements FilterInterface
{
    private $field;
    private $gte;
    private $lte;

    public function __construct(string $field, $gte = null, $lte = null)
    {
        $this->field = $field;
        $this->gte = $gte;
        $this->lte = $lte;
    }

    public function hasFilter(): bool
    {
        return $this->lte && $this->gte;
    }

    public function getFilter(): array
    {
        return [
            'range' => [
                $this->field => [
                    'gte' => $this->gte,
                    'lte' => $this->lte,
                ],
            ],
        ];
    }

    public function setGte($gte)
    {
        $this->gte = $gte;

        return $this;
    }

    public function setLte($lte)
    {
        $this->lte = $lte;

        return $this;
    }
}
