<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    private $utils;


    public function __construct(AuthenticationUtils $utils)
    {
        $this->utils = $utils;
    }


    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(): Response
    {

        // get the login error if there is one
        $error = $this->utils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $this->utils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error'         => $error,
            'lastUsername'  => $lastUsername
        ]);
    }


    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }


}
