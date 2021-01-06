<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'article.title',
                'attr' => [
                    'placeholder' => 'article.placeholders.title'
                ],
            ])
            ->add('author', TextType::class, [
                'label' => 'article.author',
                'attr' => [
                    'placeholder' => 'article.placeholders.author'
                ],
            ])
            ->add('text', TextareaType::class, [
                'label' => 'article.text',
                'attr' => [
                    'rows' => 7
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_combine(Article::getStatuses(), Article::getStatuses()),
                'choice_label' => function($value) {
                    return 'article.statuses.'.$value;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
