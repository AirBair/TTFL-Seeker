<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\NbaDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/nba-api', name: 'nba_api_')]
class NbaApiController extends AbstractController
{
    public function __construct(
        private readonly NbaDataProvider $nbaDataProvider
    ) {}

    #[Route(path: '/teams', name: 'teams')]
    public function teams(): JsonResponse
    {
        return $this->json($this->nbaDataProvider->getTeamsList());
    }

    #[Route(path: '/players', name: 'players')]
    public function players(): JsonResponse
    {
        return $this->json($this->nbaDataProvider->getPlayersList());
    }

    #[Route(path: '/games', name: 'games')]
    public function games(): JsonResponse
    {
        return $this->json($this->nbaDataProvider->getGamesList());
    }

    #[Route(path: '/boxscore/{gameId}', name: 'boxscore')]
    public function boxscore(string $gameId): JsonResponse
    {
        return $this->json($this->nbaDataProvider->gameBoxScore($gameId));
    }
}
