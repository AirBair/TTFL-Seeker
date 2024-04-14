<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\NbaPlayer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NbaPlayerCrudController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return NbaPlayer::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['lastName' => 'ASC']);
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setMaxLength(-1),
            TextField::new('lastName'),
            TextField::new('firstName'),
            TextField::new('fullNameInTtfl'),
            TextField::new('position'),
            TextField::new('jersey'),
            AssociationField::new('nbaTeam'),
            BooleanField::new('isInjured')->renderAsSwitch(),
            NumberField::new('averageFantasyPoints'),
            NumberField::new('pastYearFantasyPoints'),
            BooleanField::new('isAllowedInExoticLeague')->renderAsSwitch(),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
