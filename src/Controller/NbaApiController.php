<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\NbaDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/nba-api", name="nba_api_")
 */
class NbaApiController extends AbstractController
{
    /**
     * @var NbaDataProvider
     */
    private $nbaDataProvider;

    public function __construct(NbaDataProvider $nbaDataProvider)
    {
        $this->nbaDataProvider = $nbaDataProvider;
    }

    /**
     * @Route("/teams", name="teams")
     */
    public function teams(): JsonResponse
    {
        return $this->json($this->nbaDataProvider->getTeamsList());
    }

    /**
     * @Route("/players", name="players")
     */
    public function players(): JsonResponse
    {
        return $this->json($this->nbaDataProvider->getPlayersList());
    }

    /**
     * @Route("/games", name="games")
     */
    public function games(): JsonResponse
    {
        return $this->json($this->nbaDataProvider->getRegularSeasonGamesList());
    }

    /**
     * @Route("/boxscore/{gameDate}/{gameId}", name="boxscore")
     */
    public function boxscore(\DateTime $gameDate, string $gameId): JsonResponse
    {
        return $this->json($this->nbaDataProvider->gameBoxScore($gameDate, $gameId));
    }
}
