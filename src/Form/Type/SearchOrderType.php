<?php

namespace ClickPizza\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolverInterface;
// use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;


class SearchOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, array(
                'choices' => array(
                    'Toutes' => 'Toutes',
                    'En cours' => 'En cours',
                    'Validée' => 'Validée',
                    'Annulée' => 'Annulée')
                    ))
            ->add('id', IntegerType::class, array(
                'required' => false,
                ));
      /*      ->add('name', TextType::class, array(
            'required' => false,
            'constraints' => array(
                new Assert\Regex(array(
                    'message' =>'Le nom n\'est pas valide, il ne doit pas être composé de chiffre.',
                    'pattern' => '#[a-zA-Z-]#'))),
            )); */
     }
    
    public function getName() {
        return 'search_order';
    }
        
}