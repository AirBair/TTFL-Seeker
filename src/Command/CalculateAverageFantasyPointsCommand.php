<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\FantasyPointsCalculator;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:calculate-avg-fantasy-points',
    description: 'Calculate average fantasy points of nba players.',
)]
final readonly class CalculateAverageFantasyPointsCommand
{
    private int $defaultNbaYear;

    public function __construct(
        private FantasyPointsCalculator $fantasyPointsCalculator
    ) {
        if (false === is_numeric($_ENV['NBA_YEAR'])) {
            throw new \InvalidArgumentException('NBA_YEAR environment variable is not set or not numeric.');
        }
        $this->defaultNbaYear = (int) $_ENV['NBA_YEAR'];
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument(description: 'Season year. Default to current NBA year.')]
        ?int $season = null,
        #[Option(description: 'Save as average on past year ?')]
        bool $pastYear = false,
    ): int {
        $season ??= $this->defaultNbaYear;
        $result = $this->fantasyPointsCalculator->calculateNbaPlayersAverageFantasyPoints($season, $pastYear);

        $io->success($result.' average of fantasy for nba players have been calculated.');

        return Command::SUCCESS;
    }
}
