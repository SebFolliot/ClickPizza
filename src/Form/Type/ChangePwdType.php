<?php

namespace Clickpizza\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class ChangePwdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
           ->add('oldPassword', PasswordType::class, array(
                'required' => true,
                'constraints' => array(
                new Assert\NotBlank()),
            )) 
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe de confirmation doit être identique au  premier mot de passe.',
                'options' => array('required' => true),
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat password'),
                'required' => true,
                'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array(
                    'min' => 8,
                    'max' => 100,
                    'minMessage' => 'Le mot de passe est trop court. Il faut {{ limit }} caractères minimum.',
                    'maxMessage' => 'Le mot de passe est trop long. Il faut {{ limit }} caractères maximum.'
                )), 
                new Assert\Regex(array(
                    'message' =>'Le mot de passe n\'est pas valide. Il doit être composé d\'au moins une majuscule.',
                    'pattern' => '#([A-Z]){1,}#')),
                new Assert\Regex(array(
                    'message' =>'Le mot de passe n\'est pas valide. Il doit être composé d\'au moins un chiffre.',
                    'pattern' => '#([0-9]){1,}#'))
                ),
            ));
    }
    
    public function getName() {
        return 'user';
    }
    
}