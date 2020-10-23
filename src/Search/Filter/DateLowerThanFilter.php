<?php

namespace Faibl\ElasticsearchBundle\Search\Filter;

class DateLowerThanFilter extends DateTimeLowerThanFilter implements FilterInterface
{
    public const DATE_FORMAT = 'Y-m-d';
}
