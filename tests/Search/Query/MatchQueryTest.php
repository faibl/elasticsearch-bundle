<?php

namespace Faibl\ElasticsearchBundle\Tests\Search\Query;

use Faibl\ElasticsearchBundle\Search\Query\MatchQuery;
use PHPUnit\Framework\TestCase;

class MatchQueryTest extends TestCase
{
    public function test_default()
    {
        $expected = [
            "match" => [
                "content_search" => [

                    "query" => "search for content",
                    "zero_terms_query" => "all"
                ]
            ]
        ];

        $query = new MatchQuery(
            'content_search',
            'search for content'
        );

        $this->assertEquals($expected, $query->getQuery());
    }

    public function test_zero_term_match_none()
    {
        $expected = [
            "match" => [
                "content_search" => [

                    "query" => "search for content",
                    "zero_terms_query" => "none"
                ]
            ]
        ];

        $query = new MatchQuery(
            'content_search',
            'search for content',
            false
        );

        $this->assertEquals($expected, $query->getQuery());
    }
}
