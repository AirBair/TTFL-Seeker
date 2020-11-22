<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NbaDataSynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncNbaGamesCommand extends Command
{
    protected static $defaultName = 'app:sync-nba-games';

    /**
     * @var NbaDataSynchronizer
     */
    private $nbaDataSynchronizer;

    public function __construct(NbaDataSynchronizer $nbaDataSynchronizer)
    {
        $this->nbaDataSynchronizer = $nbaDataSynchronizer;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Synchronize games from NBA API to the local database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->nbaDataSynchronizer->synchronizeGames();

        $io->success($result.' games have been synchronized.');

        return Command::SUCCESS;
    }
}
