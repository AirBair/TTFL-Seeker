<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\FantasyUser;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Service\Attribute\Required;

class FantasyUserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    #[\Override]
    public static function getEntityFqcn(): string
    {
        return FantasyUser::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['username' => 'ASC']);
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username'),
            IntegerField::new('ttflId'),
            AssociationField::new('fantasyTeam'),
            BooleanField::new('isExoticUser')->renderAsSwitch(),
            BooleanField::new('isSynchronizationActive')->renderAsSwitch(),
            IntegerField::new('fantasyRank'),
            IntegerField::new('fantasyPoints'),
            ArrayField::new('roles'),
            DateTimeField::new('registeredAt')->hideOnForm(),
            DateTimeField::new('lastLoginAt')->onlyOnDetail(),
            FormField::addPanel('Change password')->setIcon('fa fa-key')->onlyOnForms(),
            Field::new('plainPassword', 'New password')
                ->onlyOnForms()
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'New password'],
                    'second_options' => ['label' => 'Repeat password'],
                ]),
        ];
    }

    /**
     * @return FormBuilderInterface<FantasyUser>
     */
    #[\Override]
    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);

        $this->addEncodePasswordEventListener($formBuilder);

        return $formBuilder;
    }

    /**
     * @return FormBuilderInterface<FantasyUser>
     */
    #[\Override]
    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);

        $this->addEncodePasswordEventListener($formBuilder);

        return $formBuilder;
    }

    #[Required]
    public function setEncoder(UserPasswordHasherInterface $passwordHasher): void
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param FormBuilderInterface<FantasyUser> $formBuilder
     */
    protected function addEncodePasswordEventListener(FormBuilderInterface $formBuilder): void
    {
        $formBuilder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
            /** @var FantasyUser $user */
            $user = $event->getData();
            if ($user->getPlainPassword()) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
            }
        });
    }
}
