<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserProfileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('UserName', TextType::class)
//            ->add('imageFile', VichImageType::class,[
//                'required' => false,
//                'allow_delete' => false,
//                'download_uri' => false,
//                'image_uri' => false,
//            ])
                //TODO: upload form using materialise
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => false,
            ])
            ->add('updateProfile', SubmitType::class, [
                'label' => $options['save_button_label'],
                'attr' => [
                    'class' => 'waves-effect waves-light btn right mr-2'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'save_button_label' => 'Update Profile',
        ]);
    }
}