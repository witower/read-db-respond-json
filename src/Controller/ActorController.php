<?php

namespace App\Controller;

use App\Entity\Actor;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/actors", name="actors_", defaults={})
 */
class ActorController extends AbstractController
{
    // TODO Validation
    // TODO Implement Service and move repo and em handling to it, leaving Controller with handling requests and responses
    // TODO Proper Exception handling
    private $repository;
    private $em;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->repository = $doctrine->getRepository(Actor::class);
        $this->em = $doctrine->getManager();
    }

    /**
     * @Route("", name="get_all", methods="GET")
     */
    public function getAll(): JsonResponse
    {
        $actors = $this->repository->findAll();

        return $this-> json($actors);
    }

    /**
     * @Route("/{id}", name="get_by_id", methods="GET")
     */
    public function getById($id): JsonResponse
    {
        $actor = $this->repository->find($id);

        $responseStatus = !$actor ? Response::HTTP_NOT_FOUND : Response::HTTP_OK;

        return $this->json($actor,$responseStatus);
    }

    /**
     * @Route("", name="create", methods="POST")
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        $actor = $serializer->deserialize($data, Actor::class, 'json');

        $errors = $validator->validate($actor);
        if (count($errors) > 0) {
            return $this->json((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        $this->em->persist($actor);
        $this->em->flush();

        return $this->json($actor,Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="replace", methods="PUT")
     */
    public function replace($id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        $actorNew = $serializer->deserialize($data, Actor::class, 'json');

        $actor = $this->repository->find($id);

        if(!$actor) {
            return $this->json(null, Response::HTTP_NOT_FOUND);
        }

        $errors = $validator->validate($actorNew);
        if (count($errors) > 0) {
            return $this->json((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        $actor->setFirstName($actorNew->getFirstName());
        $actor->setLastName($actorNew->getLastName());

        $this->em->flush();

        return $this->json($actor);
    }

    /**
     * @Route("/{id}", name="delete", methods="DELETE")
     */
    public function delete($id): JsonResponse
    {
        $actor = $this->repository->find($id);
        if($actor !== null) { //not sure why (!$actor) is not working here
            $this->em->remove($actor);
            $this->em->flush();
        }
        return $this->json(null,Response::HTTP_NOT_FOUND);
    }
}
