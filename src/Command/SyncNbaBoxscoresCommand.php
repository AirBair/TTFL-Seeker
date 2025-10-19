<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NbaDataSynchronizer;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-nba-boxscores',
    description: 'Synchronize boxscores (teams & players stats) rom NBA API to the local database.',
)]
final readonly class SyncNbaBoxscoresCommand
{
    public function __construct(
        private NbaDataSynchronizer $nbaDataSynchronizer
    ) {}

    public function __invoke(
        SymfonyStyle $io,
        #[Argument(description: 'Day of the games under the format Y-m-d (default: yesterday)')]
        ?string $day = null,
    ): int {
        $day = (null === $day) ? new \DateTime('yesterday') : new \DateTime($day);
        $result = $this->nbaDataSynchronizer->synchronizeBoxscores($day);

        $io->success($result['games'].' games boxscores with '.$result['activePlayers'].' active players have been synchronized for the date of '.$day->format('d/m/Y').".\n".'Best fantasy score is '.$result['bestFantasyScore'].' points.');

        return Command::SUCCESS;
    }
}
