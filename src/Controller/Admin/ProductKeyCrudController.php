<?php
// src/Controller/Admin/ProductKeyCrudController.php

namespace App\Controller\Admin;

use App\Entity\ProductKey;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProductKeyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductKey::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('number', 'Clé produit'),
            DateTimeField::new('datetime', 'Date'),
            AssociationField::new('product', 'Produit')
                ->setCrudController(ProductCrudController::class),
            AssociationField::new('invoice', 'Facture')
                ->setCrudController(InvoiceCrudController::class)
                ->hideOnForm(),
        ];
    }
}
