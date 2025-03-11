<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'Input your first name'
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Input your last name'
            ])
            ->add('email', EmailType::class,[
                'label' => 'Input your email'
            ])
            ->add('password', PasswordType::class,[
                'label' => 'Input your password'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add',
                'attr' => [
                    'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
