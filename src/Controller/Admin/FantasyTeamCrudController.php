<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\FantasyTeam;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @template-extends AbstractCrudController<FantasyTeam>
 */
class FantasyTeamCrudController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return FantasyTeam::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC']);
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            BooleanField::new('isExoticTeam')->renderAsSwitch(),
            BooleanField::new('isSynchronizationActive')->renderAsSwitch(),
            IntegerField::new('fantasyRank'),
            IntegerField::new('fantasyPoints'),
            AssociationField::new('fantasyUsers')->onlyOnForms()->setFormTypeOption('by_reference', false),
            ArrayField::new('fantasyUsers')->hideOnForm(),
        ];
    }
}
