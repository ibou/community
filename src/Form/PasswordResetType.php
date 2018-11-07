<?php

namespace App\Form;

use App\Entity\PasswordReset;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('newPassword',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'required' => true,
                'options' => ['attr' => ['class' => 'input']],
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'label_attr' => ['class' => 'label'],
                'second_options' => ['label' => 'Confirmation du nouveau mot de passe'],
                "mapped" => false,
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PasswordReset::class,
        ]);
    }
}
