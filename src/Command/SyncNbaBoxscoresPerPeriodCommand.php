<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncNbaBoxscoresPerPeriodCommand extends Command
{
    protected static $defaultName = 'app:sync-nba-boxscores-per-period';

    protected function configure(): void
    {
        $this
            ->setDescription('Synchronize boxscores (teams & players stats) on a given period from NBA API to the local database.')
            ->addArgument('beginningDay', InputArgument::REQUIRED, 'Beginning day of the games under the format Y-m-d')
            ->addArgument('endingDay', InputArgument::REQUIRED, 'Ending day of the games under the format Y-m-d');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!\is_string($input->getArgument('beginningDay')) || !\is_string($input->getArgument('endingDay'))) {
            return Command::INVALID;
        }

        $currentDay = new \DateTime($input->getArgument('beginningDay'));
        $endingDay = new \DateTime($input->getArgument('endingDay'));

        while ($currentDay <= $endingDay) {
            $this->getApplication()?->find((string) SyncNbaBoxscoresCommand::getDefaultName())->run(new ArrayInput([
                'day' => $currentDay->format('Y-m-d'),
            ]), $output);
            $currentDay->modify('+1 day');
        }

        return Command::SUCCESS;
    }
}
