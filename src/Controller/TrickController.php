<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
    private $trickRepository;

    public function __construct(TrickRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    /**
     *
     */
    public function index(): Response
    {
        // Get tricks
        $tricks = $this->trickRepository->getTricks();

        if (empty($tricks)) {
            throw $this->createNotFoundException('No trick added');
        }

        return $this->render('tricks/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     *
     */
    public function getTrick(string $slug): Response
    {
        // Find the Trick by its slug
        $trick = $this->trickRepository->findTrickBySlug($slug);

        if (empty($trick)) {
            throw $this->createNotFoundException('No post found for this slug');
        }

        return $this->render('tricks/get.html.twig', [
            'trick' => $trick[0]
        ]);
    }
}