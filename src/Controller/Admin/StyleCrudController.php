<?php

namespace App\Controller\Admin;

use App\Entity\Style;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class StyleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Style::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name','Nom du style')->setFormTypeOptions(["constraints"=>[new Length(['max'=>255]),new NotBlank()]]),
            AssociationField::new('category', 'Catégorie')->setFormTypeOptions(["constraints"=>[new NotBlank()]]),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Style')
        ->setEntityLabelInPlural('Styles')
        ->setDefaultSort(['id' => 'DESC'])
        ->setPaginatorPageSize(1000000);
    }
}
