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
            $session->set('id', $user->getId());
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
        return $this->redirectToRoute('login');
    }

    #[Route('/authors', name: 'authors')]
    public function authors(): Response
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
            "authors" => $authorsAndPictures
        ]);
    }

    #[Route('/author-pictures/{id}', name: 'author-pictures')]
    public function authorPictures($id): Response
    {
        $author = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $pictures = $author->getPictures();
        return $this->render('user/author-pictures.html.twig', [
            "pictures" => $pictures,
            "pseudo" => $author->getPseudo()
        ]);
    }

    #[Route('/my-pictures/{id}', name: 'my-pictures')]
    public function myPictures($id): Response
    {
        $author = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $pictures = $author->getPictures();
        return $this->render('pictures/my-pictures.html.twig', [
            "pictures" => $pictures,
            "pseudo" => $author->getPseudo()
        ]);
    }

    #[Route('/add-picture', name: 'add-picture')]
    public function addPictures(Request $request, SluggerInterface $slugger, SessionInterface $session): Response
    {
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $location = $request->request->get('location');
        $file = $request->files->get('picture');  
        $publishDate = new \DateTime();
        $picture = new Picture();
        
        if ($file && !empty($session->get('id'))) {
            $userId = $session->get('id');
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );
                $picture->setTitle($title);
                $picture->setDescription($description);
                $picture->setPublishDate($publishDate);
                $picture->setPicture($newFilename);
                $picture->setLocation($location);
                $picture->setLikes(0);
                $picture->setUserId($user);
                $user->addPicture($picture);
                $this->entityManager->persist($picture);
                $this->entityManager->flush();
            } catch (FileException $e) {
                var_dump($e);
            }
        }
        return $this->redirectToRoute('my-pictures', ["id" => 1]);
    }

}