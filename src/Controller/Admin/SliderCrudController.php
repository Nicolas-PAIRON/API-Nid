<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Validator\Constraints\Length;

class SliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slider::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        if($pageName === Crud::PAGE_EDIT){
            $required = false;
        }else{
            $required = true;
        }

        return [
            ImageField::new('picture','Image')->setUploadDir('public/pictures')
                                              ->setBasePath('/pictures')
                                              ->setFormTypeOptions(["constraints"=>[new Length(['max'=>255])]])
                                              ->setRequired($required),
            BooleanField::new('active', 'Actif sur le slider')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPaginatorPageSize(1000000)
                    ->setDefaultSort(['id' => 'DESC']);
    }
    
}
