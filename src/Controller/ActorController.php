<?php

namespace App\Controller;

use App\Entity\Actor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actor", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * @Route("", name="get_all", methods={"GET"})
     */
    public function getAll()
    {
        $entities = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();

        return $this-> json($entities);
    }

    /**
     * @Route("/{id}", name="get_by_id", methods={"GET"})
     */
    public function getById($id)
    {
        $entity = $this->getDoctrine()
            ->getRepository(Actor::class)
            -> find($id);

        if(!$entity) {
            throw $this->createNotFoundException(
                'No actor found for id '.$id
            );
        }
        return $this->json($entity);
    }

    /**
     * @Route("", name="create", methods={"POST"})
     */
    public function create()
    {
        $actor = new Actor();
        $actor->setFirstName("Anthony");
        $actor->setLastName("Hopkins");

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($actor);
        $entityManager->flush();

        return $this->json($actor,Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="replace", methods={"PUT"})
     */
    public function replace($newEntity)
    {
        return $this->json($newEntity);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete()
    {
        return $this->json("",Response::HTTP_NOT_FOUND);
    }
}
