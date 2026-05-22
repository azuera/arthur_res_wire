<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Platform;
use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

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
            ->setPageTitle('index', 'Liste des produits')
            ->setPageTitle('new', 'Créer un produit')
            ->setPageTitle('edit', 'Modifier le produit')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            TextField::new('title', 'Titre')
                ->setHelp('Nom du produit'),

            IntegerField::new('quantity', 'Quantité')
                ->setHelp('Stock disponible'),

            DateField::new('releaseDate', 'Date de sortie'),

            TextField::new('region', 'Région'),

            NumberField::new('price', 'Prix')
                ->setNumDecimals(2),

            TextareaField::new('description', 'Description')
                ->hideOnIndex(), // Cache sur la liste pour éviter trop de texte

            NumberField::new('rating', 'Note')
                ->setHelp('Note sur 5')
                ->setRequired(false),

            ArrayField::new('tags', 'Tags')
                ->hideOnIndex(),

            TextareaField::new('requiredConfiguration', 'Configuration requise')
                ->hideOnIndex()
                ->setRequired(false),

            // Relation ManyToMany avec Platform (plusieurs plateformes)
            AssociationField::new('plateform', 'Plateformes')
                ->setCrudController(PlatformCrudController::class)
                ->setFormTypeOption('by_reference', false)
                ->setHelp('Sélectionnez les plateformes compatibles')
                ->formatValue(function ($value, $entity) {
                    $platforms = $entity->getPlateform();
                    $names = [];
                    foreach ($platforms as $platform) {
                        $names[] = $platform->getName();
                    }
                    return implode(', ', $names);
                }),
            AssociationField::new('images', 'Images')
                ->setCrudController(ImageCrudController::class)
                ->setFormTypeOption('by_reference', false)
                ->setHelp('Gérez les images du produit')
                ->hideOnIndex(),
        ];
    }
}
