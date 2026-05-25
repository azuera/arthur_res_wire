<?php
// src/Controller/Admin/ProductKeyCrudController.php

namespace App\Controller\Admin;

use App\Entity\ProductKey;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class ProductKeyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductKey::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Clé produit')
            ->setEntityLabelInPlural('Clés produit')
            ->setSearchFields(['number', 'product.title'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPageTitle('index', 'Liste des clés produit')
            ->setPageTitle('new', 'Ajouter une clé produit')
            ->setPageTitle('edit', 'Modifier la clé produit');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('number', 'Clé')
                ->setRequired(true),
            DateTimeField::new('datetime', 'Date de création'),
            AssociationField::new('product', 'Produit')
                ->setRequired(true),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('product');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
