<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\UserRepository;
use App\Form\LoginFormType;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(
        Request $request,
        UserRepository $userRepository
    ): Response
    {

        // Create the form
        $form = $this->createForm(LoginFormType::class);

        // Handle the form
        $form->handleRequest($request);

        

        if ($form->isSubmitted()) {
            // check if the user exists
            $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);
            if ($user) {
                // check if the password is correct
                if (password_verify($form->get('password')->getData(), $user->getPassword())) {
                    // redirect to the homepage
                    return $this->redirectToRoute('app_home');
                }
            } else {
                // redirect to the registration page
                return $this->redirectToRoute('app_register');
            }

        }

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'form' => $form,
        ]);
    }
}
