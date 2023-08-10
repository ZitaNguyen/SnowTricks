<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{

    /**
     *
     */
    public function getTrick(string $slug, TrickRepository $trickRepository): Response
    {
        // Find the Trick by its slug
        $trick = $trickRepository
                ->findTrickBySlug($slug);

        if (empty($trick)) {
            throw $this->createNotFoundException('No post found for this slug');
        }

        return $this->render('trick.html.twig', [
            'trick' => $trick[0]
        ]);
    }
}