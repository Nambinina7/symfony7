<?php

namespace App\Controller\Admin;

use App\Entity\Technology;
use App\Services\FieldService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TechnologyCrudController extends AbstractCrudController
{
    public function __construct(private readonly FieldService $fieldService)
    {
    }
    public static function getEntityFqcn(): string
    {
        return Technology::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('Technologie')
            ->setEntityLabelInPlural('Technologies');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('name'),
            $this->fieldService->createImageField('image', Technology::PATH_TECHNOLOGY)
        ];

        return $this->fieldService->configureFields($pageName, Technology::PATH_TECHNOLOGY, $fields);
    }
}
