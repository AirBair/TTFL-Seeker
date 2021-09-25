<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\TrashtalkFantasyLeagueSynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncTrashtalkFantasyUsersCommand extends Command
{
    protected static $defaultName = 'app:sync-ttfl-users';

    public function __construct(
        private TrashtalkFantasyLeagueSynchronizer $trashtalkFantasyLeagueSynchronizer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Synchronize user rankings & picks from the Trashtalk Fantasy League to the local database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->trashtalkFantasyLeagueSynchronizer->synchronizeFantasyUsers();

        $io->success($result.' fantasy users have been synchronized.');

        return Command::SUCCESS;
    }
}
