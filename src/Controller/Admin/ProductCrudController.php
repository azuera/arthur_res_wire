<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setSearchFields(['title', 'description', 'region'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPageTitle('index', 'Gestion des produits')
            ->setPageTitle('new', 'Ajouter un produit')
            ->setPageTitle('edit', 'Modifier le produit');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            IntegerField::new('quantity', 'Quantité'),
            DateTimeField::new('releaseDate', 'Date de sortie'),
            TextField::new('region', 'Région'),
            NumberField::new('price', 'Prix'),
            TextareaField::new('description', 'Description')->hideOnIndex(),
            NumberField::new('rating', 'Note'),
            ArrayField::new('tags', 'Tags'),
            TextareaField::new('requiredConfiguration', 'Configuration requise')->hideOnIndex(),
            AssociationField::new('plateform', 'Plateformes')
                ->setFormTypeOptions(['by_reference' => false])
                ->formatValue(function ($value, $entity) {
                    $platforms = $entity->getPlateform()->map(function($p) {
                        return $p->getName();
                    })->toArray();
                    return implode(', ', $platforms);
                }),

            // ✅ Uniquement l'affichage du nombre d'images dans la liste
            AssociationField::new('images', 'Images')
                ->onlyOnIndex()
                ->formatValue(function ($value, $entity) {
                    return $entity->getImages()->count() . ' image(s)';
                }),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('title')
            ->add('region')
            ->add('price')
            ->add('plateform');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
