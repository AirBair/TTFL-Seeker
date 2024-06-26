<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\NbaTeam;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class NbaTeamCrudController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return NbaTeam::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['city' => 'ASC']);
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
            TextField::new('fullName'),
            TextField::new('city'),
            TextField::new('nickname'),
            TextField::new('tricode'),
            TextField::new('logoFile', 'Logo')->setFormType(VichFileType::class)->onlyOnForms(),
            ImageField::new('logoFileName', 'Logo')->setBasePath('/uploads/nba-teams-logos')->onlyOnIndex(),
            ColorField::new('primaryColor'),
            TextField::new('conference'),
            TextField::new('division'),
            AssociationField::new('nbaPlayers')->onlyOnForms()->setFormTypeOption('by_reference', false),
            ArrayField::new('nbaPlayers')->onlyOnDetail(),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
