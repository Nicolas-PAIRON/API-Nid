<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        if($pageName === Crud::PAGE_EDIT){
            $required = false;
        }else{
            $required = true;
        }
        return [

            TextField::new('name', 'Nom du produit')->setFormTypeOptions(["constraints"=>[new Length(['max'=>255]),new NotBlank()]]),
            IntegerField::new('liked','Likes')->setFormTypeOptions(["constraints"=>[new Range(['max'=>32767])]]),
            DateTimeField::new('createdAt','Date')->hideOnForm(),
            IntegerField::new('stock', 'Stock')->setFormTypeOptions(["constraints"=>[new Range(['max'=>32767])]]),
            AssociationField::new('category', 'Catégorie'),
            AssociationField::new('colection', 'Collection'),
            AssociationField::new('style', 'Style'),
            NumberField::new('price', 'Prix')->setFormTypeOptions(["constraints"=>[new Positive()]]),
            ImageField::new('picture1', 'Photo n°1')->setUploadDir('public/pictures')
            ->setBasePath('/pictures')
            ->setFormTypeOptions(["constraints"=>[new Length(['max'=>255])]])
            ->setRequired($required),
            ImageField::new('picture2', 'Photo n°2')->setUploadDir('public/pictures')
            ->setBasePath('/pictures')
            ->setFormTypeOptions(["constraints"=>[new Length(['max'=>255])]]),
            ImageField::new('picture3', 'Photo n°3')->setUploadDir('public/pictures')
            ->setBasePath('/pictures')
            ->setFormTypeOptions(["constraints"=>[new Length(['max'=>255])]]),
            TextareaField::new('description', 'Description')->setFormTypeOptions(["constraints"=>[new Length(['max'=>8000])]]),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setDefaultSort(['id' => 'DESC'])
            ->setDateTimeFormat('dd/MM/y')
            ->setPaginatorPageSize(1000000);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setUpdatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        parent::updateEntity($entityManager, $entityInstance);
    }
}
