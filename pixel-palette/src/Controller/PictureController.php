<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Picture;
use App\Entity\User;
use App\Entity\Comment;


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
        if ($session->get("isLogin") === false) {
            return $this->redirectToRoute('login');
        }
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
            "id" => $session->get("id", 0),
        ]);
    }

    #[Route('/like/{id}', name: 'like')]
    public function like($id): Response
    {
        $picture = $this->entityManager->getRepository(Picture::class)->findOneBy(['id' => $id]);
        $picture->setLikes($picture->getLikes() + 1);
        $this->entityManager->persist($picture);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail($id, SessionInterface $session): Response
    {
        $picture = $this->entityManager->getRepository(Picture::class)->findOneBy(['id' => $id]);
        $pictureComments = $picture->getComments();

        return $this->render('picture/detail.html.twig', [
            'pictureComments' => $pictureComments,
            
            'picture' => $picture,
            "isLogin" => $session->get("isLogin", false),
            "loginUserId" => $session->get("id", 0),
            "pseudo" => $session->get("pseudo", "")
        ]);
    }

    #[Route('/add-comment/{id}', name: 'add-comment')]
    public function addComment($id, SessionInterface $session, Request $request): Response
    {   
        if ($session->get("isLogin") === false) {
            return $this->redirectToRoute('login');
        }
        $comment = $request->request->get('comment');
        $entityComment = new Comment();
        $entityComment->setComment($comment);
        $publishDate = new \DateTime();
        $entityComment->setPublishDate($publishDate);
        $picture = $this->entityManager->getRepository(Picture::class)->findOneBy(['id' => $id]);
        $userId = $session->get('id');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
        $picture->addComment($entityComment);
        $user->addComment($entityComment);
        $this->entityManager->persist($entityComment);
        $this->entityManager->flush();

        return $this->redirectToRoute('detail', [
            "id" => $id,
        ]);
    }

    #[Route('delete-picture/{id}', name: 'delete-picture')]
    public function deletePicture($id, SessionInterface $session): Response
    {
        if ($session->get("isLogin") === false) {
            return $this->redirectToRoute('login');
        }
        $picture = $this->entityManager->getRepository(Picture::class)->findOneBy(['id' => $id]);
        $this->entityManager->remove($picture);
        $this->entityManager->flush();
        return $this->redirectToRoute('my-pictures', [
            "id" => $session->get("id", 0),
        ]);
    }

    #[Route('edit-picture/{id}', name: 'edit-picture')]
    public function editPicture($id, SessionInterface $session): Response
    {
        if ($session->get("isLogin") === false) {
            return $this->redirectToRoute('login');
        }
        $picture = $this->entityManager->getRepository(Picture::class)->findOneBy(['id' => $id]);
        return $this->render('pictures/edit-picture.html.twig', [
            "picture" => $picture,
            "isLogin" => $session->get("isLogin", false),
            "loginUserId" => $session->get("id", 0),
            "pseudo" => $session->get("pseudo", "")
        ]);
    }

    #[Route('edit-picture-form/{id}', name: 'edit-picture-form')]
    public function editPictureForm($id, SessionInterface $session): Response
    {
        $picture = $this->entityManager->getRepository(Picture::class)->findOneBy(['id' => $id]);
        $this->entityManager->persist($picture);
        $this->entityManager->flush();
        return $this->redirectToRoute('detail', [
            "id" => $id,
        ]);
    }

}
