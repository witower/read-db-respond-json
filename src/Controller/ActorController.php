<?php

namespace App\Controller;

use App\Entity\Actor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actor", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * @Route("", name="get_all")
     */
    public function getAll()
    {
        $entities = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();

        return $this-> json($entities);
    }

    /**
     * @Route("/{id}", name="get_by_id")
     * @param $id
     * @return JsonResponse
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
}
