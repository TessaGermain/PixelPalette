<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Picture;

class PictureController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        $pictures = $this->entityManager->getRepository(Picture::class)->findAll();

        return $this->render('home.html.twig', [
            'pictures' => $pictures,
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
}
