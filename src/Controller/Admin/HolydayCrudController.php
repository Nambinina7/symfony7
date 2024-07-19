<?php

namespace App\Controller\Admin;

use App\Entity\Holyday;
use App\Enum\Status;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

class HolydayCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    private function getHolydayStatusChoices(): array
    {
        $choices = [];
        foreach(Status::getValues() as $i => $holidayStatus) {
            $choices[$holidayStatus] = $holidayStatus;
        }
        return $choices;
    }

    public static function getEntityFqcn(): string
    {
        return Holyday::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('Holiday')
            ->setEntityLabelInPlural('Holidays');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('requestDate'),
            DateField::new('startDate'),
            DateField::new('endDate'),
            TextField::new('leaveReasons'),
            ChoiceField::new('status')->setChoices(fn () => $this->getHolydayStatusChoices()),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Holyday) {
            $user = $this->security->getUser();
            $entityInstance->addUserHolyday($user);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
