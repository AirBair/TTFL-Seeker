<?php

declare(strict_types=1);

namespace App\Service;

use JasonRoman\NbaApi\Client\Client as NbaApiClient;
use JasonRoman\NbaApi\Request\Data\Prod\Game\BoxscoreRequest;
use JasonRoman\NbaApi\Request\Data\Prod\Roster\LeagueRosterPlayersRequest;
use JasonRoman\NbaApi\Request\Data\Prod\Schedule\LeagueScheduleRequest;
use JasonRoman\NbaApi\Request\Data\Prod\Teams\TeamsRequest;
use Symfony\Component\HttpClient\HttpClient;

class NbaDataProvider
{
    public const NBA_REGULAR_SEASON_STAGE = 2;
    public const NBA_PLAYOFFS_STAGE = 4;

    private NbaApiClient $nbaApiClient;
    private int $nbaSeasonYear;
    private int $nbaSeasonStage;

    public function __construct()
    {
        $this->nbaApiClient = new NbaApiClient();
        $this->nbaSeasonYear = (int) $_ENV['NBA_YEAR'];
        $this->nbaSeasonStage = ($_ENV['NBA_PLAYOFFS']) ? self::NBA_PLAYOFFS_STAGE : self::NBA_REGULAR_SEASON_STAGE;
    }

    /**
     * @return array<array{
     *     city: string,
     *     confName: string,
     *     tricode: string,
     *     divName: string,
     *     nickname: string,
     *     teamId: string,
     * }>
     */
    public function getTeamsList(): array
    {
        $request = new TeamsRequest();
        $request->year = $this->nbaSeasonYear;

        try {
            $results = $this->nbaApiClient->request($request)->getArrayFromJson();
        } catch (\Exception) {
        }

        return array_filter($results['league']['standard'] ?? [], fn ($team) => true === $team['isNBAFranchise']) ?? [];
    }

    /**
     * @return array<array{
     *     firstName: string,
     *     lastName: string,
     *     personId: string,
     *     teamId: string,
     *     jersey: string,
     *     pos: string
     * }>
     */
    public function getPlayersList(): array
    {
        $request = new LeagueRosterPlayersRequest();
        $request->year = $this->nbaSeasonYear;

        try {
            $results = $this->nbaApiClient->request($request)->getArrayFromJson();
        } catch (\Exception) {
        }

        return $results['league']['standard'] ?? [];
    }

    /**
     * @return array<array{
     *     gameId: string,
     *     startDateEastern: string,
     *     startTimeUTC: string,
     *     hTeam: array{teamId: string, score: string},
     *     vTeam: array{teamId: string, score: string}
     * }>
     */
    public function getGamesList(): array
    {
        $request = new LeagueScheduleRequest();
        $request->year = $this->nbaSeasonYear;

        try {
            $results = $this->nbaApiClient->request($request)->getArrayFromJson();
        } catch (\Exception) {
        }

        return array_filter($results['league']['standard'] ?? [], fn ($game) => $this->nbaSeasonStage === $game['seasonStageId']);
    }

    /**
     * @return null|array{
     *     vTeam: array{
     *         totals: array{
     *             points: string
     *         }
     *      },
     *     hTeam: array{
     *         totals: array{
     *             points: string
     *         }
     *      },
     *     activePlayers: array<array{
     *         personId: string,
     *         teamId: string,
     *         points: string,
     *         min: string,
     *         fgm: string,
     *         fga: string,
     *         tpm: string,
     *         tpa: string,
     *         ftm: string,
     *         fta: string,
     *         totReb: string,
     *         assists: string,
     *         steals: string,
     *         turnovers: string,
     *         blocks: string
     *     }>
     * }
     */
    public function gameBoxScore(\DateTimeInterface $gameDate, string $gameId): ?array
    {
        $request = new BoxscoreRequest();
        $request->gameDate = \DateTime::createFromInterface($gameDate);
        $request->gameId = $gameId;

        try {
            $results = $this->nbaApiClient->request($request)->getArrayFromJson();
        } catch (\Exception) {
        }

        return $results['stats'] ?? null;
    }

    public function alternateGameBoxScore(string $gameId): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://cdn.nba.com/static/json/liveData/boxscore/boxscore_'.$gameId.'.json');

        return $response->toArray();
    }
}
