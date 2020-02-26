<?php

namespace Faibl\ElasticsearchBundle\Search\Sort;

class CustomFieldValueSort implements SortInterface
{
    private $field;
    private $scores;
    private $order;

    public function __construct(string $field,  array $scores, string $order = 'desc')
    {
        $this->field = $field;
        $this->scores = $scores;
        $this->order = $order;
    }

    public function hasSort(): bool
    {
        return !empty($this->field) && !empty($this->scores);
    }

    public function getSort(): array
    {
        return [
            '_script' => [
                'type' => 'number',
                'script' => [
                    'lang' => 'painless',
                    'source' => $this->getScript(),
                    'params' => [
                        'scores' => $this->scores,
                    ],
                ],
                'order' => $this->order,
            ],
        ];
    }

    private function getScript(): string
    {
        return sprintf("if(params.scores.containsKey(doc['%s'].value)) { return params.scores[doc['%s'].value];} return 0;", $this->field, $this->field);
    }
}
