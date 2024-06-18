<?php

namespace App\Controller\Admin;

use App\Entity\Permission;
use App\Entity\User;
use App\Enum\PermissionStatus;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use Symfony\Bundle\SecurityBundle\Security;

class PermissionCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    private function getPermissionStatusChoices(): array
    {
        $choices = [];
        foreach(PermissionStatus::getValues() as $i => $permissionStatus) {
            $choices[$permissionStatus] = $permissionStatus;
        }
        return $choices;
    }

    public static function getEntityFqcn(): string
    {
        return Permission::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('Permission')
            ->setEntityLabelInPlural('Permissions');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('startDate'),
            DateField::new('endDate'),
            TimeField::new('beginningHour'),
            TimeField::new('endTime'),
            TextField::new('naturePermission'),
            ChoiceField::new('status')->setChoices(fn () => $this->getPermissionStatusChoices()),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Permission) {
            $user = $this->security->getUser();
            $entityInstance->setUserPermissions($user);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
