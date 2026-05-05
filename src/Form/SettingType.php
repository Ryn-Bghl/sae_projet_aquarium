<?php

namespace App\Form;

use App\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('theme', ChoiceType::class, [
                'choices' => [
                    'Light' => 'light',
                    'Dark' => 'dark',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Theme Preference',
                'mapped' => false, // Crucial: tell the form not to map this field directly to the entity
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $setting = $event->getData();
            $form = $event->getForm();

            if ($setting instanceof Setting) {
                $settingsData = $setting->getSettings();
                $form->get('theme')->setData($settingsData['theme'] ?? 'light');
            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $setting = $event->getData();
            $form = $event->getForm();

            if ($setting instanceof Setting) {
                $settingsData = $setting->getSettings();
                $settingsData['theme'] = $form->get('theme')->getData();
                $setting->setSettings($settingsData);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
