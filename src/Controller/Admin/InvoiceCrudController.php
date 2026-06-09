<?php
namespace App\Controller\Admin;

use App\Entity\Invoice;
use App\Entity\ProductKey;
use App\Repository\ProductKeyRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class InvoiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Invoice::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Facture')
            ->setEntityLabelInPlural('Factures')
            ->setSearchFields(['number', 'user.email'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('number', 'N° Facture')
            ->setRequired(true);

        yield DateTimeField::new('createdAt', 'Date');

        yield NumberField::new('totalAmount', 'Montant total')
            ->setNumDecimals(2)
            ->formatValue(function ($value) {
                return number_format($value, 2, ',', ' ') . ' €';
            });

        yield ChoiceField::new('status', 'Statut')
            ->setChoices([
                'En attente' => 'pending',
                'Payée' => 'paid',
                'Annulée' => 'cancelled',
            ]);

        yield AssociationField::new('user', 'Utilisateur')
            ->setRequired(true);

        yield AssociationField::new('paymentMethod', 'Moyen de paiement')
            ->setRequired(false);

        yield AssociationField::new('productKeys', 'Clés produit disponibles')
            ->setFormTypeOptions([
                'class' => ProductKey::class,
                'query_builder' => function (ProductKeyRepository $er) {
                    return $er->createQueryBuilder('pk')
                        ->where('pk.invoice IS NULL')
                        ->orderBy('pk.id', 'ASC');
                },
                'choice_label' => function (ProductKey $pk) {
                    return sprintf('%s - %s',
                        $pk->getNumber(),
                        $pk->getProduct() ? $pk->getProduct()->getTitle() : 'Sans produit'
                    );
                },
                'multiple' => true,
                'by_reference' => false,
            ])
            ->formatValue(function ($value, $entity) {
                return $entity->getProductKeys()->count() . ' clé(s)';
            })
            ->setHelp('Sélectionnez les clés à associer à cette facture');
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('status')
            ->add('user')
            ->add('createdAt');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
