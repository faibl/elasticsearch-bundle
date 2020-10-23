<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class DateGreaterThanFilter extends DateTimeGreaterThanFilter implements FilterInterface
{
    public const DATE_FORMAT = 'Y-m-d';
}
