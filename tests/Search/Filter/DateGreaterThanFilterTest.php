<?php

namespace App\Tests\Search\Filter;

use Faibl\ElasticsearchBundle\Search\Filter\DateGreaterThanFilter;
use PHPUnit\Framework\TestCase;

class DateGreaterThanFilterTest extends TestCase
{
    public function testDefaultFormat()
    {
        $now = new \DateTime();

        $filter = (new DateGreaterThanFilter('key', $now))
            ->getFilter();

        $expected = [
            'range' => [
                'key' => [
                    'gte' => $now->format('Y-m-d'),
                ],
            ]
        ];

        $this->assertEquals($expected, $filter, 'Test default date-format');
    }

    public function testCustomFormat()
    {
        $now = new \DateTime();

        $filter = (new DateGreaterThanFilter('key', $now, 'c'))
            ->getFilter();

        $expected = [
            'range' => [
                'key' => [
                    'gte' => $now->format('c'),
                ],
            ]
        ];

        $this->assertEquals($expected, $filter, 'Test custom date-format');
    }
}
