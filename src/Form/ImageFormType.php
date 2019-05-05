<?php

namespace App\Form;


use App\Entity\Image;
use App\Form\Type\ShowImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $image = $event->getData();

                $form = $event->getForm();

                if (!$image || $image->getId() === null) {
                    $form->add('imageFile', VichImageType::class, [
                        'required' => true,
                        'allow_delete' => false,
                        'download_uri' => false,
                        'image_uri' => false,
                    ]);
                }
                else {
                    $form->add('image', ShowImageType::class, [
                        'image_property' => 'webImage',
                        'mapped' => false,
                        'label' => false,

                    ]);
                }

            })
            ->add('title', TextType::class, [
                'label' => 'title of the image',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'file_uri' => null,
        ]);
    }
}