<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\NbaGame;
use App\Entity\NbaPlayer;
use App\Entity\NbaStatsLog;
use App\Entity\NbaTeam;
use Doctrine\ORM\EntityManagerInterface;

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

    public function __construct(EntityManagerInterface $em, NbaDataProvider $nbaDataProvider, FantasyPointsCalculator $fantasyPointsCalculator)
    {
        $this->em = $em;
        $this->nbaDataProvider = $nbaDataProvider;
        $this->fantasyPointsCalculator = $fantasyPointsCalculator;
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
                ->setTricode($nbaDataTeam['tricode'])
                ->setConference($nbaDataTeam['confName'])
                ->setDivision($nbaDataTeam['divName']);
        }

        $this->em->flush();

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
                ->setPosition($nbaDataPlayer['pos'])
                ->setJersey($nbaDataPlayer['jersey'])
                ->setNbaTeam($this->em->getRepository(NbaTeam::class)->find($nbaDataPlayer['teamId']))
            ;
        }

        $this->em->flush();

        return \count($nbaDataPlayers);
    }

    public function synchronizeRegularSeasonGames(): int
    {
        $nbaDataGames = $this->nbaDataProvider->getRegularSeasonGamesList();

        foreach ($nbaDataGames as $nbaDataGame) {
            $game = $this->em->getRepository(NbaGame::class)->find($nbaDataGame['gameId']);

            if (null === $game) {
                $game = (new NbaGame())->setId($nbaDataGame['gameId']);
                $this->em->persist($game);
            }

            $game
                ->setSeason(intval($_ENV['NBA_YEAR']))
                ->setLocalNbaTeam($this->em->getRepository(NbaTeam::class)->find($nbaDataGame['hTeam']['teamId']))
                ->setVisitorNbaTeam($this->em->getRepository(NbaTeam::class)->find($nbaDataGame['vTeam']['teamId']))
                ->setGameDay(new \DateTime($nbaDataGame['startDateEastern']))
                ->setScheduledAt(new \DateTime($nbaDataGame['startTimeUTC']))
                ->setLocalScore(('' === $nbaDataGame['hTeam']['score']) ? null : (int) $nbaDataGame['hTeam']['score'])
                ->setVisitorScore(('' === $nbaDataGame['vTeam']['score']) ? null : (int) $nbaDataGame['vTeam']['score'])
            ;
        }

        $this->em->flush();

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

        return [
            'games' => \count($nbaGames),
            'activePlayers' => $nbaActivePlayers,
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
}
