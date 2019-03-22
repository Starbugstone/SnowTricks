<?php

namespace App\Controller\Image;

use App\Entity\Image;
use App\Entity\Trick;
use App\FlashMessage\FlashMessageCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeleteImageController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/image/delete/{id}", name="image.deleteFromTrick", methods={"POST"})
     * @param Image $image
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTrickImage(Image $image){

        $imageTitle = $image->getTitle();
        /** @var Trick $trick */
        $trick = $image->getTrick();
        $trick->removeImage($image);
        $this->em->persist($trick);
        $this->em->flush();

        try{
            $this->get('vich_uploader.upload_handler')->remove($image, 'imageFile');
        }
        catch (\Exception $e){
            $this->addFlash(FlashMessageCategory::WARNING, 'image '.$imageTitle.' was no longer present');
        }

        $this->addFlash(FlashMessageCategory::SUCCESS, 'image '.$imageTitle.' deleted');

        return $this->redirectToRoute('trick.edit',[
            'id'=>$trick->getId(),
        ]);
    }
}