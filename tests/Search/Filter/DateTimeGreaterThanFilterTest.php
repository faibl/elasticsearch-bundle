<?php

namespace App\Tests\Search\Filter;

use Faibl\ElasticsearchBundle\Search\Filter\DateTimeGreaterThanFilter;
use PHPUnit\Framework\TestCase;

class DateTimeGreaterThanFilterTest extends TestCase
{
    public function testDefaultFormat()
    {
        $now = new \DateTime();

        $filter = (new DateTimeGreaterThanFilter('key', $now))
            ->getFilter();

        $expected = [
            'range' => [
                'key' => [
                    'gte' => $now->format('Y-m-d H:i:s'),
                ],
            ]
        ];

        $this->assertEquals($expected, $filter, 'Test default date-format');
    }

    public function testCustomFormat()
    {
        $now = new \DateTime();

        $filter = (new DateTimeGreaterThanFilter('key', $now, 'c'))
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
