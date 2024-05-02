<?php

namespace App\Controller\Admin;

use App\Entity\Banner;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
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

    private function getAnimationChoices(): array
    {
        $choices = [];
        foreach(Banner::ANIMATION as $i => $animation) {
            $choices[$animation] = $animation;
        }
        return $choices;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('autoPlay')->renderAsSwitch(true),
            ChoiceField::new('animation')->setChoices(fn () => $this->getAnimationChoices()),
            BooleanField::new('indicators')->renderAsSwitch(true),
            IntegerField::new('timeout'),
            BooleanField::new('navButtonsAlwaysVisible')->renderAsSwitch(true),
            BooleanField::new('cycleNavigation')->renderAsSwitch(true),
            IntegerField::new('indexBanner'),
        ];
    }
}
