<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\FantasyPick;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class FantasyPickCrudController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return FantasyPick::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['pickedAt' => 'DESC']);
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('season'),
            BooleanField::new('isPlayoffs')->renderAsSwitch(false),
            DateField::new('pickedAt'),
            AssociationField::new('fantasyUser'),
            AssociationField::new('nbaPlayer'),
            IntegerField::new('fantasyPoints'),
            BooleanField::new('isNoPick')->renderAsSwitch(false),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
