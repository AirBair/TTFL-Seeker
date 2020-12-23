<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\FantasyTeam;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FantasyTeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FantasyTeam::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            BooleanField::new('isExoticTeam')->renderAsSwitch(true),
            IntegerField::new('fantasyRank'),
            IntegerField::new('fantasyPoints'),
            ArrayField::new('fantasyUsers')->hideOnForm(),
        ];
    }
}
