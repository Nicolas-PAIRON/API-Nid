<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Repository\OrderRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class OrderCrudController extends AbstractCrudController
{
    private $order;
    
    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }
    
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $orders = $this->order->findAll();
        

        foreach($orders as $order){

            $textLines ='';
            $total=0;

            $orderLines = $order->getOrderLines();

            foreach($orderLines as $orderLine){
                $textLine = 'Produit: <i>'.$orderLine->getLabelProduct().'</i> | Quantité: '.$orderLine->getQuantity().' x  Prix unitaire: '.$orderLine->getPriceProduct().' euros'.'<br>';
                $textLines .= $textLine;
                $total += $orderLine->getPriceProduct() * $orderLine->getQuantity();
            }
            $textLines .= '<br><b>Total= '.$total.' euros</b>';

            $details = '<b>Commande n° '.$order->getId().'</b> de '.$order->getUser()->getFirstname().' '.$order->getUser()->getLastname().'<br><br>';
            $details .= $textLines;

            $order->setDetails($details);
        }

        return $crud
        ->setEntityLabelInSingular('Commande')
        ->setEntityLabelInPlural('Commandes')
        ->setDateTimeFormat('dd/MM/y à HH:mm:ss')
        ->setDefaultSort(['id' => 'DESC'])
        ->setPaginatorPageSize(1000000);
    }

    
    public function configureFields(string $pageName): iterable
    {
        
        return [
            Field::new('id')->hideOnForm(),
            DateTimeField::new('date', 'Date'),
            AssociationField::new('user', 'Utilisateur'),
            TextEditorField::new('details', 'Détails')->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
    return $actions->remove(Crud::PAGE_INDEX, Action::EDIT)
                   ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
    
}
