<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{


    /**
     *
     */
    public function index(TrickRepository $trickRepository): Response
    {
        // Get tricks
        $tricks = $trickRepository->getTricks();

        if (empty($tricks)) {
            throw $this->createNotFoundException('No trick added');
        }

        return $this->render('index.html.twig', [
            'tricks' => $tricks
        ]);
    }

}