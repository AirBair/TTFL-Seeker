<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\CsvImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CsvImportFantasyRanksCommand extends Command
{
    protected static $defaultName = 'app:csv-import-fantasy-ranks';

    /**
     * @var CsvImporter
     */
    private $csvImporter;

    /**
     * @var string
     */
    private $projectDir;

    public function __construct(CsvImporter $csvImporter, string $projectDir)
    {
        $this->csvImporter = $csvImporter;
        $this->projectDir = $projectDir;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import CSV ranks in the database.')
            ->addArgument('relativeFilePath', InputArgument::REQUIRED, 'File path of the file to import (relative to project director)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $filePath = $this->projectDir.'/'.$input->getArgument('relativeFilePath');

        $result = $this->csvImporter->importRanks($filePath);

        $io->success($result.' lines imported.');

        return Command::SUCCESS;
    }
}
