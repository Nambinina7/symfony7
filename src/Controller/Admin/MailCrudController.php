<?php

namespace App\Controller\Admin;

use App\Entity\Mail;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mail::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('Mail')
            ->setEntityLabelInPlural('Mails')
            ->addFormTheme('@Tinymce/form/tinymce_type.html.twig');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        $parametersFormatted = TextField::new('parametersFormatted', 'Parameters')->onlyOnIndex();
        return [
            TextField::new('name'),
            TextField::new('subject'),
            TextEditorField::new('body')->setFormType(TinymceType::class),
            CollectionField::new('parameters')
                ->setRequired(false)
                ->hideOnIndex()
                ->allowAdd()
                ->allowDelete()
                ->addCssClass('field-parameters')
                ->setEntryType(TextType::class)
                ->setFormTypeOptions([
                    'entry_options' => [
                        'label' => 'Parameter',
                    ],
                ]),
            $parametersFormatted
        ];

    }
}
