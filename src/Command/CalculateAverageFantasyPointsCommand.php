<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\FantasyPointsCalculator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:calculate-avg-fantasy-points',
    description: 'Calculate average fantasy points of nba players.',
)]
class CalculateAverageFantasyPointsCommand extends Command
{
    public function __construct(
        private readonly FantasyPointsCalculator $fantasyPointsCalculator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('season', InputArgument::OPTIONAL, 'Season year. Default: '.$_ENV['NBA_YEAR'])
            ->addOption('pastYear', null, InputOption::VALUE_OPTIONAL, 'Save as average on past year ?', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (null !== $input->getArgument('season') && !is_numeric($input->getArgument('season'))) {
            return Command::FAILURE;
        }

        $season = (null === $input->getArgument('season')) ? (int) $_ENV['NBA_YEAR'] : (int) $input->getArgument('season');
        $isForPastYear = !\is_bool($input->getOption('pastYear')) || $input->getOption('pastYear');

        $result = $this->fantasyPointsCalculator->calculateNbaPlayersAverageFantasyPoints($season, $isForPastYear);

        $io->success($result.' average of fantasy for nba players have been calculated.');

        return Command::SUCCESS;
    }
}
