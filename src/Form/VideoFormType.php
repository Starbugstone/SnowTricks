<?php

namespace App\Form;

use App\Entity\Video;
use App\Entity\VideoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'title of the Video',
                'required' => true,
            ])
            ->add('code', TextType::class, [
                'label' => 'The Video code',
                'required' => true,
                'help' => 'the video identifier, usually a unique code following "v="'
            ])
            ->add( 'videoType', EntityType::class, [
                'class' => VideoType::class,
                'choice_label' => 'site',
                ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
                $video = $event->getData();

                $form = $event->getForm();
                if ($video && $video->getId() !== null){
                    $form->add('vidImage', HiddenType::class, [
                        'image_property' => 'videoIntegrationImage',
                        "mapped" => false,
                    ])
                        ;
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}