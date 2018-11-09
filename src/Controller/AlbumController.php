<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Serializer\FormErrorSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;
    /**
     * @var FormErrorSerializer
     */
    private $errorSerializer;
    /**
     * @var AlbumRepository
     */
    private $albumRepository;

    public function __construct(EntityManagerInterface $entityManager,
                                FormErrorSerializer $errorSerializer,
                                AlbumRepository $albumRepository)
    {
        $this->entityManager = $entityManager;
        $this->errorSerializer = $errorSerializer;
        $this->albumRepository = $albumRepository;
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
                'errors' => $this->errorSerializer->convertFormToArray($form)
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($form->getData());
        $this->entityManager->flush();

        return new JsonResponse([
            'status' => 'ok'
        ],
            JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/album/{id}", name="get_album", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function get($id)
    {
        return new JsonResponse($this->findAlbumById($id), JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/album", name="cget_album", methods={"GET"})
     */
    public function cget()
    {
        return new JsonResponse($this->albumRepository->findAll(), JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/album/{id}", name="put_album", requirements={"id": "\d+"}, methods={"PUT"})
     * @param int $id
     *
     * @return JsonResponse
     */
    public function put(Request $request, $id)
    {

        $data = json_decode($request->getContent(), true);

        $existingAlbum = $this->findAlbumById($id);

        $form = $this->createForm(AlbumType::class, $existingAlbum);

        $form->submit($data);

        if (false === $form->isValid()) {
            return new JsonResponse([
                'status' => 'error',
                'errors' => $this->errorSerializer->convertFormToArray($form)
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/album/{id}", name="patch_album", requirements={"id": "\d+"}, methods={"PATCH"})
     * @param int $id
     *
     * @return JsonResponse
     */
    public function patch(Request $request, $id)
    {

        $data = json_decode($request->getContent(), true);

        $existingAlbum = $this->findAlbumById($id);

        $form = $this->createForm(AlbumType::class, $existingAlbum);

        $form->submit($data, false);//

        if (false === $form->isValid()) {
            return new JsonResponse([
                'status' => 'error',
                'errors' => $this->errorSerializer->convertFormToArray($form)
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_NO_CONTENT);
    }

    private function findAlbumById($id)
    {
        $album = $this->albumRepository->find($id);

        if (null === $album) {
            throw new NotFoundHttpException();
        }

        return $album;
    }
}
