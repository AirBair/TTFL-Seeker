<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\TrashtalkFantasyLeagueSynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncTrashtalkFantasyTeamsCommand extends Command
{
    protected static $defaultName = 'app:sync-ttfl-teams';

    public function __construct(
        private TrashtalkFantasyLeagueSynchronizer $trashtalkFantasyLeagueSynchronizer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Synchronize team rankings from the Trashtalk Fantasy League to the local database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->trashtalkFantasyLeagueSynchronizer->synchronizeFantasyTeams();

        $io->success($result.' fantasy teams have been synchronized.');

        return Command::SUCCESS;
    }
}
