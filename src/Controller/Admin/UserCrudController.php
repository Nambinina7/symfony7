<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(4)
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users');
    }

    public function configureActions(Actions $actions): Actions
    {
        $password_user = Action::new('editpassword', 'Edit password')
            ->linkToRoute('new_user_password', fn (User $user) => ['id' => $user->getId()]);

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $password_user)->setPermission(Action::new('editpassword'), 'ROLE_USER')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('name'),
            TextField::new('position'),
            TextField::new('description'),
            TextField::new('email'),
            ImageField::new('image')
                ->setBasePath(User::PATH_USER)
                ->onlyOnIndex(),
        ];


        if (Crud::PAGE_NEW === $pageName) {
            $additionalFields = [
                TextField::new('password')
                    ->setRequired(true)
                    ->hideOnIndex()
                    ->setFormType(PasswordType::class),
            ];
            $fields = array_merge($fields, $additionalFields);
        }

        if (Crud::PAGE_NEW === $pageName) {
            $fields[] = TextField::new('imageFile')
                ->setFormType(VichImageType::class)->onlyOnForms();
        } elseif (Crud::PAGE_EDIT === $pageName || Crud::PAGE_DETAIL === $pageName) {
            $fields[] = TextField::new('imageFile')
                ->setFormType(VichImageType::class)->onlyWhenUpdating();
            $fields[] = ImageField::new('image')
                ->setBasePath(User::PATH_USER)
                ->onlyOnDetail();
        }

        return $fields;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance->getPassword()) {
            $entityInstance->setPassword($this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword()));
        }

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
