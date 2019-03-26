<?php

namespace App\Controller\Image;

use App\Entity\Image;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SetPrimaryImageController
 * @package App\Controller\Image
 *
 * Require the user to be connected for everything here
 * @IsGranted("ROLE_USER")
 */
class SetPrimaryImageController extends AbstractController
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
     * @Route("/image/set/{trick}-{image}", name="image.setprimary")
     * @param Trick $trick
     * @param Image $image
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setPrimaryController(Trick $trick, Image $image)
    {

        //we only want one front image, so reset others. we could just reset the primary but this corrects bugs if we have 2 primary images for some unknown reason
        $trickImages = $trick->getImages();
        $actualPrimaryImage = $trick->getPrimaryImages()[0];
        /** @var Image $trickImage */
        foreach ($trickImages as $trickImage) {
            if ($trickImage->getPrimaryImage()) {
                $trickImage->setPrimaryImage(false);
            }

            //setting the actual image, if we clicked on the same image then unset
            if($trickImage === $image && $actualPrimaryImage !== $image){
                $trickImage->setPrimaryImage(true);
            }
        }
        $this->em->flush();
//        dump($trick);
//        dump($image);
//        dd("end");

        return $this->redirectToRoute('trick.edit', ['id' => $trick->getId()]);
    }

}