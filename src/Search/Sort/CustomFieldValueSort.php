<?php

namespace Faibl\ElasticsearchBundle\Search\Sort;

class CustomFieldValueSort implements SortInterface
{
    public function __construct(
        private readonly string $field,
        private readonly array $scores,
        private readonly string $order = 'desc'
    ) {
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
        return sprintf("if(params.scores.containsKey(doc['%s'])) { return params.scores[doc['%s']];} return 0;", $this->field, $this->field);
    }
}
