<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\Trick;
use App\Form\DataTransformer\TagsToJsonTransformer;
use App\Form\Type\TagsType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{


    /**
     * @var TagsToJsonTransformer
     */
    private $transformer;

    public function __construct(TagsToJsonTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Name of the trick, must be at least 5 characters'
            ])
            ->add('text', TextareaType::class ,[
                'label' => 'Describe the trick',
                'attr' => [
                    'class'=>'materialize-textarea'
                ]
            ])
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'Name',
            ])

            //need to add tags as a hiddenTypeClass with custom transformers and ID for JS completion
            ->add('tags', TagsType::class)


//            ->add('tags', CollectionType::class, [
//                'entry_type' => TagFormType::class,
//                'entry_options' => ['label' => false],
//                'allow_add' => true,
//                'allow_delete' => true,
//            ])
//            ->add('tags', EntityType::class, [
//                'class' => Tag::class,
//                'choice_label' => 'name',
//                'label' => 'taggy',
//                'expanded' => false,
//                'multiple' => true,
//            ])
        ;

//        $builder
//            ->get('tags')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($tagsAsArray){
//                    dd($tagsAsArray);
//                    return json_encode($tagsAsArray);
//                },
//                function ($tagsAsString){
//                    return json_decode($tagsAsString);
//                }
//            ))
//            ;
//
//        $builder
//            ->get('tags')
//            ->addModelTransformer($this->transformer)
//            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
