<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\FantasyUserRanking;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class FantasyUserRankingCrudController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return FantasyUserRanking::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['rankingAt' => 'DESC']);
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('season'),
            BooleanField::new('isPlayoffs')->renderAsSwitch(false),
            AssociationField::new('fantasyUser'),
            DateField::new('rankingAt'),
            IntegerField::new('fantasyPoints'),
            IntegerField::new('fantasyRank'),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
