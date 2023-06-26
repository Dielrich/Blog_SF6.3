<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
/* CONSTRAINT */
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
/* TYPES */
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [ 'label' => 'Renseignez votre email ici *'])
            ->add('username', TextType::class, [ 
                    'label' => 'Indiquez un nom d\'utilisateur pour votre compte *',
                    'help' => '25 Caractères maximum'])
                    
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les conditions d\'utilisation *',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\'utilisation',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les 2 champs doivents être identiques !',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Renseigner un mot de passe*',
                                    'help' => '(8 caractères minmum, 1 majuscule, 1 minuscule, 1 caractère spécial et 1 chiffre.)'],
                'second_options' => ['label' => 'Confirmer votre mot de passe*'],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un Mot de Passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre Mot de Passe doit comporter au minimum {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[#?!~|`%§@$€£%^&µ*°²¨+={}()[\]_,;.:-]).{8,4096}$/',
                        'message' => 'Le mot de passe doit comporter 8 caractères minimum avec un moins une lettre en majuscule et minuscule, un chiffre et un caractère spécial',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'attr' => ['novalidate' => 'novalidate'], // comment me to reactivate the HTML5 validation
        ]);
    }
}
