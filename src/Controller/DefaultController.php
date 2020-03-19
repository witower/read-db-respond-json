<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("")
     */
    public function index()
    {
        $number = random_int(0,100);

        return $this->render('index.html.twig',
            [
                'number' => $number,
            ]);
    }
}