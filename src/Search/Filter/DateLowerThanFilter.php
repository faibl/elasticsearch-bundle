<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class DateLowerThanFilter extends DateTimeLowerThanFilter implements FilterInterface
{
    public const DATE_FORMAT = 'Y-m-d';

    public function __construct(string $key, ?\DateTimeInterface $dateTime, string $format = self::DATE_FORMAT)
    {
        parent::__construct($key, $dateTime, $format);
    }
}
