<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NbaDataSynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncNbaBoxscoresCommand extends Command
{
    protected static $defaultName = 'app:sync-nba-boxscores';

    public function __construct(
        private NbaDataSynchronizer $nbaDataSynchronizer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Synchronize boxscores (teams & players stats) rom NBA API to the local database.')
            ->addArgument('day', InputArgument::OPTIONAL, 'Day of the games under the format Y-m-d (default: yersteday)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $day = (null === $input->getArgument('day')) ? new \DateTime('yesterday') : new \DateTime($input->getArgument('day'));

        $result = $this->nbaDataSynchronizer->synchronizeBoxscores($day);

        $io->success($result['games'].' games boxscores with '.$result['activePlayers'].' active players have been synchronized for the date of '.$day->format('d/m/Y').".\n".'Best fantasy score is '.$result['bestFantasyScore'].' points.');

        return Command::SUCCESS;
    }
}
