<?php

namespace App\Controller\Admin;

use App\Entity\BannerItems;
use App\Services\FieldService;
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
    public function __construct(private readonly FieldService $fieldService)
    {
    }
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
            $this->fieldService->createImageField('image', BannerItems::PATH_WEB)
        ];

        return $this->fieldService->configureFields($pageName, BannerItems::PATH_WEB, $fields);
    }
}
