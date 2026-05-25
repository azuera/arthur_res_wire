<?php
// src/Controller/Admin/ImageCrudController.php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Image')
            ->setEntityLabelInPlural('Images')
            ->setSearchFields(['url', 'altText'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPageTitle('index', 'Gestion des images')
            ->setPageTitle('new', 'Ajouter une image')
            ->setPageTitle('edit', 'Modifier l\'image');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('url', 'URL de l\'image'),
            TextField::new('altText', 'Texte alternatif'),
            IntegerField::new('displayOrder', 'Ordre d\'affichage'),
            AssociationField::new('product', 'Produit associé')
                ->setRequired(false)
                ->formatValue(function ($value, $entity) {
                    return $entity->getProduct() ? $entity->getProduct()->getTitle() : 'Aucun';
                }),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('product')
            ->add('altText');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
