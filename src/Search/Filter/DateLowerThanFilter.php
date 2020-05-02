<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class DateLowerThanFilter implements FilterInterface
{
    public const DATE_FORMAT = 'Y-m-d';

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
                    'lte' => $this->dateTime->format(self::DATE_FORMAT),
                ]
            ],
        ];
    }
}
