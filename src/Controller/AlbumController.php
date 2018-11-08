<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Serializer\FormErrorSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;
    /**
     * @var FormErrorSerializer
     */
    private $errorSerializer;

    public function __construct(EntityManagerInterface $entityManager, FormErrorSerializer $errorSerializer)
    {
        $this->entityManager = $entityManager;
        $this->errorSerializer = $errorSerializer;
    }

    /**
     * @Route("/album", name="post_album", methods={"POST"})
     */
    public function post(Request $request)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $form = $this->createForm(AlbumType::class, new Album());

        $form->submit($data);

        if (false === $form->isValid()) {
            return new JsonResponse([
                'status' => 'error',
                'errors'  => $this->errorSerializer->convertFormToArray($form)
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($form->getData());
        $this->entityManager->flush();

        return new JsonResponse([
            'status' => 'ok'
        ],
            Response::HTTP_CREATED);
    }
}
