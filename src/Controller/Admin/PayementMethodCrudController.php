<?php
// src/Controller/Admin/PayementMethodCrudController.php

namespace App\Controller\Admin;

use App\Entity\PayementMethod;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class PayementMethodCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PayementMethod::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Méthode de paiement')
            ->setEntityLabelInPlural('Méthodes de paiement')
            ->setSearchFields(['type', 'lastDigits'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPageTitle('index', 'Gestion des méthodes de paiement')
            ->setPageTitle('new', 'Ajouter une méthode de paiement')
            ->setPageTitle('edit', 'Modifier la méthode de paiement');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('type', 'Type de paiement'),
            TextField::new('lastDigits', 'Derniers chiffres'),
            AssociationField::new('user', 'Utilisateur')
                ->formatValue(function ($value, $entity) {
                    return $entity->getUser() ? $entity->getUser()->getEmail() : 'N/A';
                }),
            AssociationField::new('invoices', 'Factures')
                ->onlyOnIndex()
                ->formatValue(function ($value, $entity) {
                    return $entity->getInvoices()->count() . ' facture(s)';
                }),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('type')
            ->add('user');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
