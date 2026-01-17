<?php

namespace App\Form;

use App\Entity\Aquarium;
use App\Entity\Data;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Repository\AquariumRepository;

class DataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('createdAt', null, [
                'widget' => 'single_text'
            ])
            ->add('aquarium', EntityType::class,  [
                'class' => Aquarium::class,
                'choice_label' => 'name',
            ])
            ->add('temp')
            ->add('ph')
            ->add('kh')
            ->add('gh')
            ->add('no2')
            ->add('no3')
            ->add('cl2')
            ->add('observation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Data::class,
            'user' => null,
        ]);
    }
}
