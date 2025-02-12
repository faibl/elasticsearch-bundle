<?php

namespace Faibl\ElasticsearchBundle\Repository;

use Faibl\ElasticsearchBundle\Search\Query\QueryInterface;
use Faibl\ElasticsearchBundle\Services\SearchService;

class SearchRepository
{
    public function __construct(
        private readonly SearchService $searchService
    ) {
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
