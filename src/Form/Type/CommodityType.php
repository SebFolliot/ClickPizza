<?php

namespace ClickPizza\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class CommodityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('type', ChoiceType::class, array(
                'choices' => array('Pizza' => 'Pizza', 'Salade' => 'Salade', 'Boisson' => 'Boisson', 'Dessert' => 'Dessert')))
            ->add('title', TextType::class, array(
                'required' => true))
            ->add('description', TextareaType::class, array(
                'required' => true))
            ->add('picture', FileType::class, array(
                'required' => true,
                'data_class' => null))
            ->add('price', NumberType::class, array(
                'required' => true,
                'constraints' => array(
                new Assert\NotBlank(),               
                )));
    }
    


    public function getName() {
        return 'commodity';
    }
}