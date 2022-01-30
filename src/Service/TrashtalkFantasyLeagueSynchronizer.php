<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FantasyPick;
use App\Entity\FantasyTeam;
use App\Entity\FantasyTeamRanking;
use App\Entity\FantasyUser;
use App\Entity\FantasyUserRanking;
use App\Entity\NbaGame;
use App\Entity\NbaPlayer;
use App\Entity\NbaTeam;
use App\Repository\FantasyPickRepository;
use App\Repository\FantasyTeamRankingRepository;
use App\Repository\FantasyUserRankingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class TrashtalkFantasyLeagueSynchronizer
{
    private ?HttpBrowser $browser = null;
    private int $nbaSeasonYear;
    private bool $isNbaPlayoffs;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $synchronizationLogger
    ) {
        $this->nbaSeasonYear = (int) $_ENV['NBA_YEAR'];
        $this->isNbaPlayoffs = (bool) $_ENV['NBA_PLAYOFFS'];
    }

    public function getHttpBrowser(): HttpBrowser
    {
        if (null === $this->browser) {
            $this->browser = new HttpBrowser(HttpClient::create());
            $this->browser->request('GET', 'https://fantasy.trashtalk.co/login/');
            $this->browser->submitForm('Se connecter', [
                'email' => $_ENV['TTFL_USERNAME'],
                'password' => $_ENV['TTFL_PASSWORD'],
            ]);
        }

        return $this->browser;
    }

    public function wasAnActiveNight(): bool
    {
        $nbaGames = $this->entityManager->getRepository(NbaGame::class)->findBy(['gameDay' => new \DateTime('yesterday')]);

        return \count($nbaGames) > 0;
    }

    public function synchronizeFantasyUsers(): int
    {
        if (false === $this->wasAnActiveNight()) {
            return 0;
        }

        $fantasyUsers = $this->entityManager->getRepository(FantasyUser::class)->findBy([
            'fantasyTeam' => null,
            'isSynchronizationActive' => true,
        ]);

        /** @var FantasyUser $fantasyUser */
        foreach ($fantasyUsers as $fantasyUser) {
            $this->synchronizeFantasyUserRankingAndPick($fantasyUser);
        }

        $this->entityManager->flush();

        $this->synchronizationLogger->info(\count($fantasyUsers).' Fantasy Users (Free Agent) have been synchronized.');

        return \count($fantasyUsers);
    }

    public function synchronizeFantasyUserRankingAndPick(FantasyUser $fantasyUser): void
    {
        $this->getHttpBrowser()->request('GET', 'https://fantasy.trashtalk.co/?tpl=halloffame&ttpl='.$fantasyUser->getTtflId());
        $crawler = $this->browser?->getCrawler();
        if (!$crawler instanceof Crawler) {
            return;
        }

        /** @var ?NbaTeam $nbaTeam */
        $nbaTeam = $this->entityManager->getRepository(NbaTeam::class)->findOneBy([
            'fullName' => $crawler->filter('#decks li:nth-child(1) div.list-maillot div.poptip')->attr('tip'),
        ]);

        /** @var ?NbaPlayer $nbaPlayer */
        $nbaPlayer = $this->entityManager->getRepository(NbaPlayer::class)->findOneBy([
            'nbaTeam' => $nbaTeam,
            'fullName' => $crawler->filter('#decks li:nth-child(1) div.media-body h4.media-heading')->getNode(0)?->textContent,
        ]);
        preg_match(
            '/Le ([0-9]{2}-[0-9]{2}-[0-9]{4}) pour ([0-9]+) pts/',
            trim((string) $crawler->filter('#decks li:nth-child(1) div.media-body small')->getNode(0)?->textContent),
            $dateAndPoints
        );
        $pickedAt = \DateTime::createFromFormat('d-m-Y', $dateAndPoints[1]);
        if (false === $pickedAt) {
            return;
        }
        $pickFantasyPoints = (int) $dateAndPoints[2];

        /** @var FantasyPickRepository $fantasyPickRepository */
        $fantasyPickRepository = $this->entityManager->getRepository(FantasyPick::class);
        $fantasyPick = $fantasyPickRepository->findUniqueByDate(
            $this->nbaSeasonYear,
            $this->isNbaPlayoffs,
            $fantasyUser,
            $pickedAt
        ) ?? new FantasyPick();

        $fantasyPick
            ->setSeason($this->nbaSeasonYear)
            ->setIsPlayoffs($this->isNbaPlayoffs)
            ->setFantasyUser($fantasyUser)
            ->setPickedAt($pickedAt)
            ->setFantasyPoints($pickFantasyPoints)
            ->setNbaPlayer($nbaPlayer);

        if (null === $fantasyPick->getId()) {
            $this->entityManager->persist($fantasyPick);
        }

        /** @var FantasyUserRankingRepository $fantasyUserRankingRepository */
        $fantasyUserRankingRepository = $this->entityManager->getRepository(FantasyUserRanking::class);
        $fantasyUserRanking = $fantasyUserRankingRepository->findUniqueByDate(
            $this->nbaSeasonYear,
            $this->isNbaPlayoffs,
            $fantasyUser,
            (new \DateTime())
        ) ?? new FantasyUserRanking();

        $fantasyPoints = (int) $crawler->filter('.profile-stat-count')->getNode(0)?->textContent;
        $fantasyRank = (int) $crawler->filter('.profile-stat-count')->getNode(1)?->textContent;

        $fantasyUserRanking
            ->setSeason($this->nbaSeasonYear)
            ->setIsPlayoffs($this->isNbaPlayoffs)
            ->setFantasyUser($fantasyUser)
            ->setRankingAt(new \DateTime())
            ->setFantasyPoints($fantasyPoints)
            ->setFantasyRank($fantasyRank);

        if (null === $fantasyUserRanking->getId()) {
            $this->entityManager->persist($fantasyUserRanking);
        }

        $fantasyUser
            ->setFantasyPoints($fantasyPoints)
            ->setFantasyRank($fantasyRank);
    }

    public function synchronizeFantasyTeams(): int
    {
        if (false === $this->wasAnActiveNight()) {
            return 0;
        }

        $fantasyTeams = $this->entityManager->getRepository(FantasyTeam::class)->findBy([
            'isSynchronizationActive' => true,
        ]);

        /** @var FantasyTeam $fantasyTeam */
        foreach ($fantasyTeams as $fantasyTeam) {
            $this->synchronizeFantasyTeamRanking($fantasyTeam);
        }

        $this->entityManager->flush();

        $this->synchronizationLogger->info(\count($fantasyTeams).' Fantasy Teams have been synchronized.');

        return \count($fantasyTeams);
    }

    public function synchronizeFantasyTeamRanking(FantasyTeam $fantasyTeam): void
    {
        $this->getHttpBrowser()->request('GET', 'https://fantasy.trashtalk.co/?tpl=equipe&team='.urlencode((string) $fantasyTeam->getName()));
        $crawler = $this->browser?->getCrawler();
        if (!$crawler instanceof Crawler) {
            return;
        }

        /** @var FantasyTeamRankingRepository $fantasyTeamRankingRepository */
        $fantasyTeamRankingRepository = $this->entityManager->getRepository(FantasyTeamRanking::class);
        $fantasyTeamRanking = $fantasyTeamRankingRepository->findUniqueByDate(
            $this->nbaSeasonYear,
            $this->isNbaPlayoffs,
            $fantasyTeam,
            (new \DateTime())
        ) ?? new FantasyTeamRanking();

        $fantasyPoints = (int) $crawler->filter('.profile-stat-count')->getNode(0)?->textContent;
        $fantasyRank = (int) $crawler->filter('.profile-stat-count')->getNode(1)?->textContent;

        $fantasyTeamRanking
            ->setSeason((int) $_ENV['NBA_YEAR'])
            ->setIsPlayoffs((bool) $_ENV['NBA_PLAYOFFS'])
            ->setFantasyTeam($fantasyTeam)
            ->setRankingAt(new \DateTime())
            ->setFantasyPoints($fantasyPoints)
            ->setFantasyRank($fantasyRank);

        if (null === $fantasyTeamRanking->getId()) {
            $this->entityManager->persist($fantasyTeamRanking);
        }

        $fantasyTeam
            ->setFantasyPoints($fantasyPoints)
            ->setFantasyRank($fantasyRank);

        for ($playerIndex = 1; $playerIndex <= $crawler->filter('#decks tbody tr')->count(); ++$playerIndex) {
            $this->synchronizeFantasyTeamUserRanking($crawler, $playerIndex, $fantasyTeam);
        }
    }

    public function synchronizeFantasyTeamUserRanking(Crawler $crawler, int $playerIndex, FantasyTeam $fantasyTeam): void
    {
        $username = (string) $crawler->filter('#decks tbody tr:nth-child('.$playerIndex.') td:nth-child(2) b a')->getNode(0)?->textContent;
        $fantasyRank = (int) $crawler->filter('#decks tbody tr:nth-child('.$playerIndex.') td:nth-child(1)')->getNode(0)?->textContent;
        $fantasyPoints = (int) $crawler->filter('#decks tbody tr:nth-child('.$playerIndex.') td:nth-child(3)')->getNode(0)?->textContent;
        preg_match(
            '/(.*) \((.*) pts\)/',
            trim((string) $crawler->filter('#decks tbody tr:nth-child('.$playerIndex.') td:nth-child(5)')->getNode(0)?->textContent),
            $pick
        );
        $pickName = isset($pick[1]) ? trim($pick[1]) : null;
        $pickFantasyPoints = isset($pick[2]) ? (int) $pick[2] : null;
        $pickedAt = new \DateTime('yesterday');

        /** @var ?FantasyUser $fantasyUser */
        $fantasyUser = $this->entityManager->getRepository(FantasyUser::class)->findOneBy([
            'username' => $username,
        ]);
        if (null === $fantasyUser) {
            $ttflId = (int) str_replace(
                '/?tpl=halloffame&ttpl=',
                '',
                (string) $crawler->filter('#decks tbody tr:nth-child('.$playerIndex.') td:nth-child(2) b a')->attr('href')
            );
            $fantasyUser = (new FantasyUser())
                ->setUsername($username)
                ->setTtflId($ttflId)
                ->setFantasyTeam($fantasyTeam)
                ->setIsExoticUser($fantasyTeam->getIsExoticTeam());

            $this->entityManager->persist($fantasyUser);
        }

        $fantasyUserRanking = null;
        if ($fantasyUser->getId()) {
            /** @var FantasyUserRankingRepository $fantasyUserRankingRepository */
            $fantasyUserRankingRepository = $this->entityManager->getRepository(FantasyUserRanking::class);
            $fantasyUserRanking = $fantasyUserRankingRepository->findUniqueByDate(
                $this->nbaSeasonYear,
                $this->isNbaPlayoffs,
                $fantasyUser,
                (new \DateTime())
            );
        }
        if (null === $fantasyUserRanking) {
            $fantasyUserRanking = new FantasyUserRanking();
        }

        $fantasyUserRanking
            ->setSeason($this->nbaSeasonYear)
            ->setIsPlayoffs($this->isNbaPlayoffs)
            ->setFantasyUser($fantasyUser)
            ->setRankingAt(new \DateTime())
            ->setFantasyPoints($fantasyPoints)
            ->setFantasyRank($fantasyRank);

        if (null === $fantasyUserRanking->getId()) {
            $this->entityManager->persist($fantasyUserRanking);
        }

        $fantasyUser
            ->setFantasyPoints($fantasyPoints)
            ->setFantasyRank($fantasyRank);

        if (null !== $pickName && null !== $pickFantasyPoints) {
            /** @var ?NbaPlayer $nbaPlayer */
            $nbaPlayer = $this->entityManager->getRepository(NbaPlayer::class)->findOneBy([
                'fullName' => $pickName,
            ]);

            if (null === $nbaPlayer) {
                $this->synchronizationLogger->info('Nba Player "'.$pickName.'" cannot be found for Fantasy User "'.$fantasyUser->getUsername().'"');

                return;
            }
        } else {
            $nbaPlayer = null;
        }

        $fantasyPick = null;
        if ($fantasyUser->getId()) {
            /** @var FantasyPickRepository $fantasyPickRepository */
            $fantasyPickRepository = $this->entityManager->getRepository(FantasyPick::class);
            $fantasyPick = $fantasyPickRepository->findUniqueByDate(
                $this->nbaSeasonYear,
                $this->isNbaPlayoffs,
                $fantasyUser,
                $pickedAt
            );
        }
        if (null === $fantasyPick) {
            $fantasyPick = new FantasyPick();
        }

        $fantasyPick
            ->setSeason($this->nbaSeasonYear)
            ->setIsPlayoffs($this->isNbaPlayoffs)
            ->setFantasyUser($fantasyUser)
            ->setPickedAt($pickedAt)
            ->setFantasyPoints($pickFantasyPoints ?? 0)
            ->setNbaPlayer($nbaPlayer)
            ->setIsNoPick(null === $nbaPlayer);

        if (null === $fantasyPick->getId()) {
            $this->entityManager->persist($fantasyPick);
        }
    }
}
