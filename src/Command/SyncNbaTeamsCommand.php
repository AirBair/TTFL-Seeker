<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NbaDataSynchronizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-nba-teams',
    description: 'Synchronize teams from NBA API to the local database.',
)]
class SyncNbaTeamsCommand extends Command
{
    public function __construct(
        private readonly NbaDataSynchronizer $nbaDataSynchronizer
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->nbaDataSynchronizer->synchronizeTeams();

        $io->success($result.' teams have been synchronized.');

        return Command::SUCCESS;
    }
}
