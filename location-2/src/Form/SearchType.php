<?php

namespace App\Form;

use App\Entity\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dt_debut', DateTimeType::class, [
                'widget' => 'single_text',
                "label" => "Début de location",
                "attr" => [
                    "min" => (new \DateTime())->format('Y-m-d H:i'), // désactiver les dates avant aujourdhui dans la calendrier
                    
                ]
            ])
            ->add('dt_fin', DateTimeType::class, [
                'widget' => 'single_text',
                "label" => "Fin de location", 
                "attr" => [
                    "min" => (new \DateTime())->format('Y-m-d H:i'), // désactiver les dates avant aujourdhui dans la calendrier,   
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            "method" => "get",
            "csrf_protection" => false
        ]);
    }

    public function getBlockPrefix()
    {
        return "";
    }
}
