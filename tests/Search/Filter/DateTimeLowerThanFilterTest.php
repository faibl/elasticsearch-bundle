<?php

namespace App\Tests\Search\Filter;

use Faibl\ElasticsearchBundle\Search\Filter\DateTimeLowerThanFilter;
use PHPUnit\Framework\TestCase;

class DateTimeLowerThanFilterTest extends TestCase
{
    public function testDefaultFormat()
    {
        $now = new \DateTime();

        $filter = (new DateTimeLowerThanFilter('key', $now))
            ->getFilter();

        $expected = [
            'range' => [
                'key' => [
                    'lte' => $now->format('Y-m-d H:i:s'),
                ],
            ]
        ];

        $this->assertEquals($expected, $filter, 'Test default date-format');
    }

    public function testCustomFormat()
    {
        $now = new \DateTime();

        $filter = (new DateTimeLowerThanFilter('key', $now, 'c'))
            ->getFilter();

        $expected = [
            'range' => [
                'key' => [
                    'lte' => $now->format('c'),
                ],
            ]
        ];

        $this->assertEquals($expected, $filter, 'Test custom date-format');
    }
}
