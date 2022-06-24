<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label'=>'Prénom',
                'attr'=>[
                    'placeholder'=>'votre prénom'
                ],
                'constraints'=>new Length(null,2,30)
            ])
            ->add('lastName', TextType::class, [
                'label'=>'Nom',
                'attr'=>[
                    'placeholder'=>'votre nom'
                ],
                'constraints'=>new Length(null,2,30)
            ]) 
            ->add('email', EmailType::class, [
                'label'=>'Email',
                'attr'=>[
                    'placeholder'=>'votre email'
                ],
                'constraints'=>new Length(null,2,70)
            ])  
            ->add('password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'invalid_message'=>'Le mot de passe et la confirmation doivent etre identique.',
                'label' =>'Mot de passe',
                 'required'=>true,
                 'first_options'=>[
                     'label'=>'Mot de passe','attr'=>[
                    'placeholder'=>'votre mot de passe'
                    ]],
                 'second_options'=>[
                     'label'=>'Confirmation du mot de passe',
                     'attr'=>[
                        'placeholder'=>'Confirmez votre mot de passe'
                        ]]
            ])
      
            ->add('submit',SubmitType::class,[
                'label'=>'S\'inscrire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
