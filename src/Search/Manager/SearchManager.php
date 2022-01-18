<?php

namespace Faibl\ElasticsearchBundle\Search\Manager;

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
            'id' => $id,
        ];

        return $this->client->get($params);
    }

    public function search(array $queryParams): array
    {
        $params = [
            'index' => $this->getIndexName(),
            'body' => $queryParams,
            'track_total_hits' => true,
        ];

        return $this->client->search($params);
    }

    public function delete(int $id): array
    {
        $params = [
            'index' => $this->getIndexName(),
            'id' => $id,
        ];

        return $this->client->delete($params);
    }

    public function indexSingle(int $id, array $body, array $options = []): array
    {
        $params = [
            'index' => $this->getIndexName(),
            'id' => $id,
            'body' => $body,
            'refresh' => $options['refresh'] ?? false,
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

    private function getSettings(): array
    {
        return $this->config['settings'];
    }

    private function getMapping(): array
    {
        return $this->config['mapping'];
    }
}
