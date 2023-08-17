<?php

namespace App\Controller;

use App\Form\ForgotPasswordFormType;
use App\Form\LoginFormType;
use App\Form\ResetPasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AbstractController
{

    public function index(): Response
    {
        $form = $this->createForm(LoginFormType::class);

        return $this->render('authentication/login.html.twig', [
            'loginForm' => $form->createView(),
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
