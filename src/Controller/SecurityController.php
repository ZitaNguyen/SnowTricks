<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    private $utils;
    private $security;


    public function __construct(AuthenticationUtils $utils, Security $security)
    {
        $this->utils = $utils;
        $this->security = $security;
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


    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }


}
