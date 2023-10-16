<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('images', FileType::class, [
                'label' => 'Sélectionner des images',
                'multiple' => true, // Permet de sélectionner plusieurs fichiers
                'mapped' => false, // Indique que ce champ ne doit pas être mappé à une propriété de l'entité Produit
                'required' => false, // Le champ n'est pas obligatoire
            ])

            ->add('NomDuProduit')
            ->add('Prix')
            ->add('Categorie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
