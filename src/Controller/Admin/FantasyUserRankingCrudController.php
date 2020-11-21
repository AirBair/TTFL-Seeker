<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\FantasyUserRanking;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class FantasyUserRankingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FantasyUserRanking::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['rankingAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('season'),
            AssociationField::new('fantasyUser'),
            DateField::new('rankingAt'),
            IntegerField::new('fantasyPoints'),
            IntegerField::new('fantasyRank'),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
