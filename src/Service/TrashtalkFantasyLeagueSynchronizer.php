<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FantasyPick;
use App\Entity\FantasyTeam;
use App\Entity\FantasyTeamRanking;
use App\Entity\FantasyUser;
use App\Entity\FantasyUserRanking;
use App\Entity\NbaPlayer;
use App\Entity\NbaTeam;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class TrashtalkFantasyLeagueSynchronizer
{
    /**
     * @var HttpBrowser
     */
    private $browser;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var int
     */
    private $nbaSeasonYear;

    /**
     * @var bool
     */
    private $isNbaPlayoffs;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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

    public function synchronizeFantasyUsers(): int
    {
        $fantasyUsers = $this->entityManager->getRepository(FantasyUser::class)->findAll();

        foreach ($fantasyUsers as $fantasyUser) {
            $this->synchronizeFantasyUserRankingAndPick($fantasyUser);
        }

        $this->entityManager->flush();

        return \count($fantasyUsers);
    }

    public function synchronizeFantasyUserRankingAndPick(FantasyUser $fantasyUser): void
    {
        $this->getHttpBrowser()->request('GET', 'https://fantasy.trashtalk.co/?tpl=halloffame&ttpl='.$fantasyUser->getTtflId());

        $nbaTeam = $this->entityManager->getRepository(NbaTeam::class)->findOneBy([
            'fullName' => $this->browser->getCrawler()->filter('#decks li:nth-child(1) div.list-maillot div.poptip')->attr('tip'),
        ]);
        $nbaPlayer = $this->entityManager->getRepository(NbaPlayer::class)->findOneBy([
            'nbaTeam' => $nbaTeam,
            'fullName' => $this->browser->getCrawler()->filter('#decks li:nth-child(1) div.media-body h4.media-heading')->getNode(0)->textContent,
        ]);
        preg_match(
            '/Le ([0-9]{2}-[0-9]{2}-[0-9]{4}) pour ([0-9]+) pts/',
            trim($this->browser->getCrawler()->filter('#decks li:nth-child(1) div.media-body small')->getNode(0)->textContent),
            $dateAndPoints
        );
        $pickedAt = \DateTime::createFromFormat('d-m-Y', $dateAndPoints[1]);
        $pickFantasyPoints = (int) $dateAndPoints[2];

        $fantasyPick = $this->entityManager->getRepository(FantasyPick::class)->findUniqueByDate(
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

        $fantasyUserRanking = $this->entityManager->getRepository(FantasyUserRanking::class)->findUniqueByDate(
            $this->nbaSeasonYear,
            $this->isNbaPlayoffs,
            $fantasyUser,
            (new \DateTime())
        ) ?? new FantasyUserRanking();

        $fantasyPoints = (int) $this->browser->getCrawler()->filter('.profile-stat-count')->getNode(0)->textContent;
        $fantasyRank = (int) $this->browser->getCrawler()->filter('.profile-stat-count')->getNode(1)->textContent;

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
        $fantasyTeams = $this->entityManager->getRepository(FantasyTeam::class)->findAll();

        foreach ($fantasyTeams as $fantasyTeam) {
            $this->synchronizeFantasyTeamRanking($fantasyTeam);
        }

        $this->entityManager->flush();

        return \count($fantasyTeams);
    }

    public function synchronizeFantasyTeamRanking(FantasyTeam $fantasyTeam): void
    {
        $this->getHttpBrowser()->request('GET', 'https://fantasy.trashtalk.co/?tpl=equipe&team='.$fantasyTeam->getName());

        $fantasyTeamRanking = $this->entityManager->getRepository(FantasyTeamRanking::class)->findUniqueByDate(
            $this->nbaSeasonYear,
            $this->isNbaPlayoffs,
            $fantasyTeam,
            (new \DateTime())
        ) ?? new FantasyTeamRanking();

        $fantasyPoints = (int) $this->browser->getCrawler()->filter('.profile-stat-count')->getNode(0)->textContent;
        $fantasyRank = (int) $this->browser->getCrawler()->filter('.profile-stat-count')->getNode(1)->textContent;

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
    }
}
