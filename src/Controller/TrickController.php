<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TrickController extends AbstractController
{
    public function getTrick(): Response
    {
        // Get a trick by id

        return $this->render('trick.html.twig');
    }
}