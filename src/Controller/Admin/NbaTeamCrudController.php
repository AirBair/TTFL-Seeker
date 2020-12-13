<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\NbaTeam;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

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
            ->disable(Action::NEW, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setMaxLength(-1)->setFormTypeOption('disabled', true),
            TextField::new('city')->setFormTypeOption('disabled', true),
            TextField::new('nickname')->setFormTypeOption('disabled', true),
            TextField::new('tricode')->setFormTypeOption('disabled', true),
            TextField::new('logoFile', 'Logo')->setFormType(VichFileType::class)->onlyOnForms(),
            ImageField::new('logoFileName', 'Logo')->setBasePath('/uploads/nba-teams-logos')->onlyOnIndex(),
            ColorField::new('primaryColor'),
            TextField::new('conference')->setFormTypeOption('disabled', true),
            TextField::new('division')->setFormTypeOption('disabled', true),
            ArrayField::new('nbaPlayers')->onlyOnDetail(),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
