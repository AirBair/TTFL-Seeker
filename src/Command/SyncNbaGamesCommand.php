<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NbaDataSynchronizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-nba-games',
    description: 'Synchronize games from NBA API to the local database.',
)]
final readonly class SyncNbaGamesCommand
{
    public function __construct(
        private NbaDataSynchronizer $nbaDataSynchronizer
    ) {}

    public function __invoke(SymfonyStyle $io): int
    {
        $result = $this->nbaDataSynchronizer->synchronizeGames();

        $io->success($result.' games have been synchronized.');

        return Command::SUCCESS;
    }
}
