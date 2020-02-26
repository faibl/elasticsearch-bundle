<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

use Faibl\ElasticsearchBundle\Spec;

class DateGreaterThanFilter implements FilterInterface
{
    private $key;
    private $dateTime;

    public function __construct(string $key, ?\DateTimeInterface $dateTime)
    {
        $this->key = $key;
        $this->dateTime = $dateTime;
    }

    public function hasFilter(): bool
    {
        return !empty($this->dateTime);
    }

    public function getFilter(): array
    {
        return [
            'range' => [
                $this->key => [
                    'gte' => $this->dateTime->format(Spec::DATE_FORMAT),
                ]
            ],
        ];
    }
}
