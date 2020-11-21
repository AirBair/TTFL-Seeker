<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\FantasyUser;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FantasyUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FantasyUser::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['username' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username'),
            IntegerField::new('ttflId'),
            AssociationField::new('fantasyTeam'),
            ArrayField::new('roles'),
            DateTimeField::new('registeredAt')->hideOnForm(),
        ];
    }
}
