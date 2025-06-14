<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l’offre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu',
            ])
            ->add('typeContrat', ChoiceType::class, [
                'label' => 'Type de contrat',
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Intérim' => 'Intérim',
                    'Stage' => 'Stage',
                    'Freelance' => 'Freelance',
                ],
            ])
            ->add('salaire', MoneyType::class, [
                'label' => 'Salaire',
                'currency' => 'XOF',
                'scale' => 0,
            ])
            ->add('datePublication', DateTimeType::class, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
