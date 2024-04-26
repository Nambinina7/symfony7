<?php

namespace App\Services;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class FieldService
{
    public function createImageField(string $fieldName, string $basePath): ImageField
    {
        return ImageField::new($fieldName)
            ->setBasePath($basePath)
            ->onlyOnIndex();
    }
    public function configureFields(string $pageName, $Path, array $fields): array
    {
        if (Crud::PAGE_NEW === $pageName) {
            $fields[] = TextField::new('imageFile')
                ->setFormType(VichImageType::class)->onlyOnForms();
        } elseif (Crud::PAGE_EDIT === $pageName || Crud::PAGE_DETAIL === $pageName) {
            $fields[] = TextField::new('imageFile')
                ->setFormType(VichImageType::class)->onlyWhenUpdating();
            $fields[] = ImageField::new('image')
                ->setBasePath($Path)
                ->onlyOnDetail();
        }

        return $fields;
    }
}
