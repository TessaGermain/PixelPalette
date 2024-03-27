<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Picture;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/login', name: 'login')]
    public function login(SessionInterface $session): Response
    {
        return $this->render('user/login.html.twig', [
            "error" => "",
            "isLogin" => $session->get("isLogin", false),
            "loginUserId" => $session->get("id", 0),
        ]);
    }

    #[Route('/login-form', name: 'login-form')]
    public function loginForm(Request $request, SessionInterface $session): Response
    {
        $mail = $request->request->get('mail');
        $password = $request->request->get('password');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['mail' => $mail]);
        if (password_verify($password, $user->getPassword())) {
            $session->set('id', $user->getId());
            $session->set('pseudo', $user->getPseudo());
            $session->set('isLogin', true);
            return $this->redirectToRoute('home');    
        } else {
            $session->clear();
            return $this->render('user/login.html.twig', [
                "error" => "Mot de passe incorrect",
                "isLogin" => $session->get("isLogin", false),
                "loginUserId" => $session->get("id", 0),
            ]);
        }
    } 

    #[Route('/signup', name: 'signup')]
    public function signup(SessionInterface $session): Response
    {
        return $this->render('user/signup.html.twig', [
            "isLogin" => $session->get("isLogin", false),
            "loginUserId" => $session->get("id", 0),
        ]);
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
        return $this->redirectToRoute('login');
    }

    #[Route('/authors', name: 'authors')]
    public function authors(SessionInterface $session): Response
    {
        $authors = $this->entityManager->getRepository(User::class)->findAll();
        $authorsAndPictures = [];
        foreach ($authors as $author) {
            $authorsAndPictures[$author->getId()]["id"] = $author->getId();
            $authorsAndPictures[$author->getId()]["pseudo"] = $author->getPseudo();
            $pictures = $author->getPictures();
            $authorsAndPictures[$author->getId()]["numberPictures"] = count($pictures);
        }
        return $this->render('user/authors.html.twig', [
            "authors" => $authorsAndPictures,
            "isLogin" => $session->get("isLogin", false),
            "loginUserId" => $session->get("id", 0),
        ]);
    }

    #[Route('/author-pictures/{id}', name: 'author-pictures')]
    public function authorPictures($id, SessionInterface $session): Response
    {
        $author = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $pictures = $author->getPictures();
        return $this->render('user/author-pictures.html.twig', [
            "pictures" => $pictures,
            "pseudo" => $author->getPseudo(),
            "isLogin" => $session->get("isLogin", false),
            "loginUserId" => $session->get("id", 0),
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('home');
    }

}