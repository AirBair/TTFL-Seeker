<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\NbaGame;
use App\Entity\NbaPlayer;
use App\Entity\NbaStatsLog;
use App\Entity\NbaTeam;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class NbaDataSynchronizer
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var NbaDataProvider
     */
    private $nbaDataProvider;

    /**
     * @var FantasyPointsCalculator
     */
    private $fantasyPointsCalculator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManagerInterface $em, NbaDataProvider $nbaDataProvider, FantasyPointsCalculator $fantasyPointsCalculator, LoggerInterface $synchronizationLogger)
    {
        $this->em = $em;
        $this->nbaDataProvider = $nbaDataProvider;
        $this->fantasyPointsCalculator = $fantasyPointsCalculator;
        $this->logger = $synchronizationLogger;
    }

    public function synchronizeTeams(): int
    {
        $nbaDataTeams = $this->nbaDataProvider->getTeamsList();

        foreach ($nbaDataTeams as $nbaDataTeam) {
            $team = $this->em->getRepository(NbaTeam::class)->find($nbaDataTeam['teamId']);

            if (null === $team) {
                $team = (new NbaTeam())->setId($nbaDataTeam['teamId']);
                $this->em->persist($team);
            }

            $team
                ->setCity($nbaDataTeam['city'])
                ->setNickname($nbaDataTeam['nickname'])
                ->setFullName($nbaDataTeam['city'].' '.$nbaDataTeam['nickname'])
                ->setTricode($nbaDataTeam['tricode'])
                ->setConference($nbaDataTeam['confName'])
                ->setDivision($nbaDataTeam['divName']);
        }

        $this->em->flush();

        $this->logger->info(\count($nbaDataTeams).' NBA Teams have been synchronized');

        return \count($nbaDataTeams);
    }

    public function synchronizePlayers(): int
    {
        $nbaDataPlayers = $this->nbaDataProvider->getPlayersList();

        foreach ($nbaDataPlayers as $nbaDataPlayer) {
            $player = $this->em->getRepository(NbaPlayer::class)->find($nbaDataPlayer['personId']);

            if (null === $player) {
                $player = (new NbaPlayer())
                    ->setId($nbaDataPlayer['personId'])
                    ->setIsInjured(false);
                $this->em->persist($player);
            }

            $player
                ->setLastname($nbaDataPlayer['lastName'])
                ->setFirstname($nbaDataPlayer['firstName'])
                ->setFullName($nbaDataPlayer['firstName'].' '.$nbaDataPlayer['lastName'])
                ->setPosition($nbaDataPlayer['pos'])
                ->setJersey($nbaDataPlayer['jersey'])
                ->setNbaTeam($this->em->getRepository(NbaTeam::class)->find($nbaDataPlayer['teamId']))
            ;
        }

        $this->em->flush();

        $this->logger->info(\count($nbaDataPlayers).' NBA Players have been synchronized');

        return \count($nbaDataPlayers);
    }

    public function synchronizeGames(): int
    {
        $nbaDataGames = $this->nbaDataProvider->getGamesList();

        foreach ($nbaDataGames as $nbaDataGame) {
            $game = $this->em->getRepository(NbaGame::class)->find($nbaDataGame['gameId']);

            if (null === $game) {
                $game = (new NbaGame())->setId($nbaDataGame['gameId']);
                $this->em->persist($game);
            }

            $game
                ->setSeason((int) ($_ENV['NBA_YEAR']))
                ->setIsPlayoffs((bool) $_ENV['NBA_PLAYOFFS'])
                ->setLocalNbaTeam($this->em->getRepository(NbaTeam::class)->find($nbaDataGame['hTeam']['teamId']))
                ->setVisitorNbaTeam($this->em->getRepository(NbaTeam::class)->find($nbaDataGame['vTeam']['teamId']))
                ->setGameDay(new \DateTime($nbaDataGame['startDateEastern']))
                ->setScheduledAt(new \DateTime($nbaDataGame['startTimeUTC']))
                ->setLocalScore(('' === $nbaDataGame['hTeam']['score']) ? null : (int) $nbaDataGame['hTeam']['score'])
                ->setVisitorScore(('' === $nbaDataGame['vTeam']['score']) ? null : (int) $nbaDataGame['vTeam']['score'])
            ;
        }

        $this->em->flush();

        $this->logger->info(\count($nbaDataGames).' NBA Games have been synchronized');

        return \count($nbaDataGames);
    }

    public function synchronizeBoxscores(\DateTime $day): array
    {
        $nbaGames = $this->em->getRepository(NbaGame::class)->findBy(['gameDay' => $day]);

        $nbaActivePlayers = 0;

        foreach ($nbaGames as $nbaGame) {
            $nbaActivePlayers += $this->synchronizeGameBoxscore($nbaGame);
        }

        $this->em->flush();

        $bestFantasyScore = $this->markBestPick($day);

        $this->logger->info(\count($nbaGames).' NBA Games boxscores with '.$nbaActivePlayers.' active NBA Players have been synchronized for the date of '.$day->format('d/m/Y').'. Best fantasy score is '.$bestFantasyScore.' points.');

        return [
            'games' => \count($nbaGames),
            'activePlayers' => $nbaActivePlayers,
            'bestFantasyScore' => $bestFantasyScore,
        ];
    }

    public function synchronizeGameBoxscore(NbaGame $nbaGame): int
    {
        $nbaDataBoxscore = $this->nbaDataProvider->gameBoxScore($nbaGame->getGameDay(), $nbaGame->getId());

        $nbaGame
            ->setLocalScore((int) $nbaDataBoxscore['hTeam']['totals']['points'])
            ->setVisitorScore((int) $nbaDataBoxscore['vTeam']['totals']['points']);

        $winningTeam = ($nbaGame->getLocalScore() > $nbaGame->getVisitorScore()) ?
            $nbaGame->getLocalNbaTeam() :
            $nbaGame->getVisitorNbaTeam()
        ;

        foreach ($nbaDataBoxscore['activePlayers'] as $activePlayer) {
            $nbaPlayer = $this->em->getRepository(NbaPlayer::class)->find($activePlayer['personId']);
            $nbaTeam = $this->em->getRepository(NbaTeam::class)->find($activePlayer['teamId']);

            if (null === $nbaPlayer) {
                continue;
            }

            $nbaStatsLog = $this->em->getRepository(NbaStatsLog::class)->findOneBy([
                'nbaPlayer' => $nbaPlayer,
                'nbaGame' => $nbaGame,
            ]);

            if (null === $nbaStatsLog) {
                $nbaStatsLog = (new NbaStatsLog())
                    ->setNbaPlayer($nbaPlayer)
                    ->setNbaGame($nbaGame)
                    ->setNbaTeam($nbaTeam);

                $this->em->persist($nbaStatsLog);
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
                ->setHasWon((int) $activePlayer['teamId'] === $winningTeam->getId())
                ->setIsBestPick(false);

            $nbaStatsLog->setFantasyPoints($this->fantasyPointsCalculator->calculatePlayerGameFantasyPoints($nbaStatsLog));
        }

        return \count($nbaDataBoxscore['activePlayers']);
    }

    public function markBestPick(\DateTime $day): int
    {
        $nbaStatsLogRepository = $this->em->getRepository(NbaStatsLog::class);

        $bestFantasyScore = $nbaStatsLogRepository->getBestFantasyScore($day);

        /** @var NbaStatsLog[] $bestPicks */
        $bestPicks = $nbaStatsLogRepository->findByGameDayAndFantasyPoints($day, $bestFantasyScore);
        foreach ($bestPicks as $bestPick) {
            $bestPick->setIsBestPick(true);
        }

        $this->em->flush();

        return $bestFantasyScore;
    }
}
