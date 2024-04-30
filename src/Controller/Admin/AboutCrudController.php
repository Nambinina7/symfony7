<?php

namespace App\Controller\Admin;

use App\Entity\About;
use App\Services\FieldService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AboutCrudController extends AbstractCrudController
{
    public function __construct(private readonly FieldService $fieldService)
    {
    }

    public static function getEntityFqcn(): string
    {
        return About::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('About')
            ->setEntityLabelInPlural('Abouts');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('title'),
            TextEditorField::new('description'),
            $this->fieldService->createImageField('image', About::PATH_ABOUT)
        ];
        return $this->fieldService->configureFields($pageName, About::PATH_ABOUT, $fields);
    }
}
