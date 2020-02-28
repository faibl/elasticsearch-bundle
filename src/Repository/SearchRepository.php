<?php

namespace Faibl\ElasticsearchBundle\Repository;

use Faibl\ElasticsearchBundle\Search\Query\QueryInterface;
use Faibl\ElasticsearchBundle\Services\SearchService;

class SearchRepository
{
    private $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function findForId(int $id, $hydrateMode = SearchService::HYDRATE_RESULT): array
    {
        return $this->searchService->get($id, $hydrateMode);
    }

    public function findForQuery(QueryInterface $query, string $hydrateMode = SearchService::HYDRATE_RESULT): array
    {
        return $this->searchService->find($query->getQuery(), $hydrateMode);
    }
}
