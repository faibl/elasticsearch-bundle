<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class NotExistsFilter implements FilterInterface
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
                'must_not' => [
                    'exists' => [
                        'field' => $this->key
                    ],
                ],
            ],
        ];
    }
}
