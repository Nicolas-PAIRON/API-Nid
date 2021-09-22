<?php

namespace App\Controller\Admin;

use App\Entity\StatusSite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class StatusSiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StatusSite::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('active', 'Vitrine/e-commerce')->hideOnForm(),
            DateTimeField::new('startDate', 'Date début'),
            DateTimeField::new('endDate', 'Date fin')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Statut du site')
        ->setEntityLabelInPlural('Statut du site')
        ->setDateTimeFormat('dd/MM/y à HH:mm:ss')
        ->setSearchFields(null);
    }
    
    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ->remove(Crud::PAGE_INDEX, Action::NEW)
        ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);
    }
}
