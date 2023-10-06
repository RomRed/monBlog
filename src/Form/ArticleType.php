<?php

// src/Form/ArticleType.php

namespace App\Form;

use App\Entity\SousCategorie;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre_article', TextType::class, ['attr' => ['class' => 'form-control', 'placeholder' => 'Votre Titre']])
            // ->add('slug_article', TextType::class)
            ->add('contenu_article', CKEditorType::class)
            // ->add('date_creation', DateTimeType::class)
            // ->add('id_utilisateur', IntegerType::class)
            ->add('id_sous_categorie', EntityType::class, [

                'class' => SousCategorie::class,

                'choice_label' => 'nomSousCategorie',

                 'label' => false,

                 'attr' => ['class' => 'form-select'],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Article',
        ]);
    }
}

