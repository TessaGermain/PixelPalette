<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('user/login.html.twig');
    }

    #[Route('/login-form', name: 'login-form')]
    public function loginForm(): Response
    {
        return $this->render('user/login.html.twig');
    } 

    #[Route('/signup', name: 'signup')]
    public function signup(): Response
    {
        return $this->render('user/signup.html.twig');
    }

    #[Route('/signup-form', name: 'signup-form')]
    public function signupForm(): Response
    {
        return $this->render('user/signup.html.twig');
    }
}
