<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NbaDataSynchronizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-nba-boxscores',
    description: 'Synchronize boxscores (teams & players stats) rom NBA API to the local database.',
)]
class SyncNbaBoxscoresCommand extends Command
{
    public function __construct(
        private readonly NbaDataSynchronizer $nbaDataSynchronizer
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function configure(): void
    {
        $this->addArgument('day', InputArgument::OPTIONAL, 'Day of the games under the format Y-m-d (default: yesterday)');
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (null !== $input->getArgument('day') && !\is_string($input->getArgument('day'))) {
            return Command::FAILURE;
        }

        $day = (null === $input->getArgument('day')) ? new \DateTime('yesterday') : new \DateTime($input->getArgument('day'));

        $result = $this->nbaDataSynchronizer->synchronizeBoxscores($day);

        $io->success($result['games'].' games boxscores with '.$result['activePlayers'].' active players have been synchronized for the date of '.$day->format('d/m/Y').".\n".'Best fantasy score is '.$result['bestFantasyScore'].' points.');

        return Command::SUCCESS;
    }
}
