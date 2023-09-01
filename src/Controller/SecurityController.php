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

        return $this->render('security/login.html.twig', [
            'error'         => $error,
            'lastUsername'  => $lastUsername
        ]);
    }


    public function logout(): never
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
    

    public function forgotPassword(): Response
    {
        $form = $this->createForm(ForgotPasswordFormType::class);

        return $this->render('security/forgot-password.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
    }

    public function resetPassword(): Response
    {
        $form = $this->createForm(ResetPasswordFormType::class);

        return $this->render('security/reset-password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}
