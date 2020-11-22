<?php

declare(strict_types=1);

namespace App\Service;

use JasonRoman\NbaApi\Client\Client as NbaApiClient;
use JasonRoman\NbaApi\Request\Data\Prod\Game\BoxscoreRequest;
use JasonRoman\NbaApi\Request\Data\Prod\Roster\LeagueRosterPlayersRequest;
use JasonRoman\NbaApi\Request\Data\Prod\Schedule\LeagueScheduleRequest;
use JasonRoman\NbaApi\Request\Data\Prod\Teams\TeamsRequest;

class NbaDataProvider
{
    const NBA_REGULAR_SEASON_STAGE = 2;
    const NBA_PLAYOFFS_STAGE = 4;

    /**
     * @var NbaApiClient
     */
    private $nbaApiClient;

    /**
     * @var int
     */
    private $nbaSeasonYear;

    /**
     * @var int
     */
    private $nbaSeasonStage;

    public function __construct()
    {
        $this->nbaApiClient = new NbaApiClient();
        $this->nbaSeasonYear = (int) $_ENV['NBA_YEAR'];
        $this->nbaSeasonStage = ($_ENV['NBA_PLAYOFFS']) ? self::NBA_PLAYOFFS_STAGE : self::NBA_REGULAR_SEASON_STAGE;
    }

    public function getTeamsList(): array
    {
        $request = TeamsRequest::fromArray([
            'year' => $this->nbaSeasonYear,
        ]);

        try {
            $results = $this->nbaApiClient->request($request)->getArrayFromJson();
        } catch (\Exception $e) {
        }

        return array_filter($results['league']['standard'] ?? [], function ($team) {
            return true === $team['isNBAFranchise'];
        });
    }

    public function getPlayersList(): array
    {
        $request = LeagueRosterPlayersRequest::fromArray([
            'year' => $this->nbaSeasonYear,
        ]);

        try {
            $results = $this->nbaApiClient->request($request)->getArrayFromJson();
        } catch (\Exception $e) {
        }

        return $results['league']['standard'] ?? [];
    }

    public function getGamesList()
    {
        $request = LeagueScheduleRequest::fromArray([
            'year' => $this->nbaSeasonYear,
        ]);

        try {
            $results = $this->nbaApiClient->request($request)->getArrayFromJson();
        } catch (\Exception $e) {
        }

        return array_filter($results['league']['standard'] ?? [], function ($game) {
            return $this->nbaSeasonStage === $game['seasonStageId'];
        });
    }

    public function gameBoxScore(\DateTimeInterface $gameDate, string $gameId)
    {
        $request = BoxscoreRequest::fromArray([
            'gameDate' => $gameDate,
            'gameId' => $gameId,
        ]);

        try {
            $results = $this->nbaApiClient->request($request)->getArrayFromJson();
        } catch (\Exception $e) {
        }

        return $results['stats'] ?? [];
    }
}
