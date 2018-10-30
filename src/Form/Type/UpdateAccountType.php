<?php

namespace Clickpizza\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class UpdateAccountType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, array(
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array(
                'min' => 2,
                'max' => 60,
                'minMessage' => 'Le nom est trop court. Il faut {{ limit }} caractères minimum.',
                'maxMessage' => 'Le nom est trop long. Il faut {{ limit }} caractères maximum.'
                ))),
            ))
            ->add('firstname', TextType::class, array(
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array(
                    'min' => 2,
                    'max' => 60,
                    'minMessage' => 'Le prénom est trop court. Il faut {{ limit }} caractères minimum.',
                    'maxMessage' => 'Le prénom est trop long. Il faut {{ limit }} caractères maximum.'
                    ))),
            ))
            ->add('civility', ChoiceType::class, array(
                'choices' => array('M.' => 'M.', 'Mlle' => 'Mlle', 'Mme' => 'Mme')))
            ->add('email', EmailType::class, array(
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Email(array(
                    'message' => 'Adresse mail non valide',
                    'checkMX' => true
                    ))),
            ))
            ->add('phonenumber', TextType::class, array(
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Regex(array(
                    'message' =>'Le n° de téléphone n\'est pas valide. Il doit être composé de 10 chiffres et commencé par 0.',
                    'pattern' => '#^0[0-9]{9}$#'))),
            ))
            ->add('role', TextType::class);
    }
        
        public function getName()
    {
        return 'user';
    }
}
