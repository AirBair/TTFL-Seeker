<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\NbaGame;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class NbaGameCrudController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return NbaGame::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['gameDay' => 'DESC']);
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
            IdField::new('id')->setMaxLength(-1),
            IntegerField::new('season'),
            BooleanField::new('isPlayoffs')->renderAsSwitch(false),
            DateField::new('gameDay'),
            AssociationField::new('localNbaTeam'),
            IntegerField::new('localScore'),
            AssociationField::new('visitorNbaTeam'),
            IntegerField::new('visitorScore'),
            DateTimeField::new('scheduledAt'),
            ArrayField::new('nbaStatsLogs')->onlyOnDetail(),
            DateTimeField::new('updatedAt'),
        ];
    }
}
