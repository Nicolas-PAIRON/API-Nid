<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if($pageName === Crud::PAGE_EDIT){
            $required = false;
        }else{
            $required = true;
        }
        
        return [
            TextField::new('name','Nom de la catégorie')->setFormTypeOptions(["constraints"=>[new Length(['max'=>255]),new NotBlank()]]),
            ImageField::new('picture','Image')->setUploadDir('public/pictures')
                                              ->setBasePath('/pictures')
                                              ->setRequired($required)
                                              ->setFormTypeOptions(["constraints"=>[new Length(['max'=>255])]])
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Catégorie')
        ->setEntityLabelInPlural('Catégories')
        ->setDefaultSort(['id' => 'DESC'])
        ->setPaginatorPageSize(1000000);
    }
}
