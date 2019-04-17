<?php

namespace App\Form;


use App\Entity\Image;
use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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

            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
                /** @var Trick $trick */
                $image = $event->getData();

                $form = $event->getForm();

                if(!$image || $image->getId() === null){
                    $form->add('imageFile', VichImageType::class,[
                        'required' => true,
                        'allow_delete' => false,
                        'download_uri' => false,
                        'image_uri' => false,
                    ])
                    ;
                }
                //TODO: Add image view
                //could do with js call and use hidden field but risk edit hack
                //or create a custom form type but still called on submit.
//                else{
//                    $form->add('image', TextType::class)
//                    ;
//                }

            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}