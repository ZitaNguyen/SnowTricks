<?php

namespace App\Controller;

use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('authentication/login.html.twig', [
            'error'     => $error,
            'lastUsername' => $lastUsername
        ]);
    }

    public function forgotPassword(): Response
    {
        $form = $this->createForm(ForgotPasswordFormType::class);

        return $this->render('authentication/forgot-password.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
    }

    public function resetPassword(): Response
    {
        $form = $this->createForm(ResetPasswordFormType::class);

        return $this->render('authentication/reset-password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}
