<?php

namespace Faibl\ElasticsearchBundle\Tests\Search\Query;

use Faibl\ElasticsearchBundle\Search\Filter\DateGreaterThanFilter;
use Faibl\ElasticsearchBundle\Search\Query\BoolQuery;
use Faibl\ElasticsearchBundle\Search\Query\MultiMatchQuery;
use PHPUnit\Framework\TestCase;

class BoolQueryTest extends TestCase
{
    public function test()
    {
        $expected = [
            "bool" => [
                "filter" => [
                    [
                        "range" => [
                            "date" => [
                                "gte" => (new \DateTimeImmutable('today'))->format('Y-m-d')
                            ]
                        ]
                    ]
                ],
                "must" => [
                    "multi_match" => [
                        "query" => "search for content",
                        "fields" => [
                            "content_search"
                        ],
                        "operator" => "and",
                        "type" => "cross_fields",
                        "analyzer" => "standard",
                        "zero_terms_query" => "all"
                    ]
                ],
                "must_not" => [
                ],
                "should" => [
                ],
                "minimum_should_match" => 0,
                "boost" => 1
            ]
        ];

        $query = (new BoolQuery())
            ->addMust(new MultiMatchQuery(['content_search'], 'search for content'))
            ->addFilter(new DateGreaterThanFilter('date', new \DateTimeImmutable('today')));

        $this->assertEquals($expected, $query->getQuery());
    }
}
