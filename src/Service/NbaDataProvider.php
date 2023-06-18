<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class NbaDataProvider
{
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
        // TODO, reimplement this method.

        return [];
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
        // TODO: reimplement this method.

        return [];
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
        // TODO: reimplement this method.

        return [];
    }

    /**
     * TODO: Implement this in a better way.
     *
     * @phpstan-ignore-next-line
     */
    public function gameBoxScore(string $gameId): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://cdn.nba.com/static/json/liveData/boxscore/boxscore_'.$gameId.'.json');

        return $response->toArray();
    }
}
