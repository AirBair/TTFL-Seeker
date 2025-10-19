<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-nba-boxscores-per-period',
    description: 'Synchronize boxscores (teams & players stats) on a given period from NBA API to the local database.',
)]
final readonly class SyncNbaBoxscoresPerPeriodCommand
{
    public function __construct(
        private SyncNbaBoxscoresCommand $syncNbaBoxscoresCommand,
    ) {}

    public function __invoke(
        SymfonyStyle $io,
        #[Argument(description: 'Beginning day of the games under the format Y-m-d')]
        string $beginningDay,
        #[Argument(description: 'Ending day of the games under the format Y-m-d')]
        string $endingDay,
    ): int {
        $currentDay = new \DateTime($beginningDay);
        $endingDay = new \DateTime($endingDay);
        while ($currentDay <= $endingDay) {
            $this->syncNbaBoxscoresCommand->__invoke($io, $currentDay->format('Y-m-d'));
            $currentDay->modify('+1 day');
        }

        return Command::SUCCESS;
    }
}
