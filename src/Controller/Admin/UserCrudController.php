<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Services\JWTService;
use App\Services\FieldService;
use App\Services\MailerServices;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly FieldService $fieldService,
        private readonly MailerServices $mailerServices,
        private readonly JWTService $jwtService,
        private readonly string $app_url_front,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('firstName')->formatValue(fn ($value, $entity) => ucwords($value)),
            TextField::new('lastName')->formatValue(fn ($value, $entity) => ucwords($value)),
            TextField::new('position'),
            TextField::new('description'),
            TextField::new('email'),
            $this->fieldService->createImageField('image', User::PATH_USER)
        ];


        if (Crud::PAGE_NEW === $pageName) {
            $additionalFields = [
                TextField::new('password')
                    ->setRequired(true)
                    ->hideOnIndex()
                    ->setFormType(PasswordType::class),
                ChoiceField::new('roles')
                    ->setChoices(array_flip(User::ROLES))
                    ->allowMultipleChoices()
                    ->renderAsBadges(),
            ];
            $fields = array_merge($fields, $additionalFields);
        }

        return $this->fieldService->configureFields($pageName, User::PATH_USER, $fields);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance->getPassword()) {
            $entityInstance->setPassword($this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword()));
        }

        if (in_array("ROLE_EMPLOYER", $entityInstance->getRoles())) {
            $token = $this->jwtService->createToken($entityInstance);

            $resetUrl = "{$this->app_url_front}/change-password?token={$token->toString()}";

            $message = User::HTML_CONTENT_MESSAGE;

            $htmlContent = "<p> $message : <a href=\"$resetUrl\">$resetUrl</a></p>";

            $this->mailerServices->sendEmail(
                $entityInstance->getEmail(),
                'Change password',
                $htmlContent
            );
        }

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

}
