<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\NbaStatsLog;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class NbaStatsLogCrudController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return NbaStatsLog::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['nbaGame.gameDay' => 'DESC', 'fantasyPoints' => 'DESC']);
    }

    #[\Override]
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('nbaGame'),
            AssociationField::new('nbaPlayer'),
            AssociationField::new('nbaTeam'),
            IntegerField::new('fantasyPoints'),
            BooleanField::new('isBestPick')->renderAsSwitch(false),
            IntegerField::new('points')->onlyOnDetail(),
            IntegerField::new('assists')->onlyOnDetail(),
            IntegerField::new('rebounds')->onlyOnDetail(),
            IntegerField::new('steals')->onlyOnDetail(),
            IntegerField::new('blocks')->onlyOnDetail(),
            IntegerField::new('turnovers')->onlyOnDetail(),
            IntegerField::new('fieldGoals')->onlyOnDetail(),
            IntegerField::new('fieldGoalsAttempts')->onlyOnDetail(),
            IntegerField::new('threePointsFieldGoals')->onlyOnDetail(),
            IntegerField::new('threePointsFieldGoalsAttempts')->onlyOnDetail(),
            IntegerField::new('freeThrows')->onlyOnDetail(),
            IntegerField::new('freeThrowsAttempts')->onlyOnDetail(),
            IntegerField::new('minutesPlayed')->onlyOnDetail(),
            BooleanField::new('hasWon')->onlyOnDetail(),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
