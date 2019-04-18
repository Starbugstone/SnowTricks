<?php

namespace App\Form;

use App\Entity\Video;
use App\Entity\VideoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoTypeForm extends AbstractType
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}