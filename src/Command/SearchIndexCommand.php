<?php

namespace Faibl\ElasticsearchBundle\Command;

use Faibl\ElasticsearchBundle\Services\SearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SearchIndexCommand extends Command
{
    protected static $defaultName = 'fbl:elasticsearch:index';

    private $em;
    private $className;
    private $searchService;
    /** @var SymfonyStyle */
    private $io;

    public function __construct(EntityManagerInterface $em, SearchService $searchService, string $className)
    {
        gc_enable();
        $this->em = $em;
        $this->className = $className;
        $this->searchService = $searchService;
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Elasticsearch indexing operations')
            ->addOption('all', null, InputOption::VALUE_NONE)
            ->addOption('id', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        if ($input->getOption('all')) {
            $this->addAllDocument();
        }
        if ($option = $input->getOption('id')) {
            $this->addSingleDocument((int) $option);
        }
        $this->io->success('done');

        return 1;
    }

    private function addSingleDocument(int $id): void
    {
        $this->io->section(sprintf('Index: add document %s', $id));
        $entity = $this->em->getRepository($this->className)->find($id);
        $response = $this->searchService->indexSingle($entity);
        $this->io->text(json_encode($response));
    }

    private function addAllDocument(): void
    {
        $this->io->section(sprintf('Index: add all documents'));
        $q = $this->em->createQuery(sprintf('SELECT d FROM %s d ORDER BY d.id DESC', $this->className));
        $iterableResult = $q->iterate();
        $batchSize = 100;
        $count = 1;
        $entities = [];
        foreach ($iterableResult as $row) {
            $entities[] = $row[0];
            if (($count % $batchSize) === 0) {
                $this->searchService->indexBulk($entities);
                $this->io->text(sprintf('Documents added: %d. Memory: %s', $count, $this->getMemoryUsage()));
                $entities = [];
                gc_collect_cycles();
                $this->em->clear();
            }
            $count++;
        }
        $this->searchService->indexBulk($entities);
    }

    private function getMemoryUsage(): int
    {
        return memory_get_peak_usage(false) / 1024 / 1024;
    }
}
