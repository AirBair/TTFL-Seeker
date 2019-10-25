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
    /**
     * @var NbaApiClient
     */
    private $nbaApiClient;

    /**
     * @var int
     */
    private $nbaYear;

    public function __construct()
    {
        $this->nbaApiClient = new NbaApiClient();
        $this->nbaYear = (int) $_ENV['NBA_YEAR'];
    }

    public function getTeamsList(): array
    {
        $request = TeamsRequest::fromArray([
            'year' => $this->nbaYear,
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
            'year' => $this->nbaYear,
        ]);

        try {
            $results = $this->nbaApiClient->request($request)->getArrayFromJson();
        } catch (\Exception $e) {
        }

        return $results['league']['standard'] ?? [];
    }

    public function getRegularSeasonGamesList()
    {
        $request = LeagueScheduleRequest::fromArray([
            'year' => $this->nbaYear,
        ]);

        try {
            $results = $this->nbaApiClient->request($request)->getArrayFromJson();
        } catch (\Exception $e) {
        }

        return array_filter($results['league']['standard'] ?? [], function ($game) {
            return 2 === $game['seasonStageId'];
        });
    }

    public function gameBoxScore(\DateTime $gameDate, string $gameId)
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
