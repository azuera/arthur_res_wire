<?php
// src/Controller/Admin/ImageCrudController.php

namespace App\Controller\Admin;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            // ✅ Champ pour voir l'image existante (dans l'index et le détail)
            ImageField::new('url')
                ->setBasePath('/images/products')
                ->setLabel('Image actuelle')
                ->onlyOnIndex(),

            // ✅ Champ pour uploader une nouvelle image (dans le formulaire)
            TextField::new('imageFile')
                ->setFormType(VichImageType::class)
                ->setLabel('Nouvelle image')
                ->onlyOnForms(),

            TextField::new('altText')
                ->setLabel('Texte alternatif'),

            IntegerField::new('displayOrder')
                ->setLabel('Ordre d\'affichage'),

            AssociationField::new('product')
                ->setLabel('Produit associé'),
        ];
    }
}
