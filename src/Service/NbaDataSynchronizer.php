<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\NbaGame;
use App\Entity\NbaPlayer;
use App\Entity\NbaStatsLog;
use App\Entity\NbaTeam;
use App\Repository\NbaPlayerRepository;
use App\Repository\NbaStatsLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class NbaDataSynchronizer
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NbaDataProvider $nbaDataProvider,
        private readonly FantasyPointsCalculator $fantasyPointsCalculator,
        private readonly LoggerInterface $synchronizationLogger
    ) {}

    public function synchronizeTeams(): int
    {
        $nbaDataTeams = $this->nbaDataProvider->getTeamsList();

        foreach ($nbaDataTeams as $nbaDataTeam) {
            /** @var ?NbaTeam $team */
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

        $syncBeginAt = new \DateTimeImmutable();

        /** @var NbaPlayerRepository $nbaPlayerRepository */
        $nbaPlayerRepository = $this->entityManager->getRepository(NbaPlayer::class);

        foreach ($nbaDataPlayers as $nbaDataPlayer) {
            /** @var ?NbaPlayer $player */
            $player = $nbaPlayerRepository->find($nbaDataPlayer['personId']);

            if (null === $player) {
                $player = (new NbaPlayer())
                    ->setId($nbaDataPlayer['personId'])
                    ->setIsInjured(false);
                $this->entityManager->persist($player);
            }

            /** @var ?NbaTeam $nbaTeam */
            $nbaTeam = $this->entityManager->getRepository(NbaTeam::class)->find($nbaDataPlayer['teamId']);

            $player
                ->setLastname($nbaDataPlayer['lastName'])
                ->setFirstname($nbaDataPlayer['firstName'])
                ->setFullName($nbaDataPlayer['firstName'].' '.$nbaDataPlayer['lastName'])
                ->setPosition($nbaDataPlayer['pos'])
                ->setJersey($nbaDataPlayer['jersey'])
                ->setNbaTeam($nbaTeam)
                ->setUpdatedAt(new \DateTimeImmutable());

            if (null === $player->getFullNameInTtfl() || '' === $player->getFullNameInTtfl()) {
                $player->setFullNameInTtfl((string) $player->getFullName());
            }
        }

        $this->entityManager->flush();

        $this->synchronizationLogger->info(\count($nbaDataPlayers).' NBA Players have been synchronized');

        $this->synchronizationLogger->info($nbaPlayerRepository->setInactivePlayersAsFreeAgent($syncBeginAt).' NBA Players have been set as Free Agent');

        return \count($nbaDataPlayers);
    }

    public function synchronizeGames(): int
    {
        $nbaDataGames = $this->nbaDataProvider->getGamesList();

        foreach ($nbaDataGames as $nbaDataGame) {
            /** @var ?NbaGame $game */
            $game = $this->entityManager->getRepository(NbaGame::class)->find($nbaDataGame['gameId']);

            if (null === $game) {
                $game = (new NbaGame())->setId($nbaDataGame['gameId']);
                $this->entityManager->persist($game);
            }

            /** @var ?NbaTeam $localNbaTeam */
            $localNbaTeam = $this->entityManager->getRepository(NbaTeam::class)->find($nbaDataGame['hTeam']['teamId']);

            /** @var ?NbaTeam $visitorNbaTeam */
            $visitorNbaTeam = $this->entityManager->getRepository(NbaTeam::class)->find($nbaDataGame['vTeam']['teamId']);

            $game
                ->setSeason((int) $_ENV['NBA_YEAR'])
                ->setIsPlayoffs((bool) $_ENV['NBA_PLAYOFFS'])
                ->setLocalNbaTeam($localNbaTeam)
                ->setVisitorNbaTeam($visitorNbaTeam)
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

        /** @var NbaGame $nbaGame */
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
        if (null === $nbaGame->getId() || null === $nbaGame->getLocalNbaTeam() || null === $nbaGame->getVisitorNbaTeam()) {
            return 0;
        }
        $nbaDataBoxscore = $this->nbaDataProvider->gameBoxScore($nbaGame->getId());

        $nbaGame
            ->setLocalScore((int) $nbaDataBoxscore['game']['homeTeam']['score'])
            ->setVisitorScore((int) $nbaDataBoxscore['game']['awayTeam']['score']);

        $winningTeam = ($nbaGame->getLocalScore() > $nbaGame->getVisitorScore()) ?
            $nbaGame->getLocalNbaTeam() :
            $nbaGame->getVisitorNbaTeam();

        $nbaStatsLogs = [];

        foreach ($nbaDataBoxscore['game']['homeTeam']['players'] as $homePlayer) {
            $nbaStatsLogs[] = $this->synchronizePlayerBoxscore(
                $nbaGame,
                $nbaGame->getLocalNbaTeam(),
                $winningTeam === $nbaGame->getLocalNbaTeam(),
                $homePlayer
            );
        }

        foreach ($nbaDataBoxscore['game']['awayTeam']['players'] as $awayPlayer) {
            $nbaStatsLogs[] = $this->synchronizePlayerBoxscore(
                $nbaGame,
                $nbaGame->getVisitorNbaTeam(),
                $winningTeam === $nbaGame->getVisitorNbaTeam(),
                $awayPlayer
            );
        }

        return \count(array_filter($nbaStatsLogs));
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function synchronizePlayerBoxscore(NbaGame $nbaGame, NbaTeam $nbaTeam, bool $hasWon, array $playerBoxscore): ?NbaStatsLog
    {
        /** @var ?NbaPlayer $nbaPlayer */
        $nbaPlayer = $this->entityManager->getRepository(NbaPlayer::class)->find($playerBoxscore['personId']);

        if (null === $nbaPlayer || 'ACTIVE' !== $playerBoxscore['status']) {
            return null;
        }

        /** @var ?NbaStatsLog $nbaStatsLog */
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

        $statistics = $playerBoxscore['statistics'];

        $nbaStatsLog
            ->setPoints((int) $statistics['points'])
            ->setAssists((int) $statistics['assists'])
            ->setRebounds((int) $statistics['reboundsTotal'])
            ->setSteals((int) $statistics['steals'])
            ->setBlocks((int) $statistics['blocks'])
            ->setTurnovers((int) $statistics['turnovers'])
            ->setFieldGoals((int) $statistics['fieldGoalsMade'])
            ->setFieldGoalsAttempts((int) $statistics['fieldGoalsAttempted'])
            ->setThreePointsFieldGoals((int) $statistics['threePointersMade'])
            ->setThreePointsFieldGoalsAttempts((int) $statistics['threePointersAttempted'])
            ->setFreeThrows((int) $statistics['freeThrowsMade'])
            ->setFreeThrowsAttempts((int) $statistics['freeThrowsAttempted'])
            ->setMinutesPlayed((int) preg_replace('/[^0-9]+/', '', (string) $statistics['minutesCalculated']))
            ->setHasWon($hasWon)
            ->setIsBestPick(false);

        $nbaStatsLog->setFantasyPoints($this->fantasyPointsCalculator->calculatePlayerGameFantasyPoints($nbaStatsLog));

        return $nbaStatsLog;
    }

    public function markBestPick(\DateTime $day): int
    {
        /** @var NbaStatsLogRepository $nbaStatsLogRepository */
        $nbaStatsLogRepository = $this->entityManager->getRepository(NbaStatsLog::class);

        $bestFantasyScore = $nbaStatsLogRepository->getBestFantasyScore($day);

        $bestPicks = $nbaStatsLogRepository->findByGameDayAndFantasyPoints($day, $bestFantasyScore);
        foreach ($bestPicks as $bestPick) {
            $bestPick->setIsBestPick(true);
        }

        $this->entityManager->flush();

        return $bestFantasyScore;
    }
}
