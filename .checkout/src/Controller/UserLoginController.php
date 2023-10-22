<?php

namespace App\Controller;

use App\Form\LoginFormType;
use App\Form\RegisterFormType;
use App\Service\FormHandleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserLoginController extends AbstractController
{
    #[Route('/Login', name: 'login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, Security $security, FormHandleService $formHandle): Response
    {
        if ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('logout');
        }

        $loginForm = $this->createForm(LoginFormType::class);
        $registerForm = $this->createForm(RegisterFormType::class);

        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $error = $error->getMessage();
            if ($error == "The presented password is invalid.") {
                $error = "Špatné heslo!";
            } elseif ($error == "Bad credentials.") {
                $error = "Neexistující email!";
            }
            $this->addFlash('loginFailure', $error);
        }
        if ($formHandle->handleRegistrationForm($registerForm, $request)) {
            $this->addFlash('success', 'Zaregistrováno!');
            return $this->redirectToRoute('login');
        }

        return $this->render('yetinder/login.html.twig', [
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
            'error' => $error,
        ]);
    }

    #[Route('/Logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->render('yetinder/base.html.twig');
    }
}