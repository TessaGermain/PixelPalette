<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Picture;
use App\Entity\User;

class PictureController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/home', name: 'home')]
    public function home(SessionInterface $session): Response
    {
        $pictures = $this->entityManager->getRepository(Picture::class)->findAll();

        return $this->render('home.html.twig', [
            'pictures' => $pictures,
            "isLogin" => $session->get("isLogin", false),
            "loginUserId" => $session->get("id", 0),
        ]);
    }

    #[Route('/my-pictures/{id}', name: 'my-pictures')]
    public function myPictures($id, SessionInterface $session): Response
    {
        $author = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $pictures = $author->getPictures();
        return $this->render('pictures/my-pictures.html.twig', [
            "pictures" => $pictures,
            "isLogin" => $session->get("isLogin", false),
            "loginUserId" => $session->get("id", 0),
            "pseudo" => $session->get("pseudo", "")
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
        return $this->redirectToRoute('my-pictures', [
            "id" => 1,
            "isLogin" => $session->get("isLogin", false),
            "loginUserId" => $session->get("id", 0),
            "pseudo" => $session->get("pseudo", "")
        ]);
    }
}
