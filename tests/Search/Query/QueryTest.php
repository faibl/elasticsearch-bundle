<?php

namespace Faibl\ElasticsearchBundle\Tests\Search\Query;

use Faibl\ElasticsearchBundle\Search\Filter\DateGreaterThanFilter;
use Faibl\ElasticsearchBundle\Search\Query\BoolQuery;
use Faibl\ElasticsearchBundle\Search\Query\MultiMatchQuery;
use Faibl\ElasticsearchBundle\Search\Query\Query;
use Faibl\ElasticsearchBundle\Search\Sort\FieldSort;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    public function test()
    {
        $expected = [
            "query" => [
                "bool" => [
                    "filter" => [
                        [
                            "range" => [
                                "date" => [
                                    "gte" => "2025-03-04"
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
            ],
            "sort" => [
                [
                    "date" => [
                        "order" => "desc"
                    ]
                ],
                [
                    "custom_field" => [
                        "order" => "asc"
                    ]
                ]
            ],
            "from" => 20,
            "size" => 40
        ];

        $boolQuery = (new BoolQuery())
            ->addMust(new MultiMatchQuery(['content_search'], 'search for content'))
            ->addFilter(new DateGreaterThanFilter('date', new \DateTimeImmutable('today')));

        $fieldSort = new FieldSort('date', 'desc');
        $customFieldSort = new FieldSort('custom_field');

        $query = (new Query())
            ->setQuery($boolQuery)
            ->addSorts([
                $fieldSort,
                $customFieldSort
            ])
            ->setFrom(20)
            ->setSize(40);

        $this->assertEquals($expected, $query->getQuery());
    }
}
