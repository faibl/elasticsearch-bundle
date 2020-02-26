<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class ExistsFilter implements FilterInterface
{
    private $key;

    public function __construct(?string $key)
    {
        $this->key = $key;
    }

    public function hasFilter(): bool
    {
        return !empty($this->key);
    }

    public function getFilter(): array
    {
        return [
            'bool' => [
                'must' => [
                    'exists' => [
                        'field' => $this->key
                    ],
                ],
            ],
        ];
    }
}
