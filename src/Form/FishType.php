<?php

namespace App\Form;

use App\Entity\Aquarium;
use App\Entity\Fish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Repository\AquariumRepository;

class FishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('name')
            ->add('aquarium', EntityType::class, [
                'class' => Aquarium::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fish::class,
            'user' => null,
        ]);
    }
}
