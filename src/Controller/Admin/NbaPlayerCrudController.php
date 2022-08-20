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
    public static function getEntityFqcn(): string
    {
        return NbaPlayer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['lastName' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setMaxLength(-1)->setFormTypeOption('disabled', true),
            TextField::new('lastName')->setFormTypeOption('disabled', true),
            TextField::new('firstName')->setFormTypeOption('disabled', true),
            TextField::new('fullNameInTtfl'),
            TextField::new('position')->setFormTypeOption('disabled', true),
            TextField::new('jersey')->setFormTypeOption('disabled', true),
            AssociationField::new('nbaTeam'),
            BooleanField::new('isInjured')->renderAsSwitch(),
            NumberField::new('averageFantasyPoints')->setFormTypeOption('disabled', true),
            NumberField::new('pastYearFantasyPoints')->setFormTypeOption('disabled', true),
            BooleanField::new('isAllowedInExoticLeague')->renderAsSwitch(),
            DateTimeField::new('updatedAt')->setFormTypeOption('disabled', true),
        ];
    }
}
