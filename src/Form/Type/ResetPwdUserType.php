<?php

namespace ClickPizza\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;


class ResetPwdUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('username', TextType::class, array(
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank()
                )));
    }

   public function getName() {
        return 'resetPwd';
    } 
}
