<?php
// src/Controller/Admin/PlatformCrudController.php

namespace App\Controller\Admin;

use App\Entity\Platform;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class PlatformCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Platform::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Plateforme')
            ->setEntityLabelInPlural('Plateformes')
            ->setSearchFields(['name', 'type'])
            ->setDefaultSort(['name' => 'ASC'])
            ->setPageTitle('index', 'Gestion des plateformes')
            ->setPageTitle('new', 'Ajouter une plateforme')
            ->setPageTitle('edit', 'Modifier la plateforme');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            TextField::new('type', 'Type'),
            TextField::new('url', 'URL'),
            ArrayField::new('systems', 'Systèmes'),
            AssociationField::new('products', 'Produits associés')
                ->onlyOnIndex()
                ->formatValue(function ($value, $entity) {
                    return $entity->getProducts()->count() . ' produit(s)';
                }),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('type');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
