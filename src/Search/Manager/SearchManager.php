<?php

namespace Faibl\ElasticsearchBundle\Search\Manager;

use Faibl\ElasticsearchBundle\Services\SearchService;

class SearchManager
{
    private $client;
    private $config;

    public function __construct(SearchClient $searchClient, array $config)
    {
        $this->client = $searchClient;
        $this->config = $config;
    }

    public function get(int $id): array
    {
        $params = [
            'index' => $this->getIndexName(),
            'type' => $this->getDocumentType(),
            'id' => $id,
        ];

        return $this->client->get($params);
    }

    public function search(array $queryParams): array
    {
        $params = [
            'index' => $this->getIndexName(),
            'type' => $this->getDocumentType(),
            'body' => $queryParams,
        ];

        return $this->client->search($params);
    }

    public function delete(int $id): array
    {
        $params = [
            'index' => $this->getIndexName(),
            'type' => $this->getDocumentType(),
            'id' => $id,
        ];

        return $this->client->delete($params);
    }

    public function indexSingle(int $id, array $body): array
    {
        $params = [
            'index' => $this->getIndexName(),
            'type' => $this->getDocumentType(),
            'id' => $id,
            'body' => $body,
            'refresh' => 'wait_for',
        ];

        return $this->client->indexSingle($params);
    }

    public function indexBulk(array $documents): array
    {
        $params = ['body' => []];

        foreach ($documents as $id => $body) {
            $params['body'][] = [
                'index' => [
                    '_index' => $this->getIndexName(),
                    '_type' => $this->getDocumentType(),
                    '_id' => $id,
                ],
            ];

            $params['body'][] = $body;
        }

        return $this->client->indexBulk($params);
    }

    public function createIndex(): array
    {
        $index = $this->getIndexName();
        $params = [
            'index' => $index,
            'body' => [
                'settings' => $this->getSettings(),
                'mappings' => $this->getMapping(),
            ],
        ];

        return $this->client->createIndex($params);
    }

    public function deleteIndex(): array
    {
        return $this->client->deleteIndex(['index' => $this->getIndexName()]);
    }

    public function getIndexSettings(): array
    {
        $params = [
            'index' => $this->getIndexName(),
        ];

        return $this->client->getIndexSettings($params);
    }

    public function updateIndexSettings(): array
    {
        $params = [
            'index' => $this->getIndexName(),
            'body' => [
                'settings' => $this->getSettings(),
                'mappings' => $this->getMapping(),
            ],
        ];

        return $this->client->updateIndexSettings($params);
    }

    public function getIndexMapping(): array
    {
        $params = [
            'index' => $this->getIndexName(),
        ];

        return $this->client->getIndexMapping($params);
    }

    public function updateIndexMapping(): array
    {
        $params = [
            'index' => $this->getIndexName(),
            'type' => $this->getDocumentType(),
            'body' => $this->getMapping(),
        ];

        return $this->client->updateIndexMapping($params);
    }

    public function getClusterHealth(): array
    {
        return $this->client->getClusterHealth();
    }

    public function getIndexName(): string
    {
        return $this->config['index_name'];
    }

    private function getDocumentType(): string
    {
        return $this->config['document_type'];
    }

    private function getSettings(): array
    {
        return $this->config['settings'];
    }

    private function getMapping(): array
    {
        return $this->config['mapping'];
    }
}
