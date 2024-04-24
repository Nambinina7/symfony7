<?php

namespace App\Controller\Admin;

use App\Entity\Technology;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class TechnologyCrudController extends AbstractCrudController
{
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
            ImageField::new('image')
                ->setBasePath(Technology::PATH_TECHNOLOGY)
                ->onlyOnIndex(),
        ];

        if (Crud::PAGE_NEW === $pageName) {
            $fields[] = TextField::new('imageFile')
                ->setFormType(VichImageType::class)->onlyOnForms();
        } elseif (Crud::PAGE_EDIT === $pageName || Crud::PAGE_DETAIL === $pageName) {
            $fields[] = TextField::new('imageFile')
                ->setFormType(VichImageType::class)->onlyWhenUpdating();
            $fields[] = ImageField::new('image')
                ->setBasePath(Technology::PATH_TECHNOLOGY)
                ->onlyOnDetail();
        }

        return $fields;
    }
}
