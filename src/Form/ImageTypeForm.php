<?php

namespace App\Form;


use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ImageTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'title of the image',
                'required' => true,
            ])
            ->add('imageFile', VichImageType::class,[
                'required' => true,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
            ])
//            ->add('addImage', SubmitType::class, [
//                'label' => $options['add_image_label'],
//                'attr' => [
//                    'class' => 'waves-effect waves-light btn right mr-2'
//                ]
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'add_image_label' => 'Add Image',
        ]);
    }
}