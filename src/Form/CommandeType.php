<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => function(Produit $produit) {
                    return $produit->getNom() . ' - ' . $produit->getPrix() . '€ (Stock: ' . $produit->getStock() . ')';
                },
                'multiple' => true,
                'expanded' => true,
                'label' => 'Sélectionnez les produits',
                'attr' => ['class' => 'form-check-input'],
                'query_builder' => function (ProduitRepository $repository) {
                    return $repository->createQueryBuilder('p')
                        ->where('p.stock > 0')
                        ->orderBy('p.nom', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}