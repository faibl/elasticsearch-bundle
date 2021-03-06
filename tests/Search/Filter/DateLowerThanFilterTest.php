<?php

namespace App\Tests\Search\Filter;

use Faibl\ElasticsearchBundle\Search\Filter\DateLowerThanFilter;
use PHPUnit\Framework\TestCase;

class DateLowerThanFilterTest extends TestCase
{
    public function testDefaultFormat()
    {
        $now = new \DateTime();

        $filter = (new DateLowerThanFilter('key', $now))
            ->getFilter();

        $expected = [
            'range' => [
                'key' => [
                    'lte' => $now->format('Y-m-d'),
                ],
            ]
        ];

        $this->assertEquals($expected, $filter, 'Test default date-format');
    }

    public function testCustomFormat()
    {
        $now = new \DateTime();

        $filter = (new DateLowerThanFilter('key', $now, 'c'))
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
