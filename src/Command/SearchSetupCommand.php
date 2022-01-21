<?php

namespace Faibl\ElasticsearchBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Faibl\ElasticsearchBundle\Search\Manager\SearchManager;

class SearchSetupCommand extends Command
{
    protected static $defaultName = 'fbl:elasticsearch:setup';

    private $searchManager;

    /**
     * @var SymfonyStyle
     */
    private $io;

    public function __construct(SearchManager $searchManager)
    {
        $this->searchManager = $searchManager;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this
            ->setDescription('Elasticsearch index management operations')
            ->addOption('create', null, InputOption::VALUE_NONE)
            ->addOption('recreate', null, InputOption::VALUE_NONE)
            ->addOption('update-mapping', null, InputOption::VALUE_NONE)
            ->addOption('update-settings', null, InputOption::VALUE_NONE)
            ->addOption('delete', null, InputOption::VALUE_NONE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        if ($input->getOption('create')) {
            $this->createIndex();
        }
        if ($input->getOption('recreate')) {
            $this->deleteIndex();
            $this->createIndex();
        }
        if ($input->getOption('update-mapping')) {
            $this->updateMapping();
        }
        if ($input->getOption('update-settings')) {
            $this->updateSettings();
        }
        if ($input->getOption('delete')) {
            $this->deleteIndex();
        }
        $this->io->success('done');

        return 1;
    }

    private function createIndex(): void
    {
        $this->io->section(sprintf('Create index %s', $this->searchManager->getIndexName()));
        $response = $this->searchManager->createIndex();
        $this->io->text($response);
    }

    private function updateMapping(): void
    {
        $this->io->section(sprintf('Update mapping for index %s', $this->searchManager->getIndexName()));
        $response = $this->searchManager->updateIndexMapping();
        $this->io->text($response);
    }

    private function updateSettings(): void
    {
        $this->io->section(sprintf('Update settings for index %s', $this->searchManager->getIndexName()));
        $response = $this->searchManager->updateIndexSettings();
        $this->io->text($response);
    }

    private function deleteIndex(): void
    {
        $this->io->section(sprintf('Delete index %s', $this->searchManager->getIndexName()));
        $response = $this->searchManager->deleteIndex();
        $this->io->text($response);
    }
}
