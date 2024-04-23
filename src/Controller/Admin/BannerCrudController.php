<?php

namespace App\Controller\Admin;

use App\Entity\Banner;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class BannerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Banner::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('Banner')
            ->setEntityLabelInPlural('Banners');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            BooleanField::new('autoPlay')->renderAsSwitch(true),
            Field::new('animation'),
            BooleanField::new('indicators')->renderAsSwitch(true),
            IntegerField::new('timeout'),
            BooleanField::new('navButtonsAlwaysVisible')->renderAsSwitch(true),
            BooleanField::new('cycleNavigation')->renderAsSwitch(true),
            IntegerField::new('indexBanner'),
        ];

        return $fields;
    }
}
