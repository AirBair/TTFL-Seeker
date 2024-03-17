<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route(path: '/{vuejsRouting}', name: 'index', requirements: ['vuejsRouting' => '^(?!api|nba-api|admin|security|_(profiler|wdt)).*'])]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'nbaYear' => $_ENV['NBA_YEAR'],
            'isNbaPlayoffs' => $_ENV['NBA_PLAYOFFS'],
        ]);
    }
}
