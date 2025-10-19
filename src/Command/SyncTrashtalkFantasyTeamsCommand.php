<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\TrashtalkFantasyLeagueSynchronizer;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-ttfl-teams',
    description: 'Synchronize team rankings from the Trashtalk Fantasy League to the local database.',
)]
final readonly class SyncTrashtalkFantasyTeamsCommand
{
    public function __construct(
        private TrashtalkFantasyLeagueSynchronizer $trashtalkFantasyLeagueSynchronizer
    ) {}

    public function __invoke(
        SymfonyStyle $io,
        #[Argument(description: 'Active cookie from the TTFL account which will make requests to retrieve data.')]
        string $cookie,
    ): int {
        $this->trashtalkFantasyLeagueSynchronizer->setCookie($cookie);
        $result = $this->trashtalkFantasyLeagueSynchronizer->synchronizeFantasyTeams();

        $io->success($result.' fantasy teams have been synchronized.');

        return Command::SUCCESS;
    }
}
