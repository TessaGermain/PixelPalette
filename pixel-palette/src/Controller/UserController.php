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

    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('user/login.html.twig', [
            "error" => ""
        ]);
}

    #[Route('/login-form', name: 'login-form')]
    public function loginForm(Request $request, SessionInterface $session): Response
    {
        $mail = $request->request->get('mail');
        $password = $request->request->get('password');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['mail' => $mail]);
        if (password_verify($password, $user->getPassword())) {
            $session->set('pseudo', $user->getPseudo());
            $session->set('isLogin', true);
            return $this->redirectToRoute('home');    
        } else {
            $session->clear();
            return $this->render('user/login.html.twig', [
                "error" => "Mot de passe incorrect"
            ]);
        }
    } 

    #[Route('/signup', name: 'signup')]
    public function signup(): Response
    {
        return $this->render('user/signup.html.twig');
    }

    #[Route('/signup-form', name: 'signup-form')]
    public function signupForm(Request $request, SessionInterface $session): Response
    {
        $user = new User();
        $mail = $request->request->get('mail');
        $pseudo = $request->request->get('pseudo');
        $password = $request->request->get('password');
        $user->setMail($mail);
        $user->setPseudo($pseudo);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $session->set('pseudo', $pseudo);
        $session->set('isLogin', true);
        return $this->redirectToRoute('home');
    }
}
