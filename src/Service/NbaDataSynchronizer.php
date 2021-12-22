<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\NbaGame;
use App\Entity\NbaPlayer;
use App\Entity\NbaStatsLog;
use App\Entity\NbaTeam;
use App\Repository\NbaStatsLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class NbaDataSynchronizer
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private NbaDataProvider $nbaDataProvider,
        private FantasyPointsCalculator $fantasyPointsCalculator,
        private LoggerInterface $synchronizationLogger
    ) {
    }

    public function synchronizeTeams(): int
    {
        $nbaDataTeams = $this->nbaDataProvider->getTeamsList();

        foreach ($nbaDataTeams as $nbaDataTeam) {
            $team = $this->entityManager->getRepository(NbaTeam::class)->find($nbaDataTeam['teamId']);

            if (null === $team) {
                $team = (new NbaTeam())->setId($nbaDataTeam['teamId']);
                $this->entityManager->persist($team);
            }

            $team
                ->setCity($nbaDataTeam['city'])
                ->setNickname($nbaDataTeam['nickname'])
                ->setFullName($nbaDataTeam['city'].' '.$nbaDataTeam['nickname'])
                ->setTricode($nbaDataTeam['tricode'])
                ->setConference($nbaDataTeam['confName'])
                ->setDivision($nbaDataTeam['divName']);
        }

        $this->entityManager->flush();

        $this->synchronizationLogger->info(\count($nbaDataTeams).' NBA Teams have been synchronized');

        return \count($nbaDataTeams);
    }

    public function synchronizePlayers(): int
    {
        $nbaDataPlayers = $this->nbaDataProvider->getPlayersList();

        foreach ($nbaDataPlayers as $nbaDataPlayer) {
            $player = $this->entityManager->getRepository(NbaPlayer::class)->find($nbaDataPlayer['personId']);

            if (null === $player) {
                $player = (new NbaPlayer())
                    ->setId($nbaDataPlayer['personId'])
                    ->setIsInjured(false);
                $this->entityManager->persist($player);
            }

            $player
                ->setLastname($nbaDataPlayer['lastName'])
                ->setFirstname($nbaDataPlayer['firstName'])
                ->setFullName($nbaDataPlayer['firstName'].' '.$nbaDataPlayer['lastName'])
                ->setPosition($nbaDataPlayer['pos'])
                ->setJersey($nbaDataPlayer['jersey'])
                ->setNbaTeam($this->entityManager->getRepository(NbaTeam::class)->find($nbaDataPlayer['teamId']));
        }

        $this->entityManager->flush();

        $this->synchronizationLogger->info(\count($nbaDataPlayers).' NBA Players have been synchronized');

        return \count($nbaDataPlayers);
    }

    public function synchronizeGames(): int
    {
        $nbaDataGames = $this->nbaDataProvider->getGamesList();

        foreach ($nbaDataGames as $nbaDataGame) {
            $game = $this->entityManager->getRepository(NbaGame::class)->find($nbaDataGame['gameId']);

            if (null === $game) {
                $game = (new NbaGame())->setId($nbaDataGame['gameId']);
                $this->entityManager->persist($game);
            }

            $game
                ->setSeason((int) ($_ENV['NBA_YEAR']))
                ->setIsPlayoffs((bool) $_ENV['NBA_PLAYOFFS'])
                ->setLocalNbaTeam($this->entityManager->getRepository(NbaTeam::class)->find($nbaDataGame['hTeam']['teamId']))
                ->setVisitorNbaTeam($this->entityManager->getRepository(NbaTeam::class)->find($nbaDataGame['vTeam']['teamId']))
                ->setGameDay(new \DateTime($nbaDataGame['startDateEastern']))
                ->setScheduledAt(new \DateTime($nbaDataGame['startTimeUTC']))
                ->setLocalScore(('' === $nbaDataGame['hTeam']['score']) ? null : (int) $nbaDataGame['hTeam']['score'])
                ->setVisitorScore(('' === $nbaDataGame['vTeam']['score']) ? null : (int) $nbaDataGame['vTeam']['score']);
        }

        $this->entityManager->flush();

        $this->synchronizationLogger->info(\count($nbaDataGames).' NBA Games have been synchronized');

        return \count($nbaDataGames);
    }

    /**
     * @return array{games: int, activePlayers: int, bestFantasyScore: int}
     */
    public function synchronizeBoxscores(\DateTime $day): array
    {
        $nbaGames = $this->entityManager->getRepository(NbaGame::class)->findBy(['gameDay' => $day]);

        $nbaActivePlayers = 0;

        foreach ($nbaGames as $nbaGame) {
            $nbaActivePlayers += $this->synchronizeGameBoxscore($nbaGame);
        }

        $this->entityManager->flush();

        $bestFantasyScore = $this->markBestPick($day);

        $this->synchronizationLogger->info(\count($nbaGames).' NBA Games boxscores with '.$nbaActivePlayers.' active NBA Players have been synchronized for the date of '.$day->format('d/m/Y').'. Best fantasy score is '.$bestFantasyScore.' points.');

        return [
            'games' => \count($nbaGames),
            'activePlayers' => $nbaActivePlayers,
            'bestFantasyScore' => $bestFantasyScore,
        ];
    }

    public function synchronizeGameBoxscore(NbaGame $nbaGame): int
    {
        if (null === $nbaGame->getGameDay() || null === $nbaGame->getId()) {
            return 0;
        }
        $nbaDataBoxscore = $this->nbaDataProvider->gameBoxScore($nbaGame->getGameDay(), $nbaGame->getId());

        if (null === $nbaDataBoxscore) {
            return 0;
        }

        $nbaGame
            ->setLocalScore((int) $nbaDataBoxscore['hTeam']['totals']['points'])
            ->setVisitorScore((int) $nbaDataBoxscore['vTeam']['totals']['points']);

        $winningTeam = ($nbaGame->getLocalScore() > $nbaGame->getVisitorScore()) ?
            $nbaGame->getLocalNbaTeam() :
            $nbaGame->getVisitorNbaTeam();

        foreach ($nbaDataBoxscore['activePlayers'] as $activePlayer) {
            $nbaPlayer = $this->entityManager->getRepository(NbaPlayer::class)->find($activePlayer['personId']);
            $nbaTeam = $this->entityManager->getRepository(NbaTeam::class)->find($activePlayer['teamId']);

            if (null === $nbaPlayer) {
                continue;
            }

            $nbaStatsLog = $this->entityManager->getRepository(NbaStatsLog::class)->findOneBy([
                'nbaPlayer' => $nbaPlayer,
                'nbaGame' => $nbaGame,
            ]);

            if (null === $nbaStatsLog) {
                $nbaStatsLog = (new NbaStatsLog())
                    ->setNbaPlayer($nbaPlayer)
                    ->setNbaGame($nbaGame)
                    ->setNbaTeam($nbaTeam);

                $this->entityManager->persist($nbaStatsLog);
            }

            $nbaStatsLog
                ->setPoints((int) $activePlayer['points'])
                ->setAssists((int) $activePlayer['assists'])
                ->setRebounds((int) $activePlayer['totReb'])
                ->setSteals((int) $activePlayer['steals'])
                ->setBlocks((int) $activePlayer['blocks'])
                ->setTurnovers((int) $activePlayer['turnovers'])
                ->setFieldGoals((int) $activePlayer['fgm'])
                ->setFieldGoalsAttempts((int) $activePlayer['fga'])
                ->setThreePointsFieldGoals((int) $activePlayer['tpm'])
                ->setThreePointsFieldGoalsAttempts((int) $activePlayer['tpa'])
                ->setFreeThrows((int) $activePlayer['ftm'])
                ->setFreeThrowsAttempts((int) $activePlayer['fta'])
                ->setMinutesPlayed(('' !== $activePlayer['min']) ? (int) (explode(':', $activePlayer['min'])[0]) : 0)
                ->setHasWon((string) $activePlayer['teamId'] === $winningTeam?->getId())
                ->setIsBestPick(false);

            $nbaStatsLog->setFantasyPoints($this->fantasyPointsCalculator->calculatePlayerGameFantasyPoints($nbaStatsLog));
        }

        return \count($nbaDataBoxscore['activePlayers']);
    }

    public function markBestPick(\DateTime $day): int
    {
        /** @var NbaStatsLogRepository $nbaStatsLogRepository */
        $nbaStatsLogRepository = $this->entityManager->getRepository(NbaStatsLog::class);

        $bestFantasyScore = $nbaStatsLogRepository->getBestFantasyScore($day);

        /** @var NbaStatsLog[] $bestPicks */
        $bestPicks = $nbaStatsLogRepository->findByGameDayAndFantasyPoints($day, $bestFantasyScore);
        foreach ($bestPicks as $bestPick) {
            $bestPick->setIsBestPick(true);
        }

        $this->entityManager->flush();

        return $bestFantasyScore;
    }
}
