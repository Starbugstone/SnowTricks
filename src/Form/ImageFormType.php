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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'title of the image',
                'required' => true,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var Trick $trick */
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
                //TODO: Add image view
                //could do with js call and use hidden field but risk edit hack
                //or create a custom form type but still called on submit.
                else {
                    $form->add('image', TextType::class);
                }

            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'file_uri' => null,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
        //adding the uri to image to the form view.
        //todo: since this is in a collection, need to override the default form view
    {
        /**
         * @var $entity Image
         */
        $entity = $form->getData();
        if ($entity) {
            $view->vars['file_url'] = ($entity->getImage() === null)
                ? null
                : '/uploads/trick_image/' . $entity->getImage() //TODO: reset with env variable for uploaded images
            ;
        }

//        dump($form->getData()); //this has the image in ModelData/image, also has the ID
//        dump($view);

    }
}