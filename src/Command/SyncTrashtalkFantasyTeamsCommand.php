<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\TrashtalkFantasyLeagueSynchronizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-ttfl-teams',
    description: 'Synchronize team rankings from the Trashtalk Fantasy League to the local database.',
)]
class SyncTrashtalkFantasyTeamsCommand extends Command
{
    public function __construct(
        private readonly TrashtalkFantasyLeagueSynchronizer $trashtalkFantasyLeagueSynchronizer
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function configure(): void
    {
        $this->addArgument('cookie', InputArgument::REQUIRED, 'Active cookie from the TTFL account which will make requests to retrieve data.');
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $cookie = $input->getArgument('cookie');
        $this->trashtalkFantasyLeagueSynchronizer->setCookie(\is_string($cookie) ? $cookie : '');

        $result = $this->trashtalkFantasyLeagueSynchronizer->synchronizeFantasyTeams();

        $io->success($result.' fantasy teams have been synchronized.');

        return Command::SUCCESS;
    }
}
