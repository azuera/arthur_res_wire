<?php

namespace App\Controller\Admin;

use App\Entity\Invoice;
use App\Entity\User;
use App\Entity\PayementMethod;
use App\Entity\ProductKey;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

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
            ->setPageTitle('index', 'Liste des factures')
            ->setPageTitle('new', 'Créer une facture')
            ->setPageTitle('edit', 'Modifier la facture')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            TextField::new('number', 'Numéro de facture')
                ->setHelp('Numéro unique de la facture'),

            DateTimeField::new('createdAt', 'Date de création')
                ->setFormat('dd/MM/yyyy HH:mm:ss'),

            NumberField::new('totalAmount', 'Montant total')
                ->setNumDecimals(2)
                ->setHelp('Montant total de la facture en €'),

            ChoiceField::new('status', 'Statut')
                ->setChoices([
                    'En attente' => 'pending',
                    'Payée' => 'paid',
                    'Annulée' => 'cancelled',
                    'Remboursée' => 'refunded',
                ])
                ->renderAsBadges([
                    'pending' => 'warning',
                    'paid' => 'success',
                    'cancelled' => 'danger',
                    'refunded' => 'info',
                ]),

            // Relation avec User (l'acheteur)
            AssociationField::new('user', 'Client')
                ->setCrudController(UserCrudController::class)
                ->setHelp('Sélectionnez le client')
                ->setRequired(true)
                ->formatValue(function ($value, $entity) {
                    if ($entity->getUser()) {
                        return $entity->getUser()->getEmail();
                    }
                    return null;
                }),

            // Relation avec PaymentMethod (moyen de paiement)
            AssociationField::new('paymentMethod', 'Moyen de paiement')
                ->setCrudController(PayementMethodCrudController::class)
                ->setHelp('Sélectionnez le moyen de paiement utilisé')
                ->setRequired(true)
                ->formatValue(function ($value, $entity) {
                    if ($entity->getPaymentMethod()) {
                        return $entity->getPaymentMethod()->getType() . ' (**** ' . $entity->getPaymentMethod()->getLastDigits() . ')';
                    }
                    return null;
                }),

            AssociationField::new('productKeys', 'Produits achetés')
                ->setCrudController(ProductKeyCrudController::class)
                ->setFormTypeOption('by_reference', false)
                ->setHelp('Gérez les clés produits associées')
                ->hideOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Créer une facture');
            });
    }
}
