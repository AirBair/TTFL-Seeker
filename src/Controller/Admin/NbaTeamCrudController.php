<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\NbaTeam;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NbaTeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NbaTeam::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['city' => 'ASC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setMaxLength(-1),
            TextField::new('city'),
            TextField::new('nickname'),
            TextField::new('tricode'),
            TextField::new('conference'),
            TextField::new('division'),
            ArrayField::new('nbaPlayers')->onlyOnDetail(),
            DateTimeField::new('updatedAt'),
        ];
    }
}
