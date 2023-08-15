<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('authentication/login.html.twig');
    }

    public function forgetPassword(): Response
    {
        return $this->render('authentication/forgot-password.html.twig');
    }

    public function resetPassword(): Response
    {
        return $this->render('authentication/reset-password.html.twig');
    }
}
