<?php

namespace App\Controller\Admin;

use App\Entity\BannerItems;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BannerItemsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BannerItems::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('BannerItems')
            ->setEntityLabelInPlural('banners');
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
            TextField::new('description'),
            NumberField::new('duration'),
            IntegerField::new('orderNumber'),
            AssociationField::new('banner', 'Banner')
                ->setCrudController(BannerCrudController::class),
            ImageField::new('image')
                ->setBasePath(BannerItems::PATH_WEB)
                ->onlyOnIndex(),
        ];

        if (Crud::PAGE_NEW === $pageName) {
            $fields[] = TextField::new('imageFile')
                ->setFormType(VichImageType::class)->onlyOnForms();
        } elseif (Crud::PAGE_EDIT === $pageName || Crud::PAGE_DETAIL === $pageName) {
            $fields[] = TextField::new('imageFile')
                ->setFormType(VichImageType::class)->onlyWhenUpdating();
            $fields[] = ImageField::new('image')
                ->setBasePath(BannerItems::PATH_WEB)
                ->onlyOnDetail();
        }

        return $fields;
    }
}