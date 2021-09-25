<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NbaDataSynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncNbaTeamsCommand extends Command
{
    protected static $defaultName = 'app:sync-nba-teams';

    public function __construct(
        private NbaDataSynchronizer $nbaDataSynchronizer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Synchronize teams from NBA API to the local database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->nbaDataSynchronizer->synchronizeTeams();

        $io->success($result.' teams have been synchronized.');

        return Command::SUCCESS;
    }
}
