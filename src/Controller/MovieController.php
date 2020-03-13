<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie", name="movie_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("", name="get_all")
     */
    public function getAll()
    {
        $entities = $this->getDoctrine()
            ->getRepository(Movie::class)
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
            ->getRepository(Movie::class)
            -> find($id);

        if(!$entity) {
            throw $this->createNotFoundException(
                'No movie found for id '.$id
            );
        }
        return $this->json($entity);
    }
}
