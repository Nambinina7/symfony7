<?php

namespace App\Controller\Admin;

use App\Entity\ApiUser;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiUserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }
    public static function getEntityFqcn(): string
    {
        return ApiUser::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('name'),
            TextField::new('username'),
            BooleanField::new('status')->renderAsSwitch(true),
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

        return  $fields;
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
