<?php

namespace Faibl\ElasticsearchBundle\Search\Query;

class RandomQuery implements QueryInterface
{
    public function getQuery(): array
    {
        return [
            'function_score' => [
                'functions' => [
                    [
                        'random_score' => [
                            'seed' => rand(1, 9999999999),
                            'field' => '_seq_no',
                        ],
                    ],
                ],
            ],
        ];
    }
}
