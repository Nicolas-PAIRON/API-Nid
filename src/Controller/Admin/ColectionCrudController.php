<?php

namespace App\Controller\Admin;

use App\Entity\Colection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ColectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Colection::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if($pageName === Crud::PAGE_EDIT){
            $required = false;
        }else{
            $required = true;
        }
        
        return [
            TextField::new('name','Nom de la collection')->setFormTypeOptions(["constraints"=>[new Length(['max'=>255]),new NotBlank()]]),
            ImageField::new('picture','Image')->setUploadDir('public/pictures')
                                              ->setBasePath('/pictures')
                                              ->setFormTypeOptions(["constraints"=>[new Length(['max'=>255])]])
                                              ->setRequired($required)
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Collection')
        ->setEntityLabelInPlural('Collections')
        ->setDefaultSort(['id' => 'DESC'])
        ->setPaginatorPageSize(1000000);
    }

}
