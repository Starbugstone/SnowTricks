<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use App\Form\Type\ShowImageType;
use App\Form\Type\TagsType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UnexpectedValueException;
use Vich\UploaderBundle\Form\Type\VichImageType;

class TrickFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if($options['all_tags_json'] === '' || $options['trick_tags_json'] === ''){
            throw new UnexpectedValueException("all_tags_json or trick_tags_json not defined in the form constructor");
        }

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $trick = $event->getData();

                $form = $event->getForm();

                if ($trick && $trick->getId() !== null) {
                    $form->add('delete', SubmitType::class, [
                        'label' => 'Delete',
                        'attr' => [
                            'class' => 'waves-effect waves-light btn red lighten-2 right mr-2',
                            'onclick' => 'return confirm(\'are you sure?\')',
                        ]
                    ]);;
                }
            })
            ->add('name', TextType::class, [
                'label' => 'Name of the trick, must be at least 5 characters'
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Describe the trick',
                'attr' => [
                    'class' => 'materialize-textarea'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'Name',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageFormType::class,
                'label' => false,
                'allow_add' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'allow_delete' => true,
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoFormType::class,
                'label' => false,
                'allow_add' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'allow_delete' => true,
            ])



            //Hidden encoded tags
            ->add('tags', TagsType::class)
            //hidden tags data
            ->add('tagsData', HiddenType::class, [
                "mapped" => false,
                'attr' => [
                    'class' => 'trick-tag-data',
                    'data-all-tags-json' => $options['all_tags_json'],
                    'data-trick-tags-json' => $options['trick_tags_json'],
                ]

            ])
            ->add('save', SubmitType::class, [
                'label' => $options['save_button_label'],
                'attr' => [
                    'class' => 'waves-effect waves-light btn right mr-2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'save_button_label' => 'Save',
            'all_tags_json' => '',
            'trick_tags_json' => '',
        ]);
    }
}
