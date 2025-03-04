<?php

namespace Faibl\ElasticsearchBundle\Tests\Util;

use Faibl\ElasticsearchBundle\Util\ArrayUtil;
use PHPUnit\Framework\TestCase;

class ArrayUtilTest extends TestCase
{
    public function testFilterEmpty()
    {
        $array = [
            "query" => [
                "bool" => [
                    "filter" => [
                        [
                            "terms" => [
                                "client" => [
                                    "zeit",
                                    "academics"
                                ]
                            ]
                        ]
                    ],
                    "must" => [
                        "multi_match" => [
                            "query" => "",
                            "fields" => [
                                "content_search"
                            ],
                            "operator" => "and",
                            "type" => "cross_fields",
                            "analyzer" => "standard",
                            "zero_terms_query" => "all"
                        ]
                    ],
                    "must_not" => [],
                    "should" => [],
                    "minimum_should_match" => 0,
                    "boost" => 1
                ]
            ],
            "sort" => [],
            "from" => 0,
            "size" => 20
        ];

        $expected = [
            "query" => [
                "bool" => [
                    "filter" => [
                        [
                            "terms" => [
                                "client" => [
                                    "zeit",
                                    "academics"
                                ]
                            ]
                        ]
                    ],
                    "must" => [
                        "multi_match" => [
                            "query" => "",
                            "fields" => [
                                "content_search"
                            ],
                            "operator" => "and",
                            "type" => "cross_fields",
                            "analyzer" => "standard",
                            "zero_terms_query" => "all"
                        ]
                    ],
                    "must_not" => [],
                    "should" => [],
                    "minimum_should_match" => 0,
                    "boost" => 1
                ]
            ],
            "size" => 20
        ];

        $this->assertEquals($expected, ArrayUtil::filterEmpty($array));
    }
}
