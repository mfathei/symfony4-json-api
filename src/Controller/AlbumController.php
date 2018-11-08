<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    /**
     * @Route("/album", name="post_album", methods={"POST"})
     */
    public function post(Request $request)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        dump($data);

        return new JsonResponse([
            'status' => 'ok'
        ],
            Response::HTTP_CREATED);
    }
}
