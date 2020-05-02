<?php

namespace Faibl\ElasticsearchBundle\Search\Manager;

use Monolog\Logger;
use Elasticsearch\ClientBuilder;

class SearchClient
{
    private $logger;
    private $client;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->client = ClientBuilder::create()->build();
    }

    public function get(array $params): array
    {
        return $this->callClient('get', $params);
    }

    public function search(array $params): array
    {
        return $this->callClient('search', $params);
    }

    public function delete(array $params): array
    {
        return $this->callClient('delete', $params);
    }

    public function indexSingle(array $params): array
    {
        return $this->callClient('index', $params);
    }

    public function indexBulk(array $params): array
    {
        return $this->callClient('bulk', $params);
    }

    public function createIndex(array $params): array
    {
        if ($this->client->indices()->exists(['index' => $params['index']])) {
            return ['message' => sprintf('Index "%s" already exists. If you really want to recreate it, use --recreate', $params['index'])];
        }

        return $this->callClientIndices('create', $params);
    }

    public function deleteIndex(array $params): array
    {
        if (!$this->client->indices()->exists(['index' => $params['index']])) {
            return ['message' => sprintf('Index "%s" does not exists.', $params['index'])];
        }

        return $this->callClientIndices('delete', $params);
    }

    public function getIndexSettings(array $params): array
    {
        return $this->callClientIndices('getSettings', $params);
    }

    public function updateIndexSettings(array $params): array
    {
        return $this->callClientIndices('putSettings', $params);
    }

    public function getIndexMapping(array $params): array
    {
        return $this->callClientIndices('getMapping', $params);
    }

    public function updateIndexMapping(array $params): array
    {
        return $this->callClientIndices('putMapping', $params);
    }

    public function getClusterHealth(): array
    {
        return $this->callClientCluster('health');
    }

    private function callClient(string $method, array $params = []): array
    {
        try {
            $result = call_user_func([$this->client, $method], $params);
        } catch (\Exception $e) {
            $this->logger->addError(sprintf('Search:%s errors with message %s', ucfirst($method), $e->getMessage()));
            $result = ['method' => $e->getMessage()];
        }

        return $result;
    }

    private function callClientIndices(string $method, array $params = []): array
    {
        try {
            $result = call_user_func([$this->client->indices(), $method], $params);
        } catch (\Exception $e) {
            $this->logger->addError(sprintf('Search:Indices:%s errors with message %s', ucfirst($method), $e->getMessage()));
            $result = ['method' => $e->getMessage()];
        }

        return $result;
    }

    private function callClientCluster(string $method, array $params = []): array
    {
        try {
            $result = call_user_func([$this->client->cluster(), $method], $params);
        } catch (\Exception $e) {
            $this->logger->addError(sprintf('Search:Cluster:%s errors with message %s', ucfirst($method), $e->getMessage()));
            $result = ['method' => $e->getMessage()];
        }

        return $result;
    }
}
