<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class DateTimeGreaterThanFilter implements FilterInterface
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    private $key;
    private $dateTime;
    private $format;

    public function __construct(string $key, ?\DateTimeInterface $dateTime, string $format = self::DATE_FORMAT)
    {
        $this->key = $key;
        $this->dateTime = $dateTime;
        $this->format = $format;
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
                    'gte' => $this->dateTime->format($this->format),
                ]
            ],
        ];
    }
}
