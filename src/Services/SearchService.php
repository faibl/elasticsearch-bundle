<?php

namespace Faibl\ElasticsearchBundle\Services;

use Faibl\ElasticsearchBundle\Search\Manager\SearchManager;
use Faibl\ElasticsearchBundle\Util\ArrayUtil;
use Symfony\Component\Serializer\SerializerInterface;

class SearchService
{
    public const HYDRATE_NONE = 'hydrate_none';
    public const HYDRATE_ARRAY = 'hydrate_array';
    public const HYDRATE_OBJECT = 'hydrate_object';
    public const HYDRATE_RESULT = 'hydrate_result';

    private $searchManager;
    private $serializer;
    private $className;

    public function __construct(SearchManager $searchManager, SerializerInterface $serializer, string $className)
    {
        $this->searchManager = $searchManager;
        $this->serializer = $serializer;
        $this->className = $className;
    }

    public function get(int $id, string $hydrateMode = self::HYDRATE_NONE): array
    {
        $searchResult = $this->searchManager->get($id);

        return $this->hydrate($searchResult, $hydrateMode);
    }

    public function find(array $params, string $hydrateMode = self::HYDRATE_NONE): array
    {
        $searchResult = $this->searchManager->search($params);

        return $this->hydrate($searchResult, $hydrateMode);
    }

    public function indexSingle($document, array $options = []): array
    {
        return $this->searchManager->indexSingle($document->getId(), $this->getDocumentBody($document), $options);
    }

    public function removeSingle(int $id): array
    {
        return $this->searchManager->delete($id);
    }

    public function indexBulk(array $entities): array
    {
        $documents = [];
        foreach ($entities as $entity) {
            $documents[$entity->getId()] = $this->getDocumentBody($entity);
        }

        return count($documents) > 0 ? $this->searchManager->indexBulk($documents) : [];
    }

    private function hydrate(array $searchResult, string $hydrateMode): array
    {
        $searchResult = $this->normalizeSearchResult($searchResult);

        switch ($hydrateMode) {
            case self::HYDRATE_OBJECT:
                return $this->hydrateToObjects($searchResult);
            case self::HYDRATE_RESULT:
                return $this->hydrateToResult($searchResult);
            case self::HYDRATE_ARRAY:
                return $this->hydrateToArray($searchResult);
            case self::HYDRATE_NONE:
                return $searchResult;
        }

        return $searchResult;
    }

    private function hydrateToArray(array $searchResult): array
    {
        $items = $this->hydrateToSource($searchResult);

        return [
            'total' => $searchResult['total'],
            'count' => count($items),
            'items' => $items,
        ];
    }

    private function hydrateToResult(array $searchResult): array
    {
        $items = $this->hydrateToObjects($searchResult);

        return [
            'total' => $searchResult['total'],
            'count' => count($items),
            'items' => $items,
        ];
    }

    private function hydrateToObjects(array $searchResult): array
    {
        $documents = array_map(function (array $item) {
            $id = (int) $item['_id'];
            $source = $item['_source'];
            $data = array_merge(['id' => $id], $source);

            return $this->serializer->deserialize(json_encode($data), $this->className, 'json');
        }, $searchResult['hits']);

        return ArrayUtil::filterEmpty($documents);
    }

    private function hydrateToSource(array $searchResult): array
    {
        return array_map(function (array $item) {
            $id = (int) $item['_id'];
            $source = $item['_source'];

            return array_merge(['id' => $id], $source);
        }, $searchResult['hits']);
    }

    private function normalizeSearchResult(array $searchResult): array
    {
        switch (true) {
            case isset($searchResult['hits']):
                $hits = $searchResult['hits']['hits'];
                $total = $searchResult['hits']['total'] ?? count($hits);
                break;
            case isset($searchResult['found']):
                $hits = [$searchResult];
                $total = 1;
                break;
            default:
                $hits = [];
                $total = 0;
        }

        return [
            'total' => $total,
            'hits' => $hits,
        ];
    }

    private function getDocumentBody($document): array
    {
        return $this->serializer->normalize($document, 'json');
    }
}
