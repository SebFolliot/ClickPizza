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
                'required' => true,
                'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array(
                'min' => 2,
                'max' => 60,
                'minMessage' => 'Le nom du produit est trop court. Il faut {{ limit }} caractères minimum.',
                'maxMessage' => 'Le nom du produit est trop long. Il faut {{ limit }} caractères maximum.'
                ))),
            ))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array(
                'min' => 10,
                'max' => 255,
                'minMessage' => 'La description du produit est trop courte. Il faut {{ limit }} caractères minimum.',
                'maxMessage' => 'La description du produit est trop longue. Il faut {{ limit }} caractères maximum.'
                ))),
            ))
            ->add('picture', FileType::class, array(
                'required' => true,
                'data_class' => null,
                'constraints' => array(
                new Assert\Image(array(
                'mimeTypes' => array(
                    'image/jpeg', 'image/png'),
                'mimeTypesMessage' => 'Le format du fichier image doit être au format jpeg ou au format png.'                
                )),
                new Assert\File(array(
                'maxSize' => '1024k',
                'maxSizeMessage' => 'Le fichier est trop volumineux. La taille maximale autorisée est de 1024 ko'
                ))),
            ))
            ->add('price', NumberType::class, array(
                'required' => true,
                'constraints' => array(
                new Assert\NotBlank(),
                new Assert\Range(array(
                'min' => 1,
                'max' => 40,
                'minMessage' => 'Le prix d\'un produit doit être au minimum de {{ limit }} €.',
                'maxMessage' => 'Le prix d\'un produit doit être au maximum de {{ limit }} €.'
                ))),
                ));
    }
    


    public function getName() {
        return 'commodity';
    }
}
