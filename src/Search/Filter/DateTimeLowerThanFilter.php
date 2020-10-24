<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class DateTimeLowerThanFilter implements FilterInterface
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    protected $key;
    protected $dateTime;
    protected $format;

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
                    'lte' => $this->dateTime->format($this->format),
                ]
            ],
        ];
    }
}
