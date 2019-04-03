<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class testAjax extends AbstractController{

    /**
     * @Route("/ajax", name="ajax.test")
     */
    public function index(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $response = array(
                'name' => 'john',
                'type' => 'snow'
            );
            return new JsonResponse($response);
        }
        throw new \Error("Not an xmlHttpRequest");
    }

}