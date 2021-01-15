<?php

namespace App\Form;

use App\Entity\Film;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du Film',
                'attr' => [
                    'placeholder' => 'Le seigneur des anneaux'
                ],
            ])
            ->add('image', TextType::class, [
                'label' => 'Image du Film',
                'attr' => [
                    'placeholder' => 'https://www.affichedefilm'
                ],
            ])    
            ->add('date_de_sortie', DateType::class, [
                'label' => 'Date de sortie du Film',
                'attr' => [
                    'placeholder' => 'YYYY-mm-dd'
                ],
            ])    
            ->add('duree', TimeType::class, [
                'label' => 'Durée du Film',
                'attr' => [
                    'placeholder' => '01:25:49'
                ],
            ])    
            ->add('description', TextType::class, [
                'label' => 'Description du Film',
                'attr' => [
                    'placeholder' => 'Il était une fois...'
                ],
            ]) 
            ->add('prix', NumberType::class, [
                'label' => 'Prix de location du Film',
                'attr' => [
                    'placeholder' => '3.99'
                ],
            ]) 
            ->add('bande_annonce', TextType::class, [
                'label' => 'Bande annonce du Film',
                'attr' => [
                    'placeholder' => 'https://www.bandeannoncedefilm'
                ],
            ]) 
            ->add('genre', EntityType::class, [
                'class' => Genre::class ,
                'choice_label' => 'genre',
                'attr' => [
                    'class' => 'selectpicker'
                ],
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
