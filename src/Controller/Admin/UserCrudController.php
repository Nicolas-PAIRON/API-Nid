<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserCrudController extends AbstractCrudController
{
    private $userPasswordEncoderInterface;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $encodedPassword = $this->userPasswordEncoderInterface->encodePassword($entityInstance, $entityInstance->getRawPassword());
        $entityInstance->setPassWord($encodedPassword);
        $entityInstance->setRawPassword(null);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance->getRawPassword()) {
            $encodedPassword = $this->userPasswordEncoderInterface->encodePassword($entityInstance, $entityInstance->getRawPassword());
            $entityInstance->setPassWord($encodedPassword);
        }
        $entityInstance->setUpdatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        parent::updateEntity($entityManager, $entityInstance);
        $entityInstance->setRawPassword(null);
    }

    public function configureFields(string $pageName): iterable
    {
        if($pageName === Crud::PAGE_EDIT){
            $required = false;
        }else{
            $required = true;
        }

        return [
            EmailField::new('email', "Email")->setFormTypeOptions(["constraints"=>[new Length(['max'=>180]),new NotBlank()]]),
            TextField::new('lastname','Nom')->setFormTypeOptions(["constraints"=>[new Length(['max'=>50])]]),
            TextField::new('firstname','Prénom')->setFormTypeOptions(["constraints"=>[new Length(['max'=>50])]]),
            TextField::new('rawPassword', 'Mot de passe')->onlyOnForms()
                                                         ->setHelp('Tapez un nouveau mot de passe pour le modifier')
                                                         ->setRequired($required),
            TextField::new('phoneNumber','Numéro de téléphone')->setFormTypeOptions(["constraints"=>[new Length(['max'=>15]), new Regex(['pattern'=>'/\D/','match' => false, 'message' => 'Votre numéro de téléphone ne doit contenir que des chiffres'])]]),
            DateTimeField::new('createdAt', 'Inscrit le'),
            ArrayField::new('roles', 'Rôle')->setFormTypeOptions(["constraints"=>[new NotBlank()]]),
            TextEditorField::new('addressBill', 'Adresse Facturation')->hideOnForm(),
            TextEditorField::new('addressDelivery', 'Adresse Livraison')->hideOnForm(),
        ];
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Utilisateur')
        ->setEntityLabelInPlural('Utilisateurs')
        ->setDefaultSort(['id' => 'DESC'])
        ->setDateTimeFormat('dd/MM/y')
        ->setPaginatorPageSize(1000000);
    }
    
}
