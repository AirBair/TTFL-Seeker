<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\FantasyPick;
use App\Entity\FantasyTeam;
use App\Entity\FantasyTeamRanking;
use App\Entity\FantasyUser;
use App\Entity\FantasyUserRanking;
use App\Entity\NbaGame;
use App\Entity\NbaPlayer;
use App\Entity\NbaStatsLog;
use App\Entity\NbaTeam;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(NbaTeamCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TTFL Follow-Up')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoRoute('Homepage', 'fa fa-home', 'index', ['vuejsRouting' => '']),
            MenuItem::section('NBA Data'),
            MenuItem::linkToCrud('NBA Teams', 'fa fa-bookmark', NbaTeam::class),
            MenuItem::linkToCrud('NBA Players', 'fa fa-users', NbaPlayer::class),
            MenuItem::linkToCrud('NBA Games', 'fa fa-basketball-ball', NbaGame::class),
            MenuItem::linkToCrud('NBA Stats Logs', 'fa fa-clipboard-list', NbaStatsLog::class),
            MenuItem::section('Fantasy Data'),
            MenuItem::linkToCrud('Fantasy Teams', 'fa fa-bookmark', FantasyTeam::class),
            MenuItem::linkToCrud('Fantasy Users', 'fa fa-users', FantasyUser::class),
            MenuItem::linkToCrud('Fantasy Picks', 'fa fa-crosshairs', FantasyPick::class),
            MenuItem::linkToCrud('Fantasy Team Rankings', 'fa fa-trophy', FantasyTeamRanking::class),
            MenuItem::linkToCrud('Fantasy User Rankings', 'fa fa-medal', FantasyUserRanking::class),
        ];
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setPaginatorPageSize(30)
            ->setDateFormat('dd/MM/yyyy')
            ->setTimeFormat('HH:mm:ss')
            ->setDateTimeFormat('dd/MM/yyyy HH:mm:ss');
    }
}
